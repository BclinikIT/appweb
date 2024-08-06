<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Imc_Formulario;
use App\Models\Imc_Invitacion;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PDF; // Asegúrate de importar la clase PDF
use App\Models\FormularioImc;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;

class ImcWebhookController extends Controller
{
    public function pdf(Request $request)
    {
        $url_img = public_path('/img/img_correos/');
        $encryptedId = $request->query('id');
        $decryptedId = Crypt::decryptString($encryptedId);

        $user = FormularioImc::findOrFail($decryptedId);
        $nombre = $user->nombre;
        $apellido = $user->apellido;
        $edad = $user->edad;
        $genero = $user->genero;
        $peso_en_libras = $user->peso_en_libras;
        $altura_en_centimetros = $user->altura_en_cms;
        $correo = $user->correo;
        $telefono = $user->telefono;


        $formName = '';
        $form_id = $user->form_id;



        $peso_r = ($peso_en_libras / 2.20462);
        $talla = ($altura_en_centimetros / 100);
        $tallas_r = ($talla * $talla);

        $result = round((($peso_r / $tallas_r) * 10) / 10, 2);


        $categoria = $user->categoria;
        $fechaFormateada = Carbon::parse(date('Y-m-d'))->format('d/m/Y');

        $dataToPDF= [
            'date'=>date('d-m-Y'),
            'nombre'=>$nombre,
            'apellido'=>$apellido,
            'imc'=>$result,
            'categoria'=>$categoria,
        ];




        $pdf = PDF::loadView('pdf.imc_formulario', $dataToPDF);
        return $pdf->download('Respuesta_Calculadora_IMC.pdf');
    }

    public function pdf_imc_invitado(Request $request)
    {


    }
    
    public function handle(Request $request)
    {
        try {
            $nombre = $request->input('Nombre:');
            $apellido = $request->input('Apellido:');
            $edad = $request->input('Edad:');
            $genero = $request->input('Genero:');
            $peso_en_libras = $request->input('Peso_en_libras:');
            $altura_en_centimetros = $request->input('Altura_en_cms:');
            $correo = $request->input('Correo:');
            $telefono = $request->input('Telefono:');


            $formName = $request->input('form_name');
            $form_id = $request->input('form_id');



            $peso_r = ($peso_en_libras / 2.20462);
            $talla = ($altura_en_centimetros / 100);
            $tallas_r = ($talla * $talla);

            $result = round((($peso_r / $tallas_r) * 10) / 10, 2);

            function obtenerCategoriaIMC($imc)
            {
                if ($imc < 18.5) {
                    return "Infrapeso";
                } elseif ($imc >= 18.5 && $imc < 25) {
                    return "Normal";
                } elseif ($imc >= 25 && $imc < 30) {
                    return "Sobrepeso";
                } elseif ($imc >= 30 && $imc < 40) {
                    return "Obesidad";
                } elseif ($imc >= 40) {
                    return "Obesidad mórbida";
                } else {
                    return "Valor IMC no válido";
                }
            }
            $categoria = obtenerCategoriaIMC($result);

            $fechaFormateada = Carbon::parse(date('Y-m-d'))->format('d/m/Y');
            $dataToLog = [
                'nombre' => $nombre,
                'apellido' => $apellido,
                'edad' => $edad,
                'genero' => $genero,
                'peso_en_libras' => $peso_en_libras,
                'altura_en_cms' => $altura_en_centimetros,
                'correo' => $correo,
                'telefono' => $telefono,
                'categoria' => $categoria,
                'imc' => $result,
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s'),
                'form_id' => "0"

            ];

            // Crear nuevo registro
            $newFormulario = Imc_Formulario::create($dataToLog);
            $encryptedId = Crypt::encryptString($newFormulario->id);
            $link = url('/pdf/download/imc_formulario') . '?id=' . urlencode($encryptedId);


            $dataToPDF= [
                'date'=>date('d-m-Y'),
                'nombre'=>$nombre,
                'apellido'=>$apellido,
                'imc'=>$result,
                'categoria'=>$categoria,
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


            return response()->json(['status' => 'error', 'message' => 'Error al procesar los datos del webhook'], 500);
        }
    }

    public function handleImcInvitacion(Request $request)
    {
        try {
            $data = $request->all();

            $nombre = $request->input('Tu_Nombre');
            $nombre_invitado = $request->input('Nombre_Referido');
            $telefono_referido = $request->input('Telefono_Referido');
            $email_referido = $request->input('Email_Referido');
            $form_id = $request->input('form_id');

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
