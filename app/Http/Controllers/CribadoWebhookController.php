<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Cribado_Form_Cotizacion;
use PHPMailer\PHPMailer\PHPMailer;
use PDF;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;

class CribadoWebhookController extends Controller
{
    public function handleCribadoCotizacion(Request $request)
    {


        try {
            $nombre_de_la_empresa = $request->input('Nombre_de_la_empresa', '');
            $direccion = $request->input('Dirección', '');
            $cantidad_de_colaboradores = $request->input('Cantidad_de_colaboradores_en_total', '');
            $nombre_de_quien_solicita = $request->input('Nombre_de_quien_solicita', '');
            $puesto_en_la_empresa = $request->input('Puesto_en_la_empresa', '');
            $telefono_directo_movil = $request->input('Teléfono_directo_–_móvil', '');
            $email = $request->input('Email', '');
            $date = $request->input('Date', '');
            $time = $request->input('Time', '');
            $page_url = $request->input('Page_URL', 'null');
            $user_agent = $request->input('User_Agent', 'null');
            $remote_ip = $request->input('Remote_IP', 'null');
            $powered_by = $request->input('Powered_by', 'null');
            $form_id = $request->input('form_id', 'null');
            $form_name = $request->input('form_name', 'null');


            $dataToInsert = [
                'nombre_de_la_empresa' => $nombre_de_la_empresa,
                'direccion' => $direccion,
                'cantidad_de_colaboradores_en_total' => $cantidad_de_colaboradores,
                'nombre_de_quien_solicita' => $nombre_de_quien_solicita,
                'puesto_en_la_empresa' => $puesto_en_la_empresa,
                'telefono_directo_movil' => $telefono_directo_movil,
                'email' => $email,
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'page_url' => $page_url,
                'user_agent' => $user_agent,
                'remote_ip' => $remote_ip,
                'powered_by' => $powered_by,
                'form_id' => $form_id,
                'form_name' => $form_name,
            ];

            $newCribado = Cribado_Form_Cotizacion::create($dataToInsert);
            $encryptedId = Crypt::encryptString($newCribado->id);
            $link = url('/webhook/cribado_cotizacion_download') . '?id=' . urlencode($encryptedId);

            $data = [
                'date' => date('d-m-Y'),
                'nombre_de_quien_solicita' => $nombre_de_quien_solicita,
                'nombre_de_la_empresa' => $nombre_de_la_empresa,
                'puesto_en_la_empresa' => $puesto_en_la_empresa,
            ];
            $pdf = PDF::loadView('pdf.cribado_cotizacion', $data);
            $pdfContent = $pdf->output();


            $mail = new PHPMailer(true);
            $mail->CharSet = 'UTF-8';
            $mail->isSMTP();
            $mail->Host = 'bclinik.com';
            $mail->Port = 465;
            $mail->SMTPSecure = 'ssl';
            $mail->SMTPAuth = true;
            $mail->Username = 'noreply@bclinik.com';
            $mail->Password = 'Password$001';
            $mail->setFrom('noreply@bclinik.com', 'Respuesta Cotización Cribado');
            $mail->addReplyTo('noreply@bclinik.com', 'Respuesta Cotización Cribado');
            $mail->addAddress($email);
            $body = '

                        <html dir="ltr" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office">
                        <head>
                            <meta charset="UTF-8">
                            <meta content="width=device-width, initial-scale=1" name="viewport">
                            <meta name="x-apple-disable-message-reformatting">
                            <meta content="IE=edge" http-equiv="X-UA-Compatible">
                            <meta content="telephone=no" name="format-detection">
                            <title></title>
                            <!--[if (mso 16)]>
                            <style type="text/css">
                            a {text-decoration: none;}
                            </style>
                            <![endif]-->
                            <!--[if gte mso 9]><style>sup { font-size: 100% !important; }</style><![endif]-->
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
                        -->
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
                            <div dir="ltr" class="es-wrapper-color">
                            <!--[if gte mso 9]>
                                    <v:background xmlns:v="urn:schemas-microsoft-com:vml" fill="t">
                                        <v:fill type="tile" color="#f6f6f6"></v:fill>
                                    </v:background>
                                <![endif]-->
                            <table cellspacing="0" cellpadding="0" width="100%" class="es-wrapper">
                                <tbody>
                                <tr>
                                    <td valign="top" class="esd-email-paddings">
                                    <table align="center" cellspacing="0" cellpadding="0" class="esd-header-popover es-header">
                                        <tbody>
                                        <tr>
                                            <td align="center" bgcolor="#F6F6F6" class="esd-stripe" style="background-color:#F6F6F6">
                                            <table cellspacing="0" cellpadding="0" bgcolor="#ffffff" align="center" width="600" class="es-header-body">
                                                <tbody>
                                                <tr>
                                                    <td align="left" class="esd-structure es-p20t es-p20r es-p20l">
                                                    <table cellspacing="0" width="100%" cellpadding="0">
                                                        <tbody>
                                                        <tr>
                                                            <td align="left" width="560" class="esd-container-frame">
                                                            <table cellspacing="0" width="100%" role="presentation" cellpadding="0">
                                                                <tbody>
                                                                <tr>
                                                                    <td align="center" class="esd-block-image es-infoblock" style="font-size:0">
                                                                    <a target="_blank">
                                                                        <img src="https://appweb.bclinik.com/img/img_correos/header.png" alt="" width="560" class="adapt-img">
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
                                                    <td align="left" class="esd-structure es-p20t es-p20r es-p30l">
                                                    <!--[if mso]><table width="555" cellpadding="0" cellspacing="0"><tr><td width="361" valign="top"><![endif]-->
                                                    <table cellpadding="0" cellspacing="0" align="left" class="es-left">
                                                        <tbody>
                                                        <tr>
                                                            <td align="left" width="361" class="esd-container-frame es-m-p20b">
                                                            <table role="presentation" cellpadding="0" cellspacing="0" width="100%">
                                                                 <tbody>
                                                                                                    <tr>
                                                                                                    <td align="left" class="esd-block-text es-infoblock">
                                                                                                        <p style="color:#0F4761">
                                                                                                            Hola
                                                                                                            <span style="color:#333333">
                                                                                                            '.$nombre_de_quien_solicita.'
                                                                                                            </span>
                                                                                                        </p>
                                                                                                        <p style="color:#333333">
                                                                                                            &nbsp; &nbsp; &nbsp; &nbsp; '.$nombre_de_la_empresa.' &nbsp;
                                                                                                        </p>
                                                                                                        <p style="color:#333333">
                                                                                                            &nbsp; &nbsp; &nbsp; &nbsp; '.$puesto_en_la_empresa.'
                                                                                                        </p>
                                                                                                    </td>
                                                                                                    </tr>
                                                                                                </tbody>
                                                            </table>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                    <!--[if mso]></td><td width="20"></td><td width="169" valign="top"><![endif]-->
                                                    <table cellpadding="0" cellspacing="0" align="right" class="es-right">
                                                        <tbody>
                                                        <tr>
                                                            <td width="169" align="left" class="esd-container-frame">
                                                            <table width="100%" role="presentation" cellpadding="0" cellspacing="0">
                                                                <tbody>
                                                                <tr>
                                                                    <td align="left" class="esd-block-text es-infoblock">
                                                                    <p>
                                                                        <span style="color:#0F4761">
                                                                        &nbsp; Fecha
                                                                        </span>
                                                                        : '.date('Y/m/d').'
                                                                    </p>
                                                                    <p style="line-height: 150% !important; color: #002545" align="right"><a href="' . $link . '" style="display: inline-block; padding: 1px 15px; margin-left: 10px; background-color: #007bff; color: #fff; text-decoration: none; border-radius: 5px;">Descargar</a></p>
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
                                                    <td align="left" class="esd-structure es-p20t es-p20r es-p30l">
                                                    <table cellspacing="0" align="right" width="100%" cellpadding="0" class="es-right">
                                                        <tbody>
                                                        <tr>
                                                            <td width="550" align="left" class="esd-container-frame">
                                                            <table role="presentation" cellpadding="0" cellspacing="0" width="100%">
                                                                <tbody>
                                                                <tr>
                                                                    <td align="left" class="esd-block-text es-infoblock">
                                                                    <p style="color:#000000">
                                                                        Bienvenido al Centro de Investigación Metabólica + Bio Clinik. celebramos tu interés en nuestro:
                                                                    </p>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td align="center" class="esd-block-text es-infoblock">
                                                                    <h2 style="color:#0f4761">
                                                                        Cribado Empresarial
                                                                    </h2>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td align="justify" class="esd-block-text es-infoblock es-text-3167">
                                                                    <p class="es-text-mobile-size-14" style="color:#020202;font-size:14px;line-height:150%">
                                                                        Formar parte de nuestras
                                                                        <i style="color:#0f4761">
                                                                        Pruebas Metabólicas Digitales
                                                                        </i>
                                                                        es un gran paso hacia el logro del bienestar y la consolidación de equipos de trabajo cada vez más productivos. &nbsp;Este programa, ofrecido por el
                                                                        <i style="color:#0f4761">
                                                                        Centro de Investigación Metabólica +Bio Clinik
                                                                        </i>
                                                                        , es una herramienta para detectar la prevalencia, (el número total de personas en un grupo específico que tienen o tuvieron una o varias de las enfermedades que conforman el Síndrome Metabólico) entre lo​s colaboradores de su empresa.
                                                                    </p>
                                                                    &nbsp;
                                                                    <p class="es-text-mobile-size-14" style="color:#0F4761;font-size:14px;line-height:150%">
                                                                        <b>
                                                                        ¿Específicamente qué es el Cribado Empresarial?
                                                                        </b>
                                                                    </p>
                                                                    <p class="es-text-mobile-size-14" style="color:#000000;font-size:14px;line-height:150%">
                                                                        Consiste en la realización de pruebas diagnósticas a personas, en principio sanas, para distinguir aquellas que posiblemente estén enfermas de las que posiblemente no lo estén (tamizaje).
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
                                                    <td align="left" class="esd-structure es-p20t es-p20r es-p20l">
                                                    <table width="100%" cellpadding="0" cellspacing="0">
                                                        <tbody>
                                                        <tr>
                                                            <td align="left" width="560" class="esd-container-frame">
                                                            <table cellspacing="0" width="100%" role="presentation" cellpadding="0">
                                                                <tbody>
                                                                <tr>
                                                                    <td align="center" class="esd-block-image es-infoblock" style="font-size: 0">
                                                                    <a target="_blank">
                                                                        <img alt="" width="400" src="https://appweb.bclinik.com/img/img_correos/cribado01.png" class="adapt-img">
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
                                                    <td align="left" class="esd-structure es-p20t es-p20r es-p30l">
                                                    <table cellpadding="0" cellspacing="0" align="right" width="100%" class="es-right">
                                                        <tbody>
                                                        <tr>
                                                            <td align="left" width="550" class="esd-container-frame">
                                                            <table width="100%" role="presentation" cellpadding="0" cellspacing="0">
                                                                <tbody>
                                                                <tr>
                                                                    <td align="justify" class="esd-block-text es-infoblock es-text-1684">
                                                                    <p class="es-text-mobile-size-14" style="line-height:150%;color:#000000;font-size:14px">
                                                                        El objetivo es la detección temprana y oportuna de los riesgos medios y altos en que se encuentran los colaboradores de desarrollar en todo o en parte el Síndrome Metabólico, síndrome que incluye Sobrepeso, Obesidad, Prediabetes, Diabetes y/ o dislipidemia (Colesterol y/o Triglicéridos anormales).
                                                                    </p>
                                                                    <p class="es-text-mobile-size-14" style="color:#000000;font-size:14px;line-height:150%">
                                                                        ​
                                                                    </p>
                                                                    <p class="es-text-mobile-size-14" style="font-size:14px;line-height:150%;color:#0f4761">
                                                                        <b>
                                                                        Beneficios del Cribado Empresarial:
                                                                        </b>
                                                                    </p>
                                                                    <p class="es-text-mobile-size-14" style="color:#000000;font-size:14px;line-height:150%">
                                                                        Formulamos un scanner, una representación visual del estado de salud de sus colaboradores, podrá conocer el estado de salud metabólico de sus equipos de trabajo y tomar decisiones para:
                                                                    </p>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td align="justify" class="esd-block-text es-infoblock es-text-9184">
                                                                    <ul class="es-text-mobile-size-14">
                                                                        <li style="line-height:150%;color:#000000;font-size:14px">
                                                                        <p style="line-height:150%;color:#000000;font-size:14px">
                                                                            Reducción del ausentismo: identificar problemas de salud antes de que se manifiesten plenamente, ayuda a prevenir bajas laborales.
                                                                        </p>
                                                                        </li>
                                                                        <li style="color:#000000;font-size:14px;line-height:150%">
                                                                        <p style="line-height:150%;color:#000000;font-size:14px">
                                                                            &nbsp;Prevención de accidentes laborales: al conocer el estado metabólico de los colaboradores, se pueden evitar situaciones de riesgo.
                                                                        </p>
                                                                        </li>
                                                                        <li style="color:#000000;font-size:14px;line-height:150%">
                                                                        <p style="line-height:150%;color:#000000;font-size:14px">
                                                                            &nbsp;Mejora del entorno laboral: fomentar la salud y el bienestar contribuye a un ambiente de trabajo positivo.
                                                                        </p>
                                                                        </li>
                                                                        <li style="color:#000000;font-size:14px;line-height:150%">
                                                                        <p style="color:#000000;font-size:14px;line-height:150%">
                                                                            &nbsp;Mayor eficiencia del equipo: colaboradores saludables, mayor productividad.
                                                                        </p>
                                                                        </li>
                                                                        <li style="color:#000000;font-size:14px;line-height:150%">
                                                                        <p style="line-height:150%;color:#000000;font-size:14px">
                                                                            &nbsp;Cultura de bienestar: promover la calidad de vida laboral y el autocuidado.
                                                                        </p>
                                                                        </li>
                                                                    </ul>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td align="justify" class="esd-block-text es-infoblock es-text-7382">
                                                                    <p class="es-text-mobile-size-14" style="font-size:14px;line-height:150%;color:#0f4761">
                                                                        <strong>
                                                                        Importancia de la encuesta en línea a la que nos referimos:
                                                                        </strong>
                                                                    </p>
                                                                    &nbsp;
                                                                    <p class="es-text-mobile-size-14" style="color:#000000;font-size:14px;line-height:150%">
                                                                        La información que solicitamos a cada participante en la encuesta es la base de este proceso. Para garantizar la equidad y el respeto hacia cada uno de ellos,
                                                                        <em>
                                                                        <span style="color:#0f4761">
                                                                            la comprensión y consentimiento voluntario
                                                                        </span>
                                                                        </em>
                                                                        , son fundamentales.
                                                                    </p>
                                                                    &nbsp;
                                                                    <p class="es-text-mobile-size-14" style="color:#000000;font-size:14px;line-height:150%">
                                                                        A continuación, detallamos algunos puntos importantes:
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
                                                    <td align="left" class="esd-structure es-p10r es-p10l">
                                                    <!--[if mso]><table width="590" cellpadding="0" cellspacing="0"><tr><td width="290" valign="top"><![endif]-->
                                                    <table cellspacing="0" align="left" cellpadding="0" class="es-left">
                                                        <tbody>
                                                        <tr>
                                                            <td width="290" align="left" class="esd-container-frame es-m-p20b">
                                                            <table role="presentation" cellpadding="0" cellspacing="0" width="100%">
                                                                <tbody>
                                                                <tr>
                                                                    <td align="right" class="esd-block-image es-infoblock" style="font-size:0">
                                                                    <a target="_blank">
                                                                        <img src="https://appweb.bclinik.com/img/img_correos/concentimiento.png" alt="" width="290">
                                                                    </a>
                                                                    </td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                    <!--[if mso]></td><td width="290" valign="top"><![endif]-->
                                                    <table cellspacing="0" align="right" cellpadding="0" class="es-right">
                                                        <tbody>
                                                        <tr>
                                                            <td width="290" align="left" class="esd-container-frame">
                                                            <table cellpadding="0" cellspacing="0" width="100%" role="presentation">
                                                                <tbody>
                                                                <tr>
                                                                    <td align="justify" class="esd-block-text es-infoblock es-text-7744">
                                                                    <ul class="es-text-mobile-size-14">
                                                                        <li style="color: #010101; font-size: 14px">
                                                                        <p style="color:#010101;font-size:14px">
                                                                            <span style="color:#0f4761">
                                                                            Consentimiento informado:
                                                                            </span>
                                                                            Cada colaborador debe aceptar de manera explícita el
                                                                            <span style="color:#0f4761">
                                                                            “consentimiento informado
                                                                            </span>
                                                                            ” para participar, lo que asegura su comprensión y voluntariedad sin ninguna coacción.
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
                                                    <!--[if mso]></td></tr></table><![endif]-->
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td align="left" class="esd-structure es-p20t es-p20r es-p30l">
                                                    <table cellpadding="0" cellspacing="0" align="right" width="100%" class="es-right">
                                                        <tbody>
                                                        <tr>
                                                            <td align="left" width="550" class="esd-container-frame">
                                                            <table width="100%" role="presentation" cellpadding="0" cellspacing="0">
                                                                <tbody>
                                                                <tr>
                                                                    <td align="justify" class="esd-block-text es-infoblock es-text-8053 es-p30l">
                                                                    <ul class="es-text-mobile-size-14">
                                                                        <li style="color:#000000;font-size:14px;line-height:150%">
                                                                        <p style="color:#000000;font-size:14px;line-height:150%">
                                                                            <span style="color:#0f4761">
                                                                            Participación voluntaria
                                                                            </span>
                                                                            : Su participación es completamente voluntaria y puede ser interrumpida en cualquier momento sin ningún perjuicio.
                                                                        </p>
                                                                        </li>
                                                                        <li style="color:#000000;font-size:14px;line-height:150%">
                                                                        <p style="line-height:150%;color:#000000;font-size:14px">
                                                                            &nbsp;
                                                                            <span style="color:#0f4761">
                                                                            Confidencialidad:
                                                                            </span>
                                                                            La identidad de cada colaborador será tratada de manera anónima. La información recopilada será analizada de forma conjunta y utilizada para elaborar el informe estadístico del censo metabólico.
                                                                        </p>
                                                                        </li>
                                                                        <li style="color:#000000;font-size:14px;line-height:150%">
                                                                        <p style="color:#000000;font-size:14px;line-height:150%">
                                                                            <span style="color:#0f4761">
                                                                            Acceso a resultados:
                                                                            </span>
                                                                            La información será conservada por cinco años contados desde la entrega de los resultados a la cual podrá también acceder el grupo de investigación. Cada colaborador recibirá su informe individual. Se enviará a la empresa el informe colectivo con una explicación completa de los resultados estadísticos.
                                                                        </p>
                                                                        </li>
                                                                        <li style="color:#000000;font-size:14px;line-height:150%">
                                                                        <p style="font-size:14px;line-height:150%;color:#000000">
                                                                            <span style="color:#0f4761">
                                                                            Tiempo estimado:
                                                                            </span>
                                                                            La encuesta le tomará aproximadamente 10 minutos.
                                                                        </p>
                                                                        </li>
                                                                    </ul>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td align="justify" class="esd-block-text es-infoblock es-text-6744">
                                                                    <p class="es-text-mobile-size-14" style="color:#000000;font-size:14px">
                                                                        En el Centro de Investigación Metabólica +Bio Clinik estamos comprometidos con ayudar a mejorar la salud y bienestar de todos los colaboradores de su empresa. Si tiene alguna pregunta o necesita más información, no dude en contactarnos.
                                                                    </p>
                                                                    <p class="es-text-mobile-size-14" style="color:#000000;font-size:14px">
                                                                        ​
                                                                    </p>
                                                                    <p class="es-text-mobile-size-14" style="color:#000000;font-size:14px">
                                                                        Saludos cordiales,
                                                                    </p>
                                                                    <p class="es-text-mobile-size-14" style="color:#000000;font-size:14px">
                                                                        ​
                                                                    </p>
                                                                    <p class="es-text-mobile-size-14" style="color:#0f4761;font-size:14px">
                                                                        <em>
                                                                        Centro de Investigación Metabólica +Bio Clinik
                                                                        </em>
                                                                    </p>
                                                                    <p class="es-text-mobile-size-14" style="color:#000000;font-size:14px">
                                                                        <em>
                                                                        ​
                                                                        </em>
                                                                    </p>
                                                                    <p class="es-text-mobile-size-14" style="color:#000000;font-size:14px">
                                                                        <b>
                                                                        Agenda tu cita ahora:
                                                                        </b>
                                                                    </p>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td align="left" class="esd-block-text es-infoblock">
                                                                    <p style="text-align:center;color:#0f4761">
                                                                        <b>
                                                                        Heydy Cachutt
                                                                        </b>
                                                                    </p>
                                                                    <p style="text-align:center;color:#595959">
                                                                        <strong>
                                                                        Alianzas
                                                                        </strong>
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
                                                    <td align="left" class="esd-structure es-p20t es-p20r es-p20l">
                                                    <table width="100%" cellpadding="0" cellspacing="0">
                                                        <tbody>
                                                        <tr>
                                                            <td align="left" width="560" class="esd-container-frame">
                                                            <table width="100%" role="presentation" cellpadding="0" cellspacing="0">
                                                                <tbody>
                                                                <tr>
                                                                    <td align="center" class="esd-block-image es-infoblock" style="font-size:0">
                                                                    <a target="_blank">
                                                                        <img width="300" src="https://appweb.bclinik.com/img/img_correos/cribado02.png" alt="" class="adapt-img">
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
                                                    <td align="left" class="esd-structure es-p20t es-p20r es-p30l">
                                                    <!--[if mso]><table width="560" cellpadding="0" cellspacing="0"><tr><td width="265" valign="top"><![endif]-->
                                                    <table cellpadding="0" cellspacing="0" align="left" class="es-left">
                                                        <tbody>
                                                        <tr>
                                                            <td width="265" align="left" class="esd-container-frame es-m-p20b">
                                                            <table role="presentation" cellpadding="0" cellspacing="0" width="100%">
                                                                <tbody>
                                                                <tr>
                                                                    <td esd-tmp-menu-padding="10|10" esd-tmp-menu-font-size="14px" esd-tmp-menu-size="width|20" class="esd-block-menu es-infoblock">
                                                                    <table cellspacing="0" width="100%" cellpadding="0" class="es-menu">
                                                                        <tbody>
                                                                        <tr>
                                                                            <td align="left" valign="top" width="100%" class="es-p10t es-p10b es-p5r es-p5l" style="padding-top:10px">
                                                                            <a target="_blank" href="https://wa.link/wcqioe" style="font-size:14px;color:#0f4761;font-weight:bold; text-decoration:none;">
                                                                                <img width="35" src="https://appweb.bclinik.com/img/img_correos/whatsapp.png" alt="+ 000 123 456 78" title="Whatsapp: 4785-8946" align="absmiddle" class="es-p5r">
                                                                                Whatsapp:
                                                                                <span style="color:#4282B6">
                                                                                4785-8946
                                                                                </span>
                                                                            </a>
                                                                            </td>
                                                                        </tr>
                                                                        </tbody>
                                                                    </table>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td esd-tmp-menu-font-size="14px" esd-tmp-menu-size="width|20" esd-tmp-menu-padding="10|10" class="esd-block-menu es-infoblock">
                                                                    <table width="100%" cellpadding="0" cellspacing="0" class="es-menu">
                                                                        <tbody>
                                                                        <tr>
                                                                            <td width="100%" align="left" valign="top" class="es-p10t es-p10b es-p5r es-p5l" style="padding-top:10px">
                                                                            <a target="_blank" href="mailto:heydycachutt@bclinik.com" style="font-size:14px;color:#333333;font-weight:bold; text-decoration:none;">
                                                                                <img alt="ariana_rivera@email" title="heydycachutt@bclinik.com" align="absmiddle" width="35" src="https://appweb.bclinik.com/img/img_correos/email.png" class="es-p5r">
                                                                                heydycachutt@bclinik.com
                                                                            </a>
                                                                            </td>
                                                                        </tr>
                                                                        </tbody>
                                                                    </table>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td esd-tmp-menu-padding="10|10" esd-tmp-menu-font-size="14px" esd-tmp-menu-size="width|20" class="esd-block-menu es-infoblock">
                                                                    <table cellpadding="0" cellspacing="0" class="es-menu es-table-not-adapt">
                                                                        <tbody>
                                                                        <tr>
                                                                            <td align="left" valign="top" width="100.00%" class="es-p10t es-p10b es-p5r es-p5l" style="padding-top:10px">
                                                                            <a href="www.bclinik.com" target="_blank" style="color:#333333;font-weight:bold;font-size:14px; text-decoration:none;">
                                                                                <img alt="non-profit.com" title="www.bclinik.com  " align="absmiddle" width="35" src="https://appweb.bclinik.com/img/img_correos/link.png" class="es-p5r">
                                                                                www.bclinik.com
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
                                                    <!--[if mso]></td><td width="20"></td><td width="265" valign="top"><![endif]-->
                                                    <table cellspacing="0" align="right" cellpadding="0" class="es-right">
                                                        <tbody>
                                                        <tr>
                                                            <td align="left" width="265" class="esd-container-frame">
                                                            <table cellspacing="0" width="100%" role="presentation" cellpadding="0">
                                                                <tbody>
                                                                <tr>
                                                                    <td align="center" class="esd-block-image es-infoblock" style="font-size:0">
                                                                    <a target="_blank">
                                                                        <img width="147" src="https://appweb.bclinik.com/img/img_correos/qr.jpg" alt="">
                                                                    </a>
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
                                                    <td align="left" class="esd-structure es-p20t es-p20r es-p20l">
                                                    <table cellspacing="0" width="100%" cellpadding="0">
                                                        <tbody>
                                                        <tr>
                                                            <td width="560" align="left" class="esd-container-frame">
                                                            <table width="100%" role="presentation" cellpadding="0" cellspacing="0">
                                                                <tbody>
                                                                <tr>
                                                                    <td align="left" class="esd-block-text es-infoblock es-text-4119">
                                                                    <p class="es-text-mobile-size-14" style="color:#333333;font-size:14px">
                                                                        <b>
                                                                        Ubícanos en:&nbsp;
                                                                        </b>
                                                                    </p>
                                                                    <p class="es-text-mobile-size-14" style="color:#333333;font-size:14px">
                                                                    </p>
                                                                    <p class="es-text-mobile-size-14" style="color:#333333;font-size:14px">
                                                                        <a target="_blank" href="https://www.google.com/search?q=%2BBio+Clinik+Estaci%C3%B3n+Metab%C3%B3lica+01010&sca_esv=37ce62a78f8cfe72&sca_upv=1&rlz=1C1ALOY_esGT1034GT1034&sxsrf=ADLYWIIB9swwJP45oF1pi6IacWn-cdbA2Q%3A1721931625231&ei=aZeiZpniDcrPwbkPmMfk0Ak&ved=0ahUKEwjZuuWW58KHAxXKZzABHZgjGZoQ4dUDCA8&uact=5&oq=%2BBio+Clinik+Estaci%C3%B3n+Metab%C3%B3lica+01010&gs_lp=Egxnd3Mtd2l6LXNlcnAiJytCaW8gQ2xpbmlrIEVzdGFjacOzbiBNZXRhYsOzbGljYSAwMTAxMEj-BVAAWABwAHgBkAEAmAFYoAFYqgEBMbgBA8gBAPgBAvgBAZgCAKACAJgDAJIHAKAHLQ&sclient=gws-wiz-serp" style="color:#333333;text-decoration:none;font-size:14px">
                                                                        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Edificio 01010 - Zona 10
                                                                        </a>
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
                                                    <td align="left" class="esd-structure es-p20t es-p20r es-p20l">
                                                    <table cellpadding="0" cellspacing="0" width="100%">
                                                        <tbody>
                                                        <tr>
                                                            <td width="560" align="left" class="esd-container-frame">
                                                            <table cellpadding="0" cellspacing="0" width="100%" role="presentation">
                                                                <tbody>
                                                                <tr>
                                                                    <td align="center" class="esd-block-image es-infoblock" style="font-size:0">
                                                                    <a target="_blank">
                                                                        <img src="https://appweb.bclinik.com/img/img_correos/footer.png" alt="" width="560" class="adapt-img">
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


            $mail->Subject = 'Respuesta Cotización Cribado';
            $mail->isHTML(true);
            $mail->MsgHTML($body);

            $mail->Body = $body;
            $mail->addStringAttachment($pdfContent, 'Respuesta_Cotización_Cribado.pdf');
            if (!$mail->send()) {
                log::info('Cribado Cotizacion', $mail->ErrorInfo);

                throw new \Exception('Error al enviar el correo: ' . $mail->ErrorInfo);
            }

            return response()->json(['success' => true], 200);







        } catch (\Exception $e) {
            Log::error('Error al procesar la solicitud: ' . $e->getMessage());
            return response()->json(['message' => 'Error al procesar la solicitud'], 500);
        }
    }
}
