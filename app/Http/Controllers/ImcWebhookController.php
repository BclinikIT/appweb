<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Imc_Formulario;
use App\Models\Imc_Invitacion;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class ImcWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $data = $request->all();

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


            Imc_Formulario::create($dataToLog);


            $mail = new PHPMailer(true);
            $mail->CharSet = 'UTF-8';
            $mail->isSMTP();
            $mail->Host = 'bclinik.com';
            $mail->Port = 465;
            $mail->SMTPSecure = 'ssl';
            $mail->SMTPAuth = true;
            $mail->Username = 'noreply@bclinik.com';
            $mail->Password = 'Password$001';
            $mail->setFrom('noreply@bclinik.com', 'Calculadora IMC');
            $mail->addReplyTo('noreply@bclinik.com', 'Calculadora IMC');
            $mail->addAddress($correo);

            $body = '
                    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                    <html
                        dir="ltr"
                        xmlns="http://www.w3.org/1999/xhtml"
                        xmlns:o="urn:schemas-microsoft-com:office:office"
                    >
                        <head>
                            <meta charset="UTF-8" />
                            <meta content="width=device-width, initial-scale=1" name="viewport" />
                            <meta name="x-apple-disable-message-reformatting" />
                            <meta http-equiv="X-UA-Compatible" content="IE=edge" />
                            <meta content="telephone=no" name="format-detection" />
                            <title></title>
                            <!--[if (mso 16)]>
                                <style type="text/css">
                                    a {
                                        text-decoration: none;
                                    }
                                </style>
                            <![endif]-->
                            <!--[if gte mso 9
                                ]><style>
                                    sup {
                                        font-size: 100% !important;
                                    }
                                </style><!
                            [endif]-->
                            <!--[if gte mso 9]>
                                <xml>
                                    <o:OfficeDocumentSettings>
                                        <o:AllowPNG></o:AllowPNG>
                                        <o:PixelsPerInch>96</o:PixelsPerInch>
                                    </o:OfficeDocumentSettings>
                                </xml>
                            <![endif]-->
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
                    --></head>
                        <body class="body">
                            <div dir="ltr" class="es-wrapper-color">
                                <!--[if gte mso 9]>
                                    <v:background xmlns:v="urn:schemas-microsoft-com:vml" fill="t">
                                        <v:fill type="tile" color="#f6f6f6"></v:fill>
                                    </v:background>
                                <![endif]-->
                                <table class="es-wrapper" width="100%" cellspacing="0" cellpadding="0">
                                    <tbody>
                                        <tr>
                                            <td class="esd-email-paddings" valign="top">
                                                <table class="es-content" cellspacing="0" cellpadding="0" align="center">
                                                    <tbody>
                                                        <tr>
                                                            <td class="esd-stripe" align="center">
                                                                <table
                                                                    class="es-content-body"
                                                                    width="600"
                                                                    cellspacing="0"
                                                                    cellpadding="0"
                                                                    bgcolor="#ffffff"
                                                                    align="center"
                                                                    style="border-width: 0"
                                                                >
                                                                    <tbody>
                                                                        <tr>
                                                                            <td class="esd-structure es-p20t es-p20r es-p20l" align="left">
                                                                                <table width="100%" cellspacing="0" cellpadding="0">
                                                                                    <tbody>
                                                                                        <tr>
                                                                                            <td class="esd-container-frame" width="560" valign="top" align="center">
                                                                                                <table width="100%" cellspacing="0" cellpadding="0">
                                                                                                    <tbody>
                                                                                                        <tr>
                                                                                                            <td align="center" class="esd-block-image" style="font-size: 0">
                                                                                                                <a target="_blank">
                                                                                                                    <img
                                                                                                                        src="https://appweb.bclinik.com/img/img_correos/header.png"
                                                                                                                        alt=""
                                                                                                                        width="560"
                                                                                                                        class="adapt-img"
                                                                                                                    />
                                                                                                                </a>
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                    </tbody>
                                                                                                </table>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="esd-structure es-p20t es-p35l es-p25r" align="left">
                                                                                <table cellpadding="0" cellspacing="0">
                                                                                    <tbody>
                                                                                        <tr>
                                                                                            <td width="540" class="esd-container-frame" align="left">
                                                                                                <table cellpadding="0" cellspacing="0" width="100%" role="presentation">
                                                                                                    <tbody>
                                                                                                        <tr>
                                                                                                            <td align="left" class="esd-block-text">
                                                                                                                <p style="line-height: 150% !important; color: #002545" align="right">Fecha: ' . date('Y-m-d') . '</p>
                                                                                                                <p style="line-height: 150% !important">Hola ' . $nombre . ' ' . $apellido . '</p>
                                                                                                                <p style="line-height: 150% !important">Gracias por confiarnos tus datos:</p>
                                                                                                                <p style="line-height: 150% !important">
                                                                                                                    Tu <b style="color: #002545">IMC</b> es de ' . $result . ' que corresponde a la categoría
                                                                                                                    ' . $categoria . '
                                                                                                                </p>
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                    </tbody>
                                                                                                </table>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="esd-structure es-p20t es-p20r es-p20l" align="left">
                                                                                <table cellpadding="0" cellspacing="0">
                                                                                    <tbody>
                                                                                        <tr>
                                                                                            <td width="560" class="esd-container-frame" align="left">
                                                                                                <table cellpadding="0" cellspacing="0" width="100%" role="presentation">
                                                                                                    <tbody>
                                                                                                        <tr>
                                                                                                            <td align="left" class="esd-block-text">
                                                                                                                <p align="center" style="color: #002545">
                                                                                                                    <em
                                                                                                                        ><i
                                                                                                                            >El índice de masa corporal es la relación de tu peso con tu estatura.</i
                                                                                                                        ></em
                                                                                                                    >
                                                                                                                </p>
                                                                                                                <p align="center" style="color: #002545">
                                                                                                                    <em
                                                                                                                        ><i
                                                                                                                            ><i
                                                                                                                                >Es el indicador más confiable para saber si tienes sobrepeso o ya estás en
                                                                                                                                obesidad.</i
                                                                                                                            ></i
                                                                                                                        ></em
                                                                                                                    >
                                                                                                                </p>
                                                                                                                <p>
                                                                                                                    <em><i>​</i></em>
                                                                                                                </p>
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                    </tbody>
                                                                                                </table>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="esd-structure es-p20t es-p20r es-p20l" align="left">
                                                                                <table cellpadding="0" cellspacing="0">
                                                                                    <tbody>
                                                                                        <tr>
                                                                                            <td width="560" class="esd-container-frame" align="left">
                                                                                                <table cellpadding="0" cellspacing="0" width="100%" role="presentation">
                                                                                                    <tbody>
                                                                                                        <tr>
                                                                                                            <td align="center" class="esd-block-image" style="font-size: 0">
                                                                                                                <a target="_blank">
                                                                                                                    <img
                                                                                                                        src="https://appweb.bclinik.com/img/img_correos/imc.png"
                                                                                                                        alt=""
                                                                                                                        width="421"
                                                                                                                        class="adapt-img"
                                                                                                                    />
                                                                                                                </a>
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                    </tbody>
                                                                                                </table>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="esd-structure es-p20t es-p20r es-p20l" align="left">
                                                                                <table cellpadding="0" cellspacing="0">
                                                                                    <tbody>
                                                                                        <tr>
                                                                                            <td width="560" class="esd-container-frame" align="left">
                                                                                                <table cellpadding="0" cellspacing="0" width="100%" role="presentation">
                                                                                                    <tbody>
                                                                                                        <tr>
                                                                                                            <td align="left" class="esd-block-text">
                                                                                                                <ul>
                                                                                                                    <li>
                                                                                                                        <p>
                                                                                                                            <em
                                                                                                                                ><span style="color: #f1c232">​Entre 25 a 29.99</span> &nbsp;es sobrepeso
                                                                                                                                también llamado pre obesidad.
                                                                                                                            </em>
                                                                                                                        </p>
                                                                                                                    </li>
                                                                                                                    <li>
                                                                                                                        <p>
                                                                                                                            <em
                                                                                                                                ><span style="color: #ff9900">Entre 30 a 34.99</span> estás a tiempo de
                                                                                                                                retroceder categorías y llegar a lo normal.
                                                                                                                            </em>
                                                                                                                        </p>
                                                                                                                    </li>
                                                                                                                    <li>
                                                                                                                        <p>
                                                                                                                            <em
                                                                                                                                ><span style="color: #ff0000">Entre 35 a 39.99</span> estas a tiempo de
                                                                                                                                retroceder categorías y evitar el Síndrome Metabólico.
                                                                                                                            </em>
                                                                                                                        </p>
                                                                                                                    </li>
                                                                                                                    <li>
                                                                                                                        <p style="line-height: 150% !important" align="left">
                                                                                                                            <em
                                                                                                                                ><span style="color: #990000">Mayor de 40</span> no solo puedes retroceder
                                                                                                                                categorías, además, controlas el Síndrome Metabólico que haya aparecido:
                                                                                                                                (Obesidad, Diabetes, Hipertensión, Colesterol y Triglicéridos altos).</em
                                                                                                                            >
                                                                                                                        </p>
                                                                                                                    </li>
                                                                                                                </ul>
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                    </tbody>
                                                                                                </table>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="esd-structure es-p20t es-p20r es-p20l" align="left">
                                                                                <table cellpadding="0" cellspacing="0">
                                                                                    <tbody>
                                                                                        <tr>
                                                                                            <td width="560" class="esd-container-frame" align="left">
                                                                                                <table cellpadding="0" cellspacing="0" width="100%" role="presentation">
                                                                                                    <tbody>
                                                                                                        <tr>
                                                                                                            <td align="left" class="esd-block-text">
                                                                                                                <p style="color: #002545"><strong>Interpretación de resultados</strong></p>
                                                                                                                <p style="color: #666666" align="justify">
                                                                                                                    La OMS Organización Mundial de la Salud, establece que una definición comúnmente
                                                                                                                    en uso con los siguientes valores, acordados en 1997, publicados en 2000 y
                                                                                                                    ajustados en 2010.
                                                                                                                </p>
                                                                                                                <p style="color: #002545"><strong>​</strong></p>
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                    </tbody>
                                                                                                </table>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="esd-structure es-p20t es-p20r es-p20l" align="left">
                                                                                <table cellpadding="0" cellspacing="0">
                                                                                    <tbody>
                                                                                        <tr>
                                                                                            <td width="560" class="esd-container-frame" align="left">
                                                                                                <table cellpadding="0" cellspacing="0" width="100%" role="presentation">
                                                                                                    <tbody>
                                                                                                        <tr>
                                                                                                            <td align="center" class="esd-block-image" style="font-size: 0">
                                                                                                                <a target="_blank">
                                                                                                                    <img
                                                                                                                        src="https://appweb.bclinik.com/img/img_correos/tabla.png"
                                                                                                                        alt=""
                                                                                                                        width="560"
                                                                                                                        class="adapt-img"
                                                                                                                    />
                                                                                                                </a>
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                    </tbody>
                                                                                                </table>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="esd-structure es-p20t es-p20r es-p20l" align="left">
                                                                                <table cellpadding="0" cellspacing="0">
                                                                                    <tbody>
                                                                                        <tr>
                                                                                            <td width="560" class="esd-container-frame" align="left">
                                                                                                <table cellpadding="0" cellspacing="0" width="100%" role="presentation">
                                                                                                    <tbody>
                                                                                                        <tr>
                                                                                                            <td align="left" class="esd-block-text">
                                                                                                                <p align="justify">
                                                                                                                    Un resultado de IMC igual o mayor a 25 es el inicio del sobrepeso también
                                                                                                                    llamado pre-obesidad, la antesala del Síndrome Metabólico.
                                                                                                                </p>
                                                                                                                &nbsp;
                                                                                                                <ul>
                                                                                                                    <li>
                                                                                                                        <p style="line-height: 130% !important" align="justify">
                                                                                                                            Ahora que conoces tu índice de masa corporal y la categoría en la que estás,
                                                                                                                            debes conocer:
                                                                                                                        </p>
                                                                                                                    </li>
                                                                                                                    <li>
                                                                                                                        <p style="line-height: 130% !important" align="justify">
                                                                                                                            Tu composición corporal (cuanto tienes de grasa, masa muscular, huesos y 10
                                                                                                                            indicadores más).
                                                                                                                        </p>
                                                                                                                    </li>
                                                                                                                    <li>
                                                                                                                        <p style="line-height: 130% !important" align="justify">
                                                                                                                            Cuál es tu índice de cintura talla y medidas de volumen.
                                                                                                                        </p>
                                                                                                                    </li>
                                                                                                                    <li>
                                                                                                                        <p style="line-height: 130% !important" align="justify">
                                                                                                                            Cómo están tus indicadores metabólicos de grasa (azúcar, colesterol y
                                                                                                                            triglicéridos). Tener un diagnóstico de tu estado de salud metabólico.
                                                                                                                        </p>
                                                                                                                    </li>
                                                                                                                    <li>
                                                                                                                        <p style="line-height: 130% !important" align="justify">
                                                                                                                            Saber cómo puedes perder peso y grasa sostenido para acelerar tu metabolismo.
                                                                                                                        </p>
                                                                                                                    </li>
                                                                                                                </ul>
                                                                                                                <p style="line-height: 130% !important" align="justify">
                                                                                                                    <b
                                                                                                                        >Si deseas conocer más sobre tu metabolismo, agenda tu cita para hacerte tu
                                                                                                                        evaluación personalizada y con los resultados podemos sugerirte 2 opciones:</b
                                                                                                                    >
                                                                                                                </p>
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                    </tbody>
                                                                                                </table>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="esd-structure es-p20t es-p20r es-p20l" align="left">
                                                                                <table cellpadding="0" cellspacing="0">
                                                                                    <tbody>
                                                                                        <tr>
                                                                                            <td width="560" class="esd-container-frame" align="left">
                                                                                                <table cellpadding="0" cellspacing="0" width="100%" role="presentation">
                                                                                                    <tbody>
                                                                                                        <tr>
                                                                                                            <td align="left" class="esd-block-text">
                                                                                                                <p>1. Realiza un <strong style="color: #002545">Metabograma</strong></p>
                                                                                                                <ul>
                                                                                                                    <li>
                                                                                                                        <p align="justify" style="  text-align: justify;">
                                                                                                                            Obtén un análisis exhaustivo y personalizado de tu metabolismo. Incluye
                                                                                                                            historial médico, composición corporal, antropometría y pruebas de laboratorio
                                                                                                                            avanzadas, todo para una visión completa de tu salud.
                                                                                                                        </p>
                                                                                                                    </li>
                                                                                                                </ul>
                                                                                                                <p>
                                                                                                                    2.Únete al
                                                                                                                    <strong style="color: #002545">Programa Aceleración Metabólica PAM</strong>
                                                                                                                </p>
                                                                                                                <ul>
                                                                                                                    <li>
                                                                                                                        <p align="justify">
                                                                                                                            Participa en un programa específico diseñado para acelerar tu metabolismo y
                                                                                                                            reducir grasa-peso de manera permanente y sostenible.
                                                                                                                        </p>
                                                                                                                    </li>
                                                                                                                </ul>
                                                                                                                <p><b>Agenda tu cita ahora:</b></p>
                                                                                                                <p>​</p>
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                    </tbody>
                                                                                                </table>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="esd-structure" align="left">
                                                                                <!--[if mso]><table width="600" cellpadding="0" cellspacing="0"><tr><td width="67" valign="top"><![endif]-->
                                                                                <table cellpadding="0" cellspacing="0" class="es-left" align="left">
                                                                                    <tbody>
                                                                                        <tr>
                                                                                            <td width="67" class="esd-container-frame" align="left">
                                                                                                <table cellpadding="0" cellspacing="0" width="100%" role="presentation">
                                                                                                    <tbody>
                                                                                                        <tr>
                                                                                                            <td align="right" class="esd-block-image" style="font-size: 0">
                                                                                                                <a target="_blank">
                                                                                                                    <img src="https://appweb.bclinik.com/img/img_correos/whatsapp.png" alt="" width="40" />
                                                                                                                </a>
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                    </tbody>
                                                                                                </table>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>

                                                                                <!--[if mso]></td><td width="533" valign="top"><![endif]-->
                                                                                <table cellpadding="0" cellspacing="0" class="es-right" align="right">
                                                                                    <tbody>
                                                                                        <tr>
                                                                                            <td width="533" class="esd-container-frame" align="left">
                                                                                                <table cellpadding="0" cellspacing="0" width="100%" role="presentation">
                                                                                                    <tbody>
                                                                                                        <tr>
                                                                                                            <td align="left" class="esd-block-text">
                                                                                                                <p style="color: #002545" align="left">
                                                                                                                    <b
                                                                                                                        >Whatsapp:
                                                                                                                        <a style="color: #1b85dd" target="_blank" href="https://wa.link/d8atu5"
                                                                                                                            >3324-3501</a
                                                                                                                        ></b
                                                                                                                    >
                                                                                                                </p>
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                    </tbody>
                                                                                                </table>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                                <!--[if mso]></td></tr></table><![endif]-->
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="esd-structure es-p20t es-p20r es-p20l" align="left">
                                                                                <table cellpadding="0" cellspacing="0">
                                                                                    <tbody>
                                                                                        <tr>
                                                                                            <td width="560" class="esd-container-frame" align="left">
                                                                                                <table cellpadding="0" cellspacing="0" width="100%" role="presentation">
                                                                                                    <tbody>
                                                                                                        <tr>
                                                                                                            <td align="left" class="esd-block-text">
                                                                                                                <p><b>Ubícanos en:</b></p>
                                                                                                                <ul>
                                                                                                                    <li style="color: #333333">
                                                                                                                        <p style="color: #333333">
                                                                                                                            <a target="_blank" style="text-decoration: none; color: #333333" href="https://www.google.com/search?q=bio+clinik+muxbal">
                                                                                                                        <stron>	Edificio Corporativo Muxbal &nbsp;- CAES</stron></a
                                                                                                                            >
                                                                                                                        </p>
                                                                                                                    </li>
                                                                                                                    <li style="color: #333333">
                                                                                                                        <p style="color: #333333">
                                                                                                                            <a target="_blank" style="text-decoration: none; color: #333333" 	href="https://www.google.com/search?q=bio+clinik+zona+10">

                                                                                                                                    <stron>Edificio 01010 - Zona 10</stron></a
                                                                                                                            >
                                                                                                                        </p>
                                                                                                                    </li>
                                                                                                                </ul>
                                                                                                                <p><b>​</b></p>
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                    </tbody>
                                                                                                </table>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="esd-structure es-p20t es-p20r es-p20l" align="left">
                                                                                <table cellpadding="0" cellspacing="0">
                                                                                    <tbody>
                                                                                        <tr>
                                                                                            <td width="560" class="esd-container-frame" align="left">
                                                                                                <table cellpadding="0" cellspacing="0" width="100%" role="presentation">
                                                                                                    <tbody>
                                                                                                        <tr>
                                                                                                            <td align="center" class="esd-block-image" style="font-size: 0">
                                                                                                                <a target="_blank">
                                                                                                                    <img
                                                                                                                        src="https://appweb.bclinik.com/img/img_correos/footer.png"
                                                                                                                        alt=""
                                                                                                                        width="560"
                                                                                                                        class="adapt-img"
                                                                                                                    />
                                                                                                                </a>
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                    </tbody>
                                                                                                </table>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </body>
                    </html>


