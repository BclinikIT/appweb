<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\EmailService;
use App\Models\Imc_Formulario;
use App\Models\Imc_Invitacion;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
use PDF;
use App\Models\FormularioImc;
use App\Models\FormularioImcInvitados;


class ImcWebhookController extends Controller
{
    protected $emailService;
    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    private function calculateImc($peso_en_libras, $altura_en_centimetros)
    {
        $peso_kg = $peso_en_libras / 2.20462;
        $altura_m = $altura_en_centimetros / 100;
        return round(($peso_kg / ($altura_m * $altura_m)), 2);
    }


    private function obtenerCategoriaIMC($imc)
    {
        if ($imc < 18.5) {
            return "Infrapeso";
        } elseif ($imc >= 18.5 && $imc < 25) {
            return "Normal";
        } elseif ($imc >= 25 && $imc < 30) {
            return "Sobrepeso";
        } elseif ($imc >= 30 && $imc < 40) {
            return "Obesidad";
        } else {
            return "Obesidad mórbida";
        }
    }


    public function pdf(Request $request)
    {
        $encryptedId = $request->query('id');
        $decryptedId = Crypt::decryptString($encryptedId);
        $formulario = FormularioImc::findOrFail($decryptedId);

        $dataToPDF = [
            'date' => date('d-m-Y'),
            'nombre' => $formulario->nombre,
            'apellido' => $formulario->apellido,
            'imc' => $formulario->imc,
            'categoria' => $formulario->categoria,
        ];

        $pdf = PDF::loadView('pdf.imc_formulario', $dataToPDF);
        return $pdf->download('Resultado IMC.pdf');
    }

