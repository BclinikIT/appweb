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
            $newUser = Imc_Formulario::create($dataToLog);
            $encryptedId = Crypt::encryptString($newUser->id);
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
            $subject = 'Resultado IMC';
            $view = 'emails.imc_formulario';
            $emailData = $dataToPDF;
            $attachments = [
                ['content' => $pdfContent, 'name' => 'Respuesta_Calculadora_IMC.pdf']
            ];

            $this->emailService->sendEmail($recipient, $subject, $view, $emailData, $attachments);


            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::error('Error al procesar datos del webhook: ' . $e->getMessage());
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

            $dataToSave = [
                'nombre' => $nombre,
                'apellido' => '',
                'nombre_invitado' => $nombre_invitado,
                'apellido_invitado' => '',
                'correo' => $email_referido,
                'telefono' => $telefono_referido,
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s'),
                'form_id' => $form_id
            ];
            $newImc_invitacion = Imc_Invitacion::create($dataToSave);
            $encryptedId = Crypt::encryptString($newImc_invitacion->id);
            $link = url('/pdf/download/imc_invitado') . '?id=' . urlencode($encryptedId);
            $fechaFormateada = Carbon::parse(date('Y-m-d'))->format('d/m/Y');


            $mail = new PHPMailer(true);
            $mail->CharSet = 'UTF-8';
            $mail->isSMTP();
            $mail->Host = 'bclinik.com';
            $mail->Port = 465;
            $mail->SMTPSecure = 'ssl';
            $mail->SMTPAuth = true;
            $mail->Username = 'noreply@bclinik.com';
            $mail->Password = 'Password$001';
            $mail->setFrom('noreply@bclinik.com', 'Invitación Calculadora IMC');
            $mail->addReplyTo('noreply@bclinik.com', 'Invitación Calculadora IMC');
            $mail->addAddress($email_referido);


            $body = '
                    <html dir="ltr" lang="en">
                        <head>
                            <meta charset="UTF-8" />
                            <meta name="viewport" content="width=device-width, initial-scale=1.0" />
                            <title>Invitación</title>
                            <link rel="preconnect" href="https://fonts.googleapis.com" />
                            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="" />
                            <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400&display=swap" rel="stylesheet" />
                            <style>
                                body {
                                    font-family: "Lato", sans-serif;
                                    margin: 0;
                                    padding: 0;
                                    background-color: #ffffff;
                                }
                                table {
                                    border-spacing: 0;
                                    border-collapse: collapse;
                                }
                                img {
                                    border: 0;
                                    display: block;
                                }
                                p {
                                    margin: 0;
                                    padding: 10px 0;
                                }
                                a {
                                    color: #11455d;
                                    text-decoration: none;
                                }
                            </style>
                            <!--[if mso]>
                                        <style type="text/css">
                                            ul {
                                        margin: 0 !important;
                                        }
                                        ol {
                                        margin: 0 !important;
                                        }
                                        li {
                                        margin-left: 47px !important;
                                        }

                                            </style><![endif]
                                            -->
                        </head>
                        <body class="body">
                            <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0">
                                <tbody>
                                    <tr>
                                        <td align="center">
                                            <table width="600" cellpadding="0" cellspacing="0" border="0" style="color: #595959;">
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <img src="https://appweb.bclinik.com/img/img_correos/header.png" alt="header" width="600" style="max-width: 100%;" />
                                                        </td>
                                                    </tr>
                                                    <tr esd-text="true" class="esd-text">
                                                        <td align="right" style="padding: 10px;">
                                                            <p style="line-height: 150% !important; color: #002545" align="right">Fecha: ' . $fechaFormateada . '</p>
                                                            <p style="line-height: 150% !important; color: #002545" align="right"><a href="' . $link . '" style="display: inline-block; padding: 1px 15px; margin-left: 10px; background-color: #007bff; color: #fff; text-decoration: none; border-radius: 5px;">Descargar</a></p>
                                                                                                                
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="padding: 10px;" esd-text="true" class="esd-text">
                                                            <p>Hola ' . $nombre_invitado . '</p>
                                                            <p>Tu amigo ' . $nombre . ', nos ha solicitado que te enviemos esta información.</p>
                                                            <p style="color: #11455d; text-align: center;">¿Quieres saber si tu peso es adecuado para tu edad?</p>
                                                            <p style="color: #11455d; text-align: center;">¿Si tu metabolismo está lento?</p>
                                                            <p style="color: #11455d; text-align: center;">¿Cuál es la causa principal por la que subes de peso?</p>
                                                            <p style="color: #11455d; text-align: center;">¿Qué es lo que no te deja bajar de peso?</p>
                                                        </td>
                                                    </tr>
                                                    <tr esd-text="true" class="esd-text">
                                                        <td style="padding: 10px; text-align: center;">Averígualo ingresa a <a href="https://bclinik.com/imc/">www.bclinik.com/imc</a></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="center" style="padding: 20px 0;">
                                                            <img src="https://appweb.bclinik.com/img/img_correos/imc.png" alt="imc" width="540" style="max-width: 90%;" />
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="padding: 10px;" esd-text="true" class="esd-text">
                                                            <ul style="list-style-type: none; padding: 0;">
                                                                <li style="padding: 5px 0;">
                                                                    <em><strong style="color: #f1c232;">Entre 25 a 29.99</strong> es sobrepeso también llamado <span style="color: #11455d;">pre obesidad.</span></em>
                                                                </li>
                                                                <li style="padding: 5px 0;">
                                                                    <em><strong style="color: #ff9900;">Entre 30 a 34.99</strong> estás a tiempo de retroceder categorías y llegar a lo normal.</em>
                                                                </li>
                                                                <li style="padding: 5px 0;">
                                                                    <em><strong style="color: #ff0000;">Entre 35 a 39.99</strong> estás a tiempo de retroceder categorías y evitar el Síndrome Metabólico.</em>
                                                                </li>
                                                                <li style="padding: 5px 0;">
                                                                    <em>
                                                                        <strong style="color: #990000;">Mayor de 40</strong> no solo puedes retroceder categorías, además, controlas el Síndrome Metabólico que haya aparecido: <br />
                                                                        (Obesidad, Diabetes, Hipertensión, Colesterol y Triglicéridos altos).
                                                                    </em>
                                                                </li>
                                                            </ul>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="padding: 10px;" esd-text="true" class="esd-text">
                                                            <p><strong>Ubícanos en:</strong></p>
                                                            <p>
                                                                <a
                                                                    href="https://www.google.com/search?q=bio+clinik+zona+10&rlz=1C1UEAD_enGT1090GT1090&oq=bio+clinik+zona+10&gs_lcrp=EgZjaHJvbWUyBggAEEUYOTIJCAEQIRgKGKABMgkIAhAhGAoYoAHSAQgzNTU0ajBqN6gCALACAA&sourceid=chrome&ie=UTF-8"
                                                                >
                                                                    Edificio 01010 - Zona 10
                                                                </a>
                                                            </p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <img src="https://appweb.bclinik.com/img/img_correos/footer.png" alt="footer" width="600" style="max-width: 100%;" />
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </body>
                    </html>


                ';
            $mail->Subject = 'Invitación Calculadora IMC';
            $mail->isHTML(true);
            $mail->MsgHTML($body);

            if (!$mail->send()) {
                throw new \Exception('Error al enviar el correo: ' . $mail->ErrorInfo);
            }

        } catch (\Exception $e) {
            Log::error('Error al procesar datos del webhook: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Error al procesar los datos del webhook'], 500);
        }
    }
}