';

        $mail->Subject = 'Calculadora IMC';
        $mail->isHTML(true);
        $mail->MsgHTML($body);

        // Enviar el correo
        if (!$mail->send()) {
            throw new \Exception('Error al enviar el correo: ' . $mail->ErrorInfo);
        }


            //  Log::info('Datos específicos recibidos del webhook:', $data);


            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            // Capturar cualquier excepción y registrarla
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
            Imc_Invitacion::create($dataToSave);


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
                        <html dir="ltr" lang="en"><head> <meta charset="UTF-8"> <meta name="viewport" content="width=device-width, initial-scale=1.0"> <title>Invitación</title> <link rel="preconnect" href="https://fonts.googleapis.com"> <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin=""> <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400&display=swap" rel="stylesheet">
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
                        </style> <!--[if mso]>
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
                        --></head> <body class="body">
                    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0">
                        <tbody><tr>
                            <td align="center">
                                <table width="600" cellpadding="0" cellspacing="0" border="0" style="color: #595959;">
                                    <tbody><tr>
                                        <td>
                                            <img src="https://appweb.bclinik.com/img/img_correos/header.png" alt="header" width="600" style="max-width: 100%;">
                                        </td>
                                    </tr>
                                    <tr esd-text="true" class="esd-text">
                                        <td align="right" style="padding: 10px;">
                                            Fecha: ' . date('Y-m-d') . '
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 10px;" esd-text="true" class="esd-text">
                                            <p>Hola ' . $nombre_invitado  . '</p>
                                            <p>Tu amigo ' . $nombre . ', nos ha solicitado que te enviemos esta información.</p>
                                            <p style="color:#11455d; text-align: center;">¿Quieres saber si tu peso es adecuado para tu edad?</p>
                                            <p style="color:#11455d; text-align: center;">¿Si tu metabolismo está lento?</p>
                                            <p style="color:#11455d; text-align: center;">¿Cuál es la causa principal por la que subes de peso?</p>
                                            <p style="color:#11455d; text-align: center;">¿Qué es lo que no te deja bajar de peso?</p>
                                        </td>
                                    </tr>
                                    <tr esd-text="true" class="esd-text">
                                        <td style="padding: 10px; text-align: center;">
                                            Averígualo ingresa a <a href="https://bclinik.com/imc/">www.bclinik.com/imc</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="center" style="padding: 20px 0;">
                                            <img src="https://appweb.bclinik.com/img/img_correos/imc.png" alt="imc" width="540" style="max-width: 90%;">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 10px;" esd-text="true" class="esd-text">
                                            <ul style="list-style-type: none; padding: 0;">
                                                <li style="padding: 5px 0;"><em><strong style="color:#F1C232;">Entre 25 a 29.99</strong> es sobrepeso también llamado <span style="color:#11455d;">pre obesidad.</span></em></li>
                                                <li style="padding: 5px 0;"><em><strong style="color:#FF9900;">Entre 30 a 34.99</strong> estás a tiempo de retroceder categorías y llegar a lo normal.</em></li>
                                                <li style="padding: 5px 0;"><em><strong style="color:#FF0000;">Entre 35 a 39.99</strong> estás a tiempo de retroceder categorías y evitar el Síndrome Metabólico.</em></li>
                                                <li style="padding: 5px 0;"><em><strong style="color:#990000;">Mayor de 40</strong> no solo puedes retroceder categorías, además, controlas el Síndrome Metabólico que haya aparecido: <br>(Obesidad, Diabetes, Hipertensión, Colesterol y Triglicéridos altos).</em></li>
                                            </ul>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 10px;" esd-text="true" class="esd-text">
                                            <p><strong>Ubícanos en:</strong></p>
                                            <p><a href="https://www.google.com/search?q=bio+clinik+muxbal&rlz=1C1UEAD_enGT1090GT1090&oq=bio+clinik+muxbal&gs_lcrp=EgZjaHJvbWUyBggAEEUYOTIJCAEQIRgKGKABMgkIAhAhGAoYoAHSAQgyMzY5ajBqN6gCALACAA&sourceid=chrome&ie=UTF-8">Edificio Corporativo Muxbal - CAES</a></p>
                                            <p><a href="https://www.google.com/search?q=bio+clinik+zona+10&rlz=1C1UEAD_enGT1090GT1090&oq=bio+clinik+zona+10&gs_lcrp=EgZjaHJvbWUyBggAEEUYOTIJCAEQIRgKGKABMgkIAhAhGAoYoAHSAQgzNTU0ajBqN6gCALACAA&sourceid=chrome&ie=UTF-8">Edificio 01010 - Zona 10</a></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <img src="https://appweb.bclinik.com/img/img_correos/footer.png" alt="footer" width="600" style="max-width: 100%;">
                                        </td>
                                    </tr>
                                </tbody></table>
                            </td>
                        </tr>
                    </tbody></table>


                    </body></html>
                ';
            $mail->Subject = 'Invitación Calculadora IMC';
            $mail->isHTML(true);
            $mail->MsgHTML($body);

            // Enviar el correo
            if (!$mail->send()) {
                throw new \Exception('Error al enviar el correo: ' . $mail->ErrorInfo);
            }



            // Log::info('Datos específicos recibidos del webhook:', $data);

        } catch (\Exception $e) {
            Log::error('Error al procesar datos del webhook: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Error al procesar los datos del webhook'], 500);
        }
    }
}