    public function pdf_imc_invitado(Request $request)
    {


        $encryptedId = $request->query('id');
        $decryptedId = Crypt::decryptString($encryptedId);
        $invitados = FormularioImcInvitados::findOrFail($decryptedId);

        $dataToPDF = [
            'date' => date('d-m-Y'),
            'nombre' => $invitados->nombre,
            'apellido' => $invitados->apellido,
            'nombre_invitado' => $invitados->nombre_invitado,
            'apellido_invitado' => $invitados->apellido_invitado,
            'correo' => $invitados->correo,
            'telefono' => $invitados->telefono,
            'fecha' => $invitados->fecha,
            'hora' => $invitados->hora,
            'form_id' => $invitados->form_id,
        ];

        $pdf = PDF::loadView('pdf.imc_invitacion', $dataToPDF);
        return $pdf->download('Invitacion IMC.pdf');


    }
    public function handle(Request $request)
    {
        try {
            // Recoger datos del request
            $data = $request->only([
                'Nombre:',
                'Apellido:',
                'Edad:',
                'Genero:',
                'Peso_en_libras:',
                'Altura_en_cms:',
                'Correo:',
                'Telefono:',
                'form_name',
                'form_id'
            ]);

            // Calcular IMC y categoría
            $result = $this->calculateImc($data['Peso_en_libras:'], $data['Altura_en_cms:']);
            $categoria = $this->obtenerCategoriaIMC($result);

            // Preparar datos para guardar
            $dataToLog = [
                'nombre' => $data['Nombre:'],
                'apellido' => $data['Apellido:'],
                'edad' => $data['Edad:'],
                'genero' => $data['Genero:'],
                'peso_en_libras' => $data['Peso_en_libras:'],
                'altura_en_cms' => $data['Altura_en_cms:'],
                'correo' => $data['Correo:'],
                'telefono' => $data['Telefono:'],
                'categoria' => $categoria,
                'imc' => $result,
                'fecha' => Carbon::now()->format('Y-m-d'),
                'hora' => Carbon::now()->format('H:i:s'),
                'form_id' => $data['form_id'] ?? "0"
            ];

            // Crear nuevo registro
            $newFormulario = Imc_Formulario::create($dataToLog);
            $encryptedId = Crypt::encryptString($newFormulario->id);
            $link = url('/pdf/download/imc_formulario') . '?id=' . urlencode($encryptedId);

            // Preparar datos para PDF
            $dataToPDF = [
                'date' => Carbon::now()->format('d-m-Y'),
                'nombre' => $data['Nombre:'],
                'apellido' => $data['Apellido:'],
                'imc' => $result,
                'categoria' => $categoria,
                'link' => $link
            ];





            $pdf = PDF::loadView('pdf.imc_formulario', $dataToPDF);
            $pdfContent = $pdf->output();



            // Preparar y enviar el correo electrónico
            $recipient = $data['Correo:'];
            $subject = 'Calculadora IMC';
            $view = 'emails.imc_formulario';
            $emailData = $dataToPDF;
            $attachments = [
                ['content' => $pdfContent, 'name' => 'Respuesta_Calculadora_IMC.pdf']
            ];

            $fromName = 'Calculadora IMC'; // Dynamic value
            $replyToName = 'Calculadora IMC';
            $this->emailService->sendEmail($recipient, $subject, $view, $emailData, $attachments, 'noreply@bclinik.com', $fromName, 'noreply@bclinik.com', $replyToName);


            //return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::error('Error al procesar datos del webhook: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Error al procesar los datos del webhook'], 500);
        }
    }
    public function handleImcInvitacion(Request $request)
        {
            try {
                // Retrieve all necessary input data from the request
                $data = $request->only([
                    'Tu_Nombre',
                    'Nombre_Referido',
                    'Telefono_Referido',
                    'Email_Referido',
                    'form_id'
                ]);

                // Extract individual variables for clarity
                $nombre = $data['Tu_Nombre'];
                $nombre_invitado = $data['Nombre_Referido'];
                $telefono_referido = $data['Telefono_Referido'];
                $email_referido = $data['Email_Referido'];
                $form_id = $data['form_id'];

                // Prepare data to save in the database
                $dataToSave = [
                    'nombre' => $nombre,
                    'apellido' => '',
                    'nombre_invitado' => $nombre_invitado,
                    'apellido_invitado' => '',
                    'correo' => $email_referido,
                    'telefono' => $telefono_referido,
                    'fecha' => now()->format('Y-m-d'),
                    'hora' => now()->format('H:i:s'),
                    'form_id' => $form_id
                ];

                // Save the data and create a new Imc_Invitacion record
                $newImc_invitacion = Imc_Invitacion::create($dataToSave);

                // Encrypt the ID and create a link for the invitation
                $encryptedId = Crypt::encryptString($newImc_invitacion->id);
                $link = url('/pdf/download/imc_invitado') . '?id=' . urlencode($encryptedId);

                // Format the date for the email
                $fechaFormateada = now()->format('d/m/Y');

                // Prepare email data for the Blade view
                $emailData = [
                    'date' => $fechaFormateada,
                    'nombre' => $nombre,
                    'apellido' => '',
                    'nombre_invitado' => $nombre_invitado,
                    'apellido_invitado' => '',
                    'correo' => $email_referido,
                    'telefono' => $telefono_referido,
                    'link' => $link,
                ];

                // Generate PDF content
                $pdf = PDF::loadView('pdf.imc_invitacion', $emailData);
                $pdfContent = $pdf->output();

                // Prepare and send the email
                $recipient = $email_referido;
                $subject = 'Invitación Calculadora IMC';
                $view = 'emails.imc_invitacion';
                $attachments = [
                    ['content' => $pdfContent, 'name' => 'Invitacion_Calculadora_IMC.pdf']
                ];

                $fromName = 'Invitación Calculadora IMC'; // Dynamic value
                $replyToName = 'Invitación Calculadora IMC'; // Dynamic value

                $this->emailService->sendEmail($recipient, $subject, $view, $emailData, $attachments, 'noreply@bclinik.com', $fromName, 'noreply@bclinik.com', $replyToName);

               //  return response()->json(['status' => 'success']);
            } catch (\Exception $e) {
                // Log the error and return an error response
                Log::error('Error al procesar datos del webhook: ' . $e->getMessage());
                return response()->json(['status' => 'error', 'message' => 'Error al procesar los datos del webhook'], 500);
            }
        }


}
