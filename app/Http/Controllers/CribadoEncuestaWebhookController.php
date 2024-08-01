<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\EncuestaCribado;
use PHPMailer\PHPMailer\PHPMailer;
use PDF;
use PhpParser\Node\Expr\FuncCall;
use Illuminate\Support\Facades\Crypt;

class CribadoEncuestaWebhookController extends Controller
{

    public function pdf(Request $request){
        $encryptedId = $request->query('id');
        $decryptedId = Crypt::decryptString($encryptedId);
        $datos = EncuestaCribado::findOrFail($decryptedId);

        echo $datos->nombre;
    }
    public function handleCribadoEncuesta(Request $request)
    {

        // Captura de datos
        // Registrar los datos para inspección posterior
        // Log::info('Datos de la solicitud:', $request->all());

            // return response()->json($request->all(), 500);

        try {

            
            

             // Extraer los datos de los campos del formulario
             $nombre = $request->input('fields.name.value');
             $apellido = $request->input('fields.apellido.value');
             $edad = $request->input('fields.edad.value');
             $telefono_personal = $request->input('fields.telefono_personal.value');
             $correo = $request->input('fields.email.value');
             $empresa = $request->input('fields.empresa.value');
             $sede = $request->input('fields.sede.value');
             $genero = $request->input('fields.genero.value');
             $peso_en_libras = $request->input('fields.peso_en_libras.value');
             $altura_en_cms = $request->input('fields.altura_en_cms.value');
             $cintura_en_cms = $request->input('fields.cintura_en_cms.value');
 
             $agua_diaria = $request->input('fields.agua_diaria.value');
             $horarios_refacciones_comidas = $request->input('fields.horarios_refacciones_comidas.value');
             $porciones_pequenas = $request->input('fields.porciones_pequenas.value');
             $separar_combinar_alimentos = $request->input('fields.separar_combinar_alimentos.value');
             $rutina_ejercicio = $request->input('fields.rutina_ejercicio.value');
             $vivir_saludable = $request->input('fields.vivir_saludable.value');
 
             $insulina_alta = $request->input('fields.insulina_alta.value');
             $resistencia_insulina = $request->input('fields.resistencia_insulina.value');
             $elevaciones_azucar_embarazo = $request->input('fields.elevaciones_azucar_embarazo.value');
             $sindrome_ovarios_poliquistico = $request->input('fields.sindrome_ovarios_poliquistico.value');


 


             $sobrepeso = $request->input('fields.sobrepeso.value');
             $diabetes_embarazo_hijo = $request->input('fields.diabetes_embarazo_hijo.value');
             $ejercicio_regular = $request->input('fields.ejercicio_regular.value');
             $ovario_poliquistico = $request->input('fields.ovario_poliquistico.value');
 
             $padre = $request->input('fields.padre.value');
             $madre = $request->input('fields.madre.value');
             $hermanos = $request->input('fields.hermanos.value');
             $tios_paternos = $request->input('fields.tios_paternos.value');
             $tios_maternos = $request->input('fields.tios_maternos.value');
             $abuelos_maternos = $request->input('fields.abuelos_maternos.value');
             $abuelos_paternos = $request->input('fields.abuelos_paternos.value');

            $peso_r = ($peso_en_libras / 2.20462);
            $talla = ($altura_en_cms / 100);
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

            function asignarValor($respuesta) {
                switch(trim($respuesta)) {
                    case 'Si':
                        return 2;
                    case 'A veces':
                        return 1;
                    case 'No':
                        return 0;
                    default:
                        return 0; // Por defecto, 0 si la respuesta no coincide
                }
            }

            $sumatoria = asignarValor($agua_diaria) + asignarValor($horarios_refacciones_comidas) + asignarValor($porciones_pequenas) + 
            asignarValor($separar_combinar_alimentos) + asignarValor($rutina_ejercicio) + asignarValor($vivir_saludable);

            // Determinar el nivel de riesgo
            if ($sumatoria <= 5) {
                $nivel = '<p style="color: red;font-size: 2em;font-weight: bold;">Alto</p>';
            } elseif ($sumatoria >= 6 && $sumatoria <= 10) {
                $nivel = '<p style="color: #d7d436;font-size: 2em;font-weight: bold;">Medio</p>';
                
            } else {
                $nivel = '<p style="color: #76b82a;font-size: 2em;font-weight: bold;">Bajo</p>';
            }
            

            if ($result < 25) {
                $habitos_costumbre_riesgos = '<p style="color: #76b82a;font-size: 2em;font-weight: bold;">Bajo</p>
                                                <p>&nbsp;¡Tienes un Estilo de Vida Saludable!</p>
                                                <p>&nbsp;Tienes el metabolismo acelerado<br type="_moz"></p><i></i>';
            } elseif ($result >= 25 && $result <= 29) {
                $habitos_costumbre_riesgos = '<p style="color: #d7d436;font-size: 2em;font-weight: bold;">Medio<br type="_moz"></p>
                            <p>¡Tu Estilo de Vida puede mejorar!<br type="_moz"></p>
                            <p>Tienes el metabolismo irregular<br type="_moz"></p>';
            } elseif ($result >= 30 && $result <= 39) {
                $habitos_costumbre_riesgos = '<p style="color: red;font-size: 2em;font-weight: bold;">Alto&nbsp; &nbsp; &nbsp;<br type="_moz"></p>
                                                <p>¡Debes cambiar tu Estilo de Vida!<br type="_moz"></p>
                                                <p>Tienes el metabolismo lento<br type="_moz"></p>';
            } else {
                $habitos_costumbre_riesgos = '<p style="color: red;font-size: 2em;font-weight: bold;">Muy Alto&nbsp; &nbsp; &nbsp;<br type="_moz"></p>
                                                <p>¡Debes cambiar tu Estilo de Vida!<br type="_moz"></p>
                                                <p>Tienes el metabolismo lento<br type="_moz"></p>';
            }
            



            function asignarValorPorcentaje($respuesta) {
                $respuesta = strtolower($respuesta);
                switch(trim($respuesta)) {
                    case 'si':
                        return 25;
                    case 'no':
                        return 0;
                    default:
                        return 0; // Por defecto, 0 si la respuesta no coincide
                }
            }


            $antecedentesTotal=0;
            $factoresTotales=0;
            $hereditariorsTotal=0;

            $antecedentesPorcentaje=0;
            $factoresPorcentaje=0;
            $hereditariosPorcentaje=0;
        
            $promedio=0;
            $porcentajePromedio=0;

            $antecedentesTotal = asignarValorPorcentaje($insulina_alta) + asignarValorPorcentaje($resistencia_insulina) + asignarValorPorcentaje($elevaciones_azucar_embarazo) + asignarValorPorcentaje($sindrome_ovarios_poliquistico);
            $factoresTotales = asignarValorPorcentaje($sobrepeso) + asignarValorPorcentaje($diabetes_embarazo_hijo) + asignarValorPorcentaje($ejercicio_regular) + asignarValorPorcentaje($ovario_poliquistico);
            $hereditariorsTotal = 20;
            // Condicionales para sumar los valores
            if (strtolower($padre) == 'si' || strtolower($madre) == 'si') {
                $hereditariorsTotal += 35;
            }
            if (strtolower($hermanos) == 'si') {
                $hereditariorsTotal += 30;
            }
            if (strtolower($tios_paternos) == 'si' || strtolower($tios_maternos) == 'si') {
                $hereditariorsTotal += 25;
            }
            if (strtolower($abuelos_maternos) == 'si' || strtolower($abuelos_paternos) == 'si') {
                $hereditariorsTotal += 20;
            }
            

            if($antecedentesTotal>95){
                $antecedentesPorcentaje=95;
            } else {
                $antecedentesPorcentaje=$antecedentesTotal;
            }


            if($factoresTotales>95){
                $factoresPorcentaje=95;
            }else {
                $factoresPorcentaje=$factoresTotales;
            }


            if($hereditariorsTotal>95){
                $hereditariosPorcentaje=95;
            }else {
                $hereditariosPorcentaje=$hereditariorsTotal;
            }

                    
            $promedio=$antecedentesTotal+$factoresTotales+$hereditariosPorcentaje;
            $porcentajePromedio=round($promedio/3, 2);
            $porcentajePromedioFinal=0;
        
            if($porcentajePromedio>95)
            {
        
                $porcentajePromedioFinal=95;
        
            }
            else {
                $porcentajePromedioFinal=$porcentajePromedio;
            }
            
           
 
             $dataToInsert = [
                 'nombre' => $nombre,
                 'apellido' => $apellido,
                 'edad' => $edad,
                 'telefono_personal' => $telefono_personal,
                 'correo' => $correo,
                 'empresa' => $empresa,
                 'sede' => $sede,
                 'genero' => $genero,
                 'peso_en_libras' => $peso_en_libras,
                 'altura_en_cms' => $altura_en_cms,
                 'cintura_en_cms' => $cintura_en_cms,
                 
                 'agua_diaria' => $agua_diaria,
                 'horarios_refacciones_comidas' => $horarios_refacciones_comidas,
                 'porciones_pequenas' => $porciones_pequenas,
                 'separar_combinar_alimentos' => $separar_combinar_alimentos,
                 'rutina_ejercicio' => $rutina_ejercicio,
                 'vivir_saludable' => $vivir_saludable,
 
                 'insulina_alta' => $insulina_alta,
                 'resistencia_insulina' => $resistencia_insulina,
                 'elevaciones_azucar_embarazo' => $elevaciones_azucar_embarazo,
                 'sindrome_ovarios_poliquistico' => $sindrome_ovarios_poliquistico,
 
                 'sobrepeso' => $sobrepeso,
                 'diabetes_embarazo_hijo' => $diabetes_embarazo_hijo,
                 'ejercicio_regular' => $ejercicio_regular,
                 'ovario_poliquistico' => $ovario_poliquistico,
 
                 'padre' => $padre,
                 'madre' => $madre,
                 'hermanos' => $hermanos,
                 'tios_paternos' => $tios_paternos,
                 'tios_maternos' => $tios_maternos,
                 'abuelos_maternos' => $abuelos_maternos,
                 'abuelos_paternos' => $abuelos_paternos,
             ];
 
 
 
            $newEncuesta = EncuestaCribado::create($dataToInsert);
            $encryptedId = Crypt::encryptString($newEncuesta->id);
            $link = url('/webhook/cribado_encuesta_download') . '?id=' . urlencode($encryptedId);


            $mail = new PHPMailer(true);
            $mail->CharSet = 'UTF-8';
            $mail->isSMTP();
            $mail->Host = 'bclinik.com';
            $mail->Port = 465;
            $mail->SMTPSecure = 'ssl';
            $mail->SMTPAuth = true;
            $mail->Username = 'noreply@bclinik.com';
            $mail->Password = 'Password$001';
            $mail->setFrom('noreply@bclinik.com', 'Respuesta Encuesta Cribado');
            $mail->addReplyTo('noreply@bclinik.com', 'Respuesta Encuesta Cribado');
            $mail->addAddress($correo);
           
            $body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                    <html dir="ltr" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office">

                    <head>
                        <meta charset="UTF-8">
                        <meta content="width=device-width, initial-scale=1" name="viewport">
                        <meta name="x-apple-disable-message-reformatting">
                        <meta http-equiv="X-UA-Compatible" content="IE=edge">
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
                    </head>
                    <style>
                        /* CONFIG STYLES Please do not delete and edit CSS styles below */
                    /* IMPORTANT THIS STYLES MUST BE ON FINAL EMAIL */
                    .rollover:hover .rollover-first {
                        max-height: 0px !important;
                        display: none !important;
                    }

                    .rollover:hover .rollover-second {
                        max-height: none !important;
                        display: block !important;
                    }

                    .rollover span {
                        font-size: 0px;
                    }

                    u+.body img~div div {
                        display: none;
                    }

                    #outlook a {
                        padding: 0;
                    }

                    span.MsoHyperlink,
                    span.MsoHyperlinkFollowed {
                        color: inherit;
                        mso-style-priority: 99;
                    }

                    a.es-button {
                        mso-style-priority: 100 !important;
                        text-decoration: none !important;
                    }

                    a[x-apple-data-detectors] {
                        color: inherit !important;
                        text-decoration: none !important;
                        font-size: inherit !important;
                        font-family: inherit !important;
                        font-weight: inherit !important;
                        line-height: inherit !important;
                    }

                    .es-desk-hidden {
                        display: none;
                        float: left;
                        overflow: hidden;
                        width: 0;
                        max-height: 0;
                        line-height: 0;
                        mso-hide: all;
                    }

                    .es-button-border:hover>a.es-button {
                        color: #ffffff !important;
                    }

                    /*
                    END OF IMPORTANT
                    */
                    body {
                        width: 100%;
                        height: 100%;
                    }

                    table {
                        mso-table-lspace: 0pt;
                        mso-table-rspace: 0pt;
                        border-collapse: collapse;
                        border-spacing: 0px;
                    }

                    table td,
                    body,
                    .es-wrapper {
                        padding: 0;
                        Margin: 0;
                    }

                    .es-content,
                    .es-header,
                    .es-footer {
                        width: 100%;
                        table-layout: fixed !important;
                    }

                    img {
                        display: block;
                        font-size: 14px;
                        border: 0;
                        outline: none;
                        text-decoration: none;
                    }

                    p,
                    hr {
                        Margin: 0;
                    }

                    h1,
                    h2,
                    h3,
                    h4,
                    h5,
                    h6 {
                        Margin: 0;
                        font-family: arial, "helvetica neue", helvetica, sans-serif;
                        mso-line-height-rule: exactly;
                        letter-spacing: 0;
                    }

                    p,
                    a {
                        mso-line-height-rule: exactly;
                    }

                    .es-left {
                        float: left;
                    }

                    .es-right {
                        float: right;
                    }

                    .es-p5 {
                        padding: 5px;
                    }

                    .es-p5t {
                        padding-top: 5px;
                    }

                    .es-p5b {
                        padding-bottom: 5px;
                    }

                    .es-p5l {
                        padding-left: 5px;
                    }

                    .es-p5r {
                        padding-right: 5px;
                    }

                    .es-p10 {
                        padding: 10px;
                    }

                    .es-p10t {
                        padding-top: 10px;
                    }

                    .es-p10b {
                        padding-bottom: 10px;
                    }

                    .es-p10l {
                        padding-left: 10px;
                    }

                    .es-p10r {
                        padding-right: 10px;
                    }

                    .es-p15 {
                        padding: 15px;
                    }

                    .es-p15t {
                        padding-top: 15px;
                    }

                    .es-p15b {
                        padding-bottom: 15px;
                    }

                    .es-p15l {
                        padding-left: 15px;
                    }

                    .es-p15r {
                        padding-right: 15px;
                    }

                    .es-p20 {
                        padding: 20px;
                    }

                    .es-p25 {
                        padding: 25px;
                    }

                    .es-p25t {
                        padding-top: 25px;
                    }

                    .es-p25b {
                        padding-bottom: 25px;
                    }

                    .es-p25l {
                        padding-left: 25px;
                    }

                    .es-p25r {
                        padding-right: 25px;
                    }

                    .es-p30 {
                        padding: 30px;
                    }

                    .es-p30t {
                        padding-top: 30px;
                    }

                    .es-p30b {
                        padding-bottom: 30px;
                    }

                    .es-p30l {
                        padding-left: 30px;
                    }

                    .es-p30r {
                        padding-right: 30px;
                    }

                    .es-p35 {
                        padding: 35px;
                    }

                    .es-p35t {
                        padding-top: 35px;
                    }

                    .es-p35b {
                        padding-bottom: 35px;
                    }

                    .es-p35l {
                        padding-left: 35px;
                    }

                    .es-p35r {
                        padding-right: 35px;
                    }

                    .es-p40 {
                        padding: 40px;
                    }

                    .es-p40t {
                        padding-top: 40px;
                    }

                    .es-p40b {
                        padding-bottom: 40px;
                    }

                    .es-p40l {
                        padding-left: 40px;
                    }

                    .es-p40r {
                        padding-right: 40px;
                    }

                    .es-menu td {
                        border: 0;
                    }

                    .es-menu td a img {
                        display: inline !important;
                        vertical-align: middle;
                    }

                    /*
                    END CONFIG STYLES
                    */
                    s {
                        text-decoration: line-through;
                    }

                    ul,
                    ol {
                        font-family: arial, "helvetica neue", helvetica, sans-serif;
                        padding: 0px 0px 0px 40px;
                        margin: 15px 0px;
                    }

                    ul li {
                        color: #333333;
                    }

                    ol li {
                        color: #333333;
                    }

                    li {
                        margin: 0px 0px 15px;
                        font-size: 14px;
                    }

                    a {
                        text-decoration: underline;
                    }

                    .es-menu td a {
                        font-family: arial, "helvetica neue", helvetica, sans-serif;
                        text-decoration: none;
                        display: block;
                    }

                    .es-wrapper {
                        width: 100%;
                        height: 100%;
                        background-repeat: repeat;
                        background-position: center top;
                    }

                    .es-wrapper-color,
                    .es-wrapper {
                        background-color: #f6f6f6;
                    }

                    .es-content-body p,
                    .es-footer-body p,
                    .es-header-body p,
                    .es-infoblock p {
                        font-family: arial, "helvetica neue", helvetica, sans-serif;
                        line-height: 150%;
                        letter-spacing: 0;
                    }

                    .es-header {
                        background-color: transparent;
                        background-repeat: repeat;
                        background-position: center top;
                    }

                    .es-header-body {
                        background-color: #ffffff;
                    }

                    .es-header-body p {
                        color: #333333;
                        font-size: 14px;
                    }

                    .es-header-body a {
                        color: #2cb543;
                        font-size: 14px;
                    }

                    .es-content-body {
                        background-color: #ffffff;
                    }

                    .es-content-body a {
                        color: #2cb543;
                        font-size: 14px;
                    }

                    .es-footer {
                        background-color: transparent;
                        background-repeat: repeat;
                        background-position: center top;
                    }

                    .es-footer-body {
                        background-color: #ffffff;
                    }

                    .es-footer-body p {
                        color: #333333;
                        font-size: 14px;
                    }

                    .es-footer-body a {
                        color: #2cb543;
                        font-size: 14px;
                    }

                    .es-content-body p {
                        color: #333333;
                        font-size: 14px;
                    }

                    .es-infoblock p {
                        font-size: 12px;
                        color: #cccccc;
                    }

                    .es-infoblock a {
                        font-size: 12px;
                        color: #cccccc;
                    }

                    h1 {
                        font-size: 30px;
                        font-style: normal;
                        font-weight: normal;
                        line-height: 120%;
                        color: #333333;
                    }

                    h2 {
                        font-size: 24px;
                        font-style: normal;
                        font-weight: normal;
                        line-height: 120%;
                        color: #333333;
                    }

                    h3 {
                        font-size: 20px;
                        font-style: normal;
                        font-weight: normal;
                        line-height: 120%;
                        color: #333333;
                    }

                    .es-header-body h1 a,
                    .es-content-body h1 a,
                    .es-footer-body h1 a {
                        font-size: 30px;
                    }

                    .es-header-body h2 a,
                    .es-content-body h2 a,
                    .es-footer-body h2 a {
                        font-size: 24px;
                    }

                    .es-header-body h3 a,
                    .es-content-body h3 a,
                    .es-footer-body h3 a {
                        font-size: 20px;
                    }

                    h4 {
                        font-size: 24px;
                        font-style: normal;
                        font-weight: normal;
                        line-height: 120%;
                        color: #333333;
                    }

                    h5 {
                        font-size: 20px;
                        font-style: normal;
                        font-weight: normal;
                        line-height: 120%;
                        color: #333333;
                    }

                    h6 {
                        font-size: 16px;
                        font-style: normal;
                        font-weight: normal;
                        line-height: 120%;
                        color: #333333;
                    }

                    .es-header-body h4 a,
                    .es-content-body h4 a,
                    .es-footer-body h4 a {
                        font-size: 24px;
                    }

                    .es-header-body h5 a,
                    .es-content-body h5 a,
                    .es-footer-body h5 a {
                        font-size: 20px;
                    }

                    .es-header-body h6 a,
                    .es-content-body h6 a,
                    .es-footer-body h6 a {
                        font-size: 16px;
                    }

                    a.es-button,
                    button.es-button {
                        padding: 10px 20px 10px 20px;
                        display: inline-block;
                        background: #31cb4b;
                        border-radius: 30px 30px 30px 30px;
                        font-size: 18px;
                        font-family: arial, "helvetica neue", helvetica, sans-serif;
                        font-weight: normal;
                        font-style: normal;
                        line-height: 120%;
                        color: #ffffff;
                        text-decoration: none !important;
                        width: auto;
                        text-align: center;
                        letter-spacing: 0;
                        mso-padding-alt: 0;
                        mso-border-alt: 10px solid #31cb4b;
                    }

                    .es-button-border {
                        border-style: solid;
                        border-color: #2cb543 #2cb543 #2cb543 #2cb543;
                        background: #31cb4b;
                        border-width: 0px 0px 2px 0px;
                        display: inline-block;
                        border-radius: 30px 30px 30px 30px;
                        width: auto;
                        mso-hide: all;
                    }

                    .es-button img {
                        display: inline-block;
                        vertical-align: middle;
                    }

                    .es-fw,
                    .es-fw .es-button {
                        display: block;
                    }

                    .es-il,
                    .es-il .es-button {
                        display: inline-block;
                    }

                    .es-text-rtl h1,
                    .es-text-rtl h2,
                    .es-text-rtl h3,
                    .es-text-rtl h4,
                    .es-text-rtl h5,
                    .es-text-rtl h6,
                    .es-text-rtl input,
                    .es-text-rtl label,
                    .es-text-rtl textarea,
                    .es-text-rtl p,
                    .es-text-rtl ol,
                    .es-text-rtl ul,
                    .es-text-rtl .es-menu a,
                    .es-text-rtl .es-table {
                        direction: rtl;
                    }

                    .es-text-ltr h1,
                    .es-text-ltr h2,
                    .es-text-ltr h3,
                    .es-text-ltr h4,
                    .es-text-ltr h5,
                    .es-text-ltr h6,
                    .es-text-ltr input,
                    .es-text-ltr label,
                    .es-text-ltr textarea,
                    .es-text-ltr p,
                    .es-text-ltr ol,
                    .es-text-ltr ul,
                    .es-text-ltr .es-menu a,
                    .es-text-ltr .es-table {
                        direction: ltr;
                    }

                    .es-text-rtl ol,
                    .es-text-rtl ul {
                        padding: 0px 40px 0px 0px;
                    }

                    .es-text-ltr ul,
                    .es-text-ltr ol {
                        padding: 0px 0px 0px 40px;
                    }

                    /*
                    RESPONSIVE STYLES
                    Please do not delete and edit CSS styles below.

                    If you don"t need responsive layout, please delete this section.
                    */
                    .es-p20t {
                        padding-top: 20px;
                    }

                    .es-p20r {
                        padding-right: 20px;
                    }

                    .es-p20l {
                        padding-left: 20px;
                    }

                    .es-p20b {
                        padding-bottom: 20px;
                    }

                    .es-p-default {
                        padding-top: 20px;
                        padding-right: 20px;
                        padding-bottom: 0px;
                        padding-left: 20px;
                    }

                    .msohide {
                        mso-hide: all;
                    }

                    @media only screen and (max-width: 600px) {
                        h1 {
                            font-size: 30px !important;
                            text-align: left;
                        }

                        h2 {
                            font-size: 24px !important;
                            text-align: left;
                        }

                        h3 {
                            font-size: 20px !important;
                            text-align: left;
                        }

                        .es-m-p20b {
                            padding-bottom: 20px !important;
                        }

                        *[class="gmail-fix"] {
                            display: none !important;
                        }

                        p,
                        a {
                            line-height: 150% !important;
                        }

                        h1,
                        h1 a {
                            line-height: 120% !important;
                        }

                        h2,
                        h2 a {
                            line-height: 120% !important;
                        }

                        h3,
                        h3 a {
                            line-height: 120% !important;
                        }

                        h4,
                        h4 a {
                            line-height: 120% !important;
                        }

                        h5,
                        h5 a {
                            line-height: 120% !important;
                        }

                        h6,
                        h6 a {
                            line-height: 120% !important;
                        }

                        h4 {
                            font-size: 24px !important;
                            text-align: left;
                        }

                        h5 {
                            font-size: 20px !important;
                            text-align: left;
                        }

                        h6 {
                            font-size: 16px !important;
                            text-align: left;
                        }

                        .es-header-body h1 a,
                        .es-content-body h1 a,
                        .es-footer-body h1 a {
                            font-size: 30px !important;
                        }

                        .es-header-body h2 a,
                        .es-content-body h2 a,
                        .es-footer-body h2 a {
                            font-size: 24px !important;
                        }

                        .es-header-body h3 a,
                        .es-content-body h3 a,
                        .es-footer-body h3 a {
                            font-size: 20px !important;
                        }

                        .es-header-body h4 a,
                        .es-content-body h4 a,
                        .es-footer-body h4 a {
                            font-size: 24px !important;
                        }

                        .es-header-body h5 a,
                        .es-content-body h5 a,
                        .es-footer-body h5 a {
                            font-size: 20px !important;
                        }

                        .es-header-body h6 a,
                        .es-content-body h6 a,
                        .es-footer-body h6 a {
                            font-size: 16px !important;
                        }

                        .es-menu td a {
                            font-size: 14px !important;
                        }

                        .es-header-body p,
                        .es-header-body a {
                            font-size: 14px !important;
                        }

                        .es-content-body p,
                        .es-content-body a {
                            font-size: 14px !important;
                        }

                        .es-footer-body p,
                        .es-footer-body a {
                            font-size: 14px !important;
                        }

                        .es-infoblock p,
                        .es-infoblock a {
                            font-size: 12px !important;
                        }

                        .es-m-txt-c,
                        .es-m-txt-c h1,
                        .es-m-txt-c h2,
                        .es-m-txt-c h3,
                        .es-m-txt-c h4,
                        .es-m-txt-c h5,
                        .es-m-txt-c h6 {
                            text-align: center !important;
                        }

                        .es-m-txt-r,
                        .es-m-txt-r h1,
                        .es-m-txt-r h2,
                        .es-m-txt-r h3,
                        .es-m-txt-r h4,
                        .es-m-txt-r h5,
                        .es-m-txt-r h6 {
                            text-align: right !important;
                        }

                        .es-m-txt-j,
                        .es-m-txt-j h1,
                        .es-m-txt-j h2,
                        .es-m-txt-j h3,
                        .es-m-txt-j h4,
                        .es-m-txt-j h5,
                        .es-m-txt-j h6 {
                            text-align: justify !important;
                        }

                        .es-m-txt-l,
                        .es-m-txt-l h1,
                        .es-m-txt-l h2,
                        .es-m-txt-l h3,
                        .es-m-txt-l h4,
                        .es-m-txt-l h5,
                        .es-m-txt-l h6 {
                            text-align: left !important;
                        }

                        .es-m-txt-r img,
                        .es-m-txt-c img,
                        .es-m-txt-l img {
                            display: inline !important;
                        }

                        .es-m-txt-r .rollover:hover .rollover-second,
                        .es-m-txt-c .rollover:hover .rollover-second,
                        .es-m-txt-l .rollover:hover .rollover-second {
                            display: inline !important;
                        }

                        .es-m-txt-r .rollover span,
                        .es-m-txt-c .rollover span,
                        .es-m-txt-l .rollover span {
                            line-height: 0 !important;
                            font-size: 0 !important;
                        }

                        .es-spacer {
                            display: inline-table;
                        }

                        a.es-button,
                        button.es-button {
                            font-size: 18px !important;
                            line-height: 120% !important;
                        }

                        a.es-button,
                        button.es-button,
                        .es-button-border {
                            display: inline-block !important;
                        }

                        .es-m-fw,
                        .es-m-fw.es-fw,
                        .es-m-fw .es-button {
                            display: block !important;
                        }

                        .es-m-il,
                        .es-m-il .es-button,
                        .es-social,
                        .es-social td,
                        .es-menu {
                            display: inline-block !important;
                        }

                        .es-adaptive table,
                        .es-left,
                        .es-right {
                            width: 100% !important;
                        }

                        .es-content table,
                        .es-header table,
                        .es-footer table,
                        .es-content,
                        .es-footer,
                        .es-header {
                            width: 100% !important;
                            max-width: 600px !important;
                        }

                        .adapt-img {
                            width: 100% !important;
                            height: auto !important;
                        }

                        .es-mobile-hidden,
                        .es-hidden {
                            display: none !important;
                        }

                        .es-desk-hidden {
                            width: auto !important;
                            overflow: visible !important;
                            float: none !important;
                            max-height: inherit !important;
                            line-height: inherit !important;
                            display: table-row !important;
                        }

                        tr.es-desk-hidden {
                            display: table-row !important;
                        }

                        table.es-desk-hidden {
                            display: table !important;
                        }

                        td.es-desk-menu-hidden {
                            display: table-cell !important;
                        }

                        .es-menu td {
                            width: 1% !important;
                        }

                        table.es-table-not-adapt,
                        .esd-block-html table {
                            width: auto !important;
                        }

                        .es-social td {
                            padding-bottom: 10px;
                        }

                        .h-auto {
                            height: auto !important;
                        }
                    }

                    /*
                    END RESPONSIVE STYLES
                    */
                    ul li,
                    ol li {
                        margin-left: 0;
                    }
                    </style>

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
                                            <table cellpadding="0" cellspacing="0" class="es-content esd-header-popover" align="center">
                                                <tbody>
                                                    <tr>
                                                        <td class="esd-stripe" align="center">
                                                            <table bgcolor="#ffffff" class="es-content-body" align="center" cellpadding="0" cellspacing="0" width="600">
                                                                <tbody>
                                                                    <tr>
                                                                        <td class="es-p20t es-p20r es-p20l esd-structure" align="left">
                                                                            <table cellpadding="0" cellspacing="0" width="100%">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td width="560" class="esd-container-frame" align="center" valign="top">
                                                                                            <table cellpadding="0" cellspacing="0" width="100%">
                                                                                                <tbody>
                                                                                                    <tr>
                                                                                                        <td align="center" class="esd-block-image" style="font-size: 0px;"><a target="_blank" href><img class="adapt-img" src="https://appweb.bclinik.com/img/img_correos/header.png" alt style="display: block;" width="560"></a></td>
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
                                            <table cellpadding="0" cellspacing="0" class="es-content" align="center">
                                                <tbody>
                                                    <tr>
                                                        <td class="esd-stripe" align="center">
                                                            <table bgcolor="#ffffff" class="es-content-body" align="center" cellpadding="0" cellspacing="0" width="600">
                                                                <tbody>
                                                                    <tr>
                                                                        <td class="es-p20t es-p20r es-p20l esd-structure" align="left">
                                                                            <!--[if mso]><table width="560" cellpadding="0" cellspacing="0"><tr><td width="415" valign="top"><![endif]-->
                                                                            <table cellpadding="0" cellspacing="0" class="es-left" align="left">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td width="415" class="es-m-p20b esd-container-frame" align="center">
                                                                                            <table cellpadding="0" cellspacing="0" width="100%">
                                                                                                <tbody>
                                                                                                    <tr>
                                                                                                        <td align="center" class="esd-empty-container" style="display: none;"></td>
                                                                                                    </tr>
                                                                                                </tbody>
                                                                                            </table>
                                                                                        </td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                            <!--[if mso]></td><td width="20"></td><td width="125" valign="top"><![endif]-->
                                                                            <table cellpadding="0" cellspacing="0" class="es-right" align="right">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td width="125" align="center" class="esd-container-frame">
                                                                                            <table cellpadding="0" cellspacing="0" width="100%">
                                                                                                <tbody>
                                                                                                    <tr>
                                                                                                        <td align="left" class="esd-block-text">
                                                                                                            <p>Fecha: '.date('Y/m/d').' <br></p>
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
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <table class="es-content" cellspacing="0" cellpadding="0" align="center">
                                                <tbody>
                                                    <tr>
                                                        <td class="esd-stripe" align="center">
                                                            <table class="es-content-body" width="600" cellspacing="0" cellpadding="0" bgcolor="#ffffff" align="center">
                                                                <tbody>
                                                                    <tr>
                                                                        <td class="es-p20t es-p20r es-p20l esd-structure" align="left">
                                                                            <table width="100%" cellspacing="0" cellpadding="0">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td class="esd-container-frame" width="560" valign="top" align="center">
                                                                                            <table width="100%" cellspacing="0" cellpadding="0">
                                                                                                <tbody>
                                                                                                    <tr>
                                                                                                        <td align="left" class="esd-block-text">
                                                                                                            <p>Hola '.$nombre.' '.$apellido.'</p>
                                                                                                            <p>Gracias por confiarnos tus datos: <br type="_moz"></p>
                                                                                                            <p>Tu <b>IMC </b> es de '.$result.' que corresponde a la categoría '.$categoria.' <br type="_moz"></p>
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
                                            <table cellpadding="0" cellspacing="0" class="es-content" align="center">
                                                <tbody>
                                                    <tr>
                                                        <td class="esd-stripe" align="center">
                                                            <table bgcolor="#ffffff" class="es-content-body" align="center" cellpadding="0" cellspacing="0" width="600">
                                                                <tbody>
                                                                    <tr>
                                                                        <td class="esd-structure es-p20" align="left">
                                                                            <!--[if mso]><table width="560" cellpadding="0" cellspacing="0"><tr><td width="133" valign="top"><![endif]-->
                                                                            <table cellpadding="0" cellspacing="0" class="es-left" align="left">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td width="133" class="es-m-p0r esd-container-frame es-m-p20b" valign="top" align="center">
                                                                                            <table cellpadding="0" cellspacing="0" width="100%">
                                                                                                <tbody>
                                                                                                    <tr>
                                                                                                        <td align="center" class="esd-block-image" style="font-size: 0px;"><a target="_blank"><img class="adapt-img" src="https://appweb.bclinik.com/img/img_correos/semaforo.png" alt style="display: block;" width="133"></a></td>
                                                                                                    </tr>
                                                                                                </tbody>
                                                                                            </table>
                                                                                        </td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                            <!--[if mso]></td><td width="20"></td><td width="407" valign="top"><![endif]-->
                                                                            <table class="es-right" cellpadding="0" cellspacing="0" align="right">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td width="407" align="left" class="esd-container-frame">
                                                                                            <table cellpadding="0" cellspacing="0" width="100%">
                                                                                                <tbody>
                                                                                                    <tr>
                                                                                                        <td align="left" class="esd-block-text">
                                                                                                            <p style="color: #333333;"><br></p>
                                                                                                            <p style="color: #333333;"><br></p>
                                                                                                            <p style="color: #333333;">Por lo que el nivel de riesgo de desarrollar Síndrome Metabólico es: </p>
                                                                                                            <p style="color: #333333;">'.$nivel.'</p>
                                                                                                            <p style="text-align: center; color: #0e2841;"><i>El índice de masa corporal es la relación de tu peso con tu estatura. </i></p>
                                                                                                            <p style="text-align: center; color: #0e2841;"><i>Es el indicador más confiable para saber si tienes sobrepeso o ya estás en obesidad. &nbsp;&nbsp; </i></p>
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
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <table cellpadding="0" cellspacing="0" class="es-content" align="center">
                                                <tbody>
                                                    <tr>
                                                        <td class="esd-stripe" align="center">
                                                            <table bgcolor="#ffffff" class="es-content-body" align="center" cellpadding="0" cellspacing="0" width="600">
                                                                <tbody>
                                                                    <tr>
                                                                        <td class="es-p20t es-p20r es-p20l esd-structure" align="left">
                                                                            <table cellpadding="0" cellspacing="0" width="100%">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td width="560" class="esd-container-frame" align="center" valign="top">
                                                                                            <table cellpadding="0" cellspacing="0" width="100%">
                                                                                                <tbody>
                                                                                                    <tr>
                                                                                                        <td align="center" class="esd-block-image" style="font-size: 0px;"><a target="_blank"><img class="adapt-img" src="https://appweb.bclinik.com/img/img_correos/imc.png" alt style="display: block;" width="530"></a></td>
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
                                            <table cellpadding="0" cellspacing="0" class="es-content" align="center">
                                                <tbody>
                                                    <tr>
                                                        <td class="esd-stripe" align="center">
                                                            <table bgcolor="#ffffff" class="es-content-body" align="center" cellpadding="0" cellspacing="0" width="600">
                                                                <tbody>
                                                                    <tr>
                                                                        <td class="es-p20t es-p20r es-p20l esd-structure" align="left">
                                                                            <table cellpadding="0" cellspacing="0" width="100%">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td width="560" class="esd-container-frame" align="center" valign="top">
                                                                                            <table cellpadding="0" cellspacing="0" width="100%">
                                                                                                <tbody>
                                                                                                    <tr>
                                                                                                        <td align="left" class="esd-block-text">
                                                                                                            <p>●&nbsp; &nbsp; &nbsp; &nbsp;<span style="color:#DAA520;"><b><i>Entre 25 a 29.99</i></b></span><i>&nbsp;</i><i> es sobrepeso también llamado</i><i> pre obesidad.</i></p>
                                                                                                            <p>●&nbsp; &nbsp; &nbsp; &nbsp;<span style="color:#FF8C00;"><b><i>Entre 30 a 34.99</i></b></span><i> estás a tiempo de retroceder categorías y llegar a lo normal.</i></p>
                                                                                                            <p>●&nbsp; &nbsp; &nbsp; &nbsp;<span style="color:#FF0000;"><b><i>Entre 35 a 39.99</i></b></span><i> estas a tiempo de retroceder categorías y evitar el Síndrome Metabólico.</i></p>
                                                                                                            <p>●&nbsp; &nbsp; &nbsp; &nbsp;<span style="color:#800000;"><b><i>Mayor de 40</i></b></span><i> </i><i>no solo puedes retroceder categorías, además, controlas el Síndrome Metabólico que haya aparecido:</i><br type="_moz"></p>
                                                                                                            <p><i>(Obesidad, Diabetes, Hipertensión, Colesterol y Triglicéridos altos).</i></p>
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
                                            <table cellpadding="0" cellspacing="0" class="es-content" align="center">
                                                <tbody>
                                                    <tr>
                                                        <td class="esd-stripe" align="center">
                                                            <table bgcolor="#ffffff" class="es-content-body" align="center" cellpadding="0" cellspacing="0" width="600">
                                                                <tbody>
                                                                    <tr>
                                                                        <td class="es-p20t es-p20r es-p20l esd-structure" align="left">
                                                                            <table cellpadding="0" cellspacing="0" width="100%">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td width="560" class="esd-container-frame" align="center" valign="top">
                                                                                            <table cellpadding="0" cellspacing="0" width="100%">
                                                                                                <tbody>
                                                                                                    <tr>
                                                                                                        <td align="left" class="esd-block-text">
                                                                                                            <p style="color: #0f4761;"><b>Interpretación de resultados</b></p>
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
                                            <table cellpadding="0" cellspacing="0" class="es-content" align="center">
                                                <tbody>
                                                    <tr>
                                                        <td class="esd-stripe" align="center">
                                                            <table bgcolor="#ffffff" class="es-content-body" align="center" cellpadding="0" cellspacing="0" width="600">
                                                                <tbody>
                                                                    <tr>
                                                                        <td class="es-p20t es-p20r es-p20l esd-structure" align="left">
                                                                            <table cellpadding="0" cellspacing="0" width="100%">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td width="560" class="esd-container-frame" align="center" valign="top">
                                                                                            <table cellpadding="0" cellspacing="0" width="100%">
                                                                                                <tbody>
                                                                                                    <tr>
                                                                                                        <td align="left" class="esd-block-text">
                                                                                                            <p>La OMS Organización Mundial de la Salud, establece que una definición comúnmente en uso con los siguientes valores, acordados en 1997, publicados en 2000 y ajustados en 2010.<br type="_moz"></p>
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
                                            <table cellpadding="0" cellspacing="0" class="es-content" align="center">
                                                <tbody>
                                                    <tr>
                                                        <td class="esd-stripe" align="center">
                                                            <table bgcolor="#ffffff" class="es-content-body" align="center" cellpadding="0" cellspacing="0" width="600">
                                                                <tbody>
                                                                    <tr>
                                                                        <td class="es-p20t es-p20r es-p20l esd-structure" align="left">
                                                                            <table cellpadding="0" cellspacing="0" width="100%">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td width="560" class="esd-container-frame" align="center" valign="top">
                                                                                            <table cellpadding="0" cellspacing="0" width="100%">
                                                                                                <tbody>
                                                                                                    <tr>
                                                                                                        <td align="center" class="esd-block-image" style="font-size: 0px;"><a target="_blank"><img class="adapt-img" src="https://appweb.bclinik.com/img/img_correos/tabla.png" alt style="display: block;" width="560"></a></td>
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
                                            <table cellpadding="0" cellspacing="0" class="es-content" align="center">
                                                <tbody>
                                                    <tr>
                                                        <td class="esd-stripe" align="center">
                                                            <table bgcolor="#ffffff" class="es-content-body" align="center" cellpadding="0" cellspacing="0" width="600">
                                                                <tbody>
                                                                    <tr>
                                                                        <td class="es-p20t es-p20r es-p20l esd-structure" align="left">
                                                                            <table cellpadding="0" cellspacing="0" width="100%">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td width="560" class="esd-container-frame" align="center" valign="top">
                                                                                            <table cellpadding="0" cellspacing="0" width="100%">
                                                                                                <tbody>
                                                                                                    <tr>
                                                                                                        <td align="center" class="esd-block-text">
                                                                                                            <p style="color: #0f4761; font-size: 24px;"><strong> Hábitos y costumbres </strong></p>
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
                                            <table cellpadding="0" cellspacing="0" class="es-content" align="center">
                                                <tbody>
                                                    <tr>
                                                        <td class="esd-stripe" align="center">
                                                            <table bgcolor="#ffffff" class="es-content-body" align="center" cellpadding="0" cellspacing="0" width="600">
                                                                <tbody>
                                                                    <tr>
                                                                        <td class="es-p20t es-p20r es-p20l esd-structure" align="left">
                                                                            <table cellpadding="0" cellspacing="0" width="100%">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td width="560" class="esd-container-frame" align="center" valign="top">
                                                                                            <table cellpadding="0" cellspacing="0" width="100%">
                                                                                                <tbody>
                                                                                                    <tr>
                                                                                                        <td align="left" class="esd-block-text">
                                                                                                            <p>El nivel de riesgo de desarrollar Síndrome Metabólico por tus hábitos y costumbres es:<br type="_moz"></p>
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
                                            <table cellpadding="0" cellspacing="0" class="es-content" align="center">
                                                <tbody>
                                                    <tr>
                                                        <td class="esd-stripe" align="center">
                                                            <table bgcolor="#ffffff" class="es-content-body" align="center" cellpadding="0" cellspacing="0" width="600">
                                                                <tbody>
                                                                    <tr>
                                                                        <td class="esd-structure es-p20" align="left">
                                                                            <!--[if mso]><table width="560" cellpadding="0" cellspacing="0"><tr><td width="133" valign="top"><![endif]-->
                                                                            <table cellpadding="0" cellspacing="0" class="es-left" align="left">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td width="133" class="es-m-p0r esd-container-frame es-m-p20b" valign="top" align="center">
                                                                                            <table cellpadding="0" cellspacing="0" width="100%">
                                                                                                <tbody>
                                                                                                    <tr>
                                                                                                        <td align="center" class="esd-block-image" style="font-size: 0px;"><a target="_blank"><img class="adapt-img" src="https://appweb.bclinik.com/img/img_correos/semaforo.png" alt style="display: block;" width="133"></a></td>
                                                                                                    </tr>
                                                                                                </tbody>
                                                                                            </table>
                                                                                        </td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                            <!--[if mso]></td><td width="20"></td><td width="407" valign="top"><![endif]-->
                                                                            <table class="es-right" cellpadding="0" cellspacing="0" align="right">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td width="407" align="left" class="esd-container-frame">
                                                                                            <table cellpadding="0" cellspacing="0" width="100%">
                                                                                                <tbody>
                                                                                                    <tr>
                                                                                                        <td align="left" class="esd-block-text">
                                                                                                            <p style="color:#333333"><br></p>
                                                                                                            <p style="color:#333333"><br></p>
                                                                                                            '.$habitos_costumbre_riesgos.'
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
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <table cellpadding="0" cellspacing="0" class="es-content" align="center">
                                                <tbody>
                                                    <tr>
                                                        <td class="esd-stripe" align="center">
                                                            <table bgcolor="#ffffff" class="es-content-body" align="center" cellpadding="0" cellspacing="0" width="600">
                                                                <tbody>
                                                                    <tr>
                                                                        <td class="es-p20t es-p20r es-p20l esd-structure" align="left">
                                                                            <table cellpadding="0" cellspacing="0" width="100%">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td width="560" class="esd-container-frame" align="center" valign="top">
                                                                                            <table cellpadding="0" cellspacing="0" width="100%">
                                                                                                <tbody>
                                                                                                    <tr>
                                                                                                        <td align="center" class="esd-block-text">
                                                                                                            <p style="color: #0f4761; font-size: 24px;"><strong></strong><strong>Pre Diabetes y Diabetes</strong><strong></strong><br type="_moz"></p>
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
                                            <table cellpadding="0" cellspacing="0" class="es-content" align="center">
                                                <tbody>
                                                    <tr>
                                                        <td class="esd-stripe" align="center">
                                                            <table bgcolor="#ffffff" class="es-content-body" align="center" cellpadding="0" cellspacing="0" width="600">
                                                                <tbody>
                                                                    <tr>
                                                                        <td class="es-p20t es-p20r es-p20l esd-structure" align="left">
                                                                            <table cellpadding="0" cellspacing="0" width="100%">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td width="560" class="esd-container-frame" align="center" valign="top">
                                                                                            <table cellpadding="0" cellspacing="0" width="100%">
                                                                                                <tbody>
                                                                                                    <tr>
                                                                                                        <td align="left" class="esd-block-text">
                                                                                                            <p>El riesgo de desarrollar Síndrome Metabólico, específicamente Prediabetes y Diabetes por antecedentes familiares, factores de riesgo propios y hereditarios es: </p>
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
                                            <table cellpadding="0" cellspacing="0" class="es-content" align="center">
                                                <tbody>
                                                    <tr>
                                                        <td class="esd-stripe" align="center">
                                                            <table bgcolor="#ffffff" class="es-content-body" align="center" cellpadding="0" cellspacing="0" width="600">
                                                                <tbody>
                                                                    <tr>
                                                                        <td class="esd-structure es-p20" align="left">
                                                                            <!--[if mso]><table width="560" cellpadding="0" cellspacing="0"><tr><td width="133" valign="top"><![endif]-->
                                                                            <table cellpadding="0" cellspacing="0" class="es-left" align="left">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td width="133" class="es-m-p0r esd-container-frame es-m-p20b" valign="top" align="center">
                                                                                            <table cellpadding="0" cellspacing="0" width="100%">
                                                                                                <tbody>
                                                                                                    <tr>
                                                                                                        <td align="center" class="esd-block-image" style="font-size: 0px;"><a target="_blank"><img class="adapt-img" src="https://appweb.bclinik.com/img/img_correos/semaforo.png" alt style="display: block;" width="133"></a></td>
                                                                                                    </tr>
                                                                                                </tbody>
                                                                                            </table>
                                                                                        </td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                            <!--[if mso]></td><td width="20"></td><td width="407" valign="top"><![endif]-->
                                                                            <table class="es-right" cellpadding="0" cellspacing="0" align="right">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td width="407" align="left" class="esd-container-frame">
                                                                                            <table cellpadding="0" cellspacing="0" width="100%">
                                                                                                <tbody>
                                                                                                    <tr>
                                                                                                        <td align="left" class="esd-block-text">
                                                                                                            <p style="color: #333333; "><br></p>
                                                                                                            <p style="color: #333333; "><br></p>
                                                                                                            <p style="line-height: 200%;">Por antecedentes familiares la posibilidad es '.$antecedentesPorcentaje.'% </p>
                                                                                                            <p style="line-height: 200%;">Por factores de riesgo propios la posibilidad es '.$factoresPorcentaje.'% </p>
                                                                                                            <p style="line-height: 200%;">Por factores hereditarios la posibilidad es '.$hereditariosPorcentaje.'% </p>
                                                                                                            <p style="line-height: 200%;"><b>La posibilidad total de desarrollar prediabetes es </b><b>'.$porcentajePromedioFinal.'% </b></p>
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
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <table cellpadding="0" cellspacing="0" class="es-content" align="center">
                                                <tbody>
                                                    <tr>
                                                        <td class="esd-stripe" align="center">
                                                            <table bgcolor="#ffffff" class="es-content-body" align="center" cellpadding="0" cellspacing="0" width="600">
                                                                <tbody>
                                                                    <tr>
                                                                        <td class="es-p20t es-p20r es-p20l esd-structure" align="left">
                                                                            <table cellpadding="0" cellspacing="0" width="100%">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td width="560" class="esd-container-frame" align="center" valign="top">
                                                                                            <table cellpadding="0" cellspacing="0" width="100%">
                                                                                                <tbody>
                                                                                                    <tr>
                                                                                                        <td align="left" class="esd-block-text">
                                                                                                            <p><i>Prediabetes significa que tienes un nivel de glucosa sanguínea más alto de lo normal. Aún no es lo suficientemente alto como para considerarse diabetes tipo 2, pero si no haces cambios en el estilo de vida, tienes una alta probabilidad de desarrollarla. La Prediabetes se cura, la Diabetes no. </i></p>
                                                                                                            <p>La certeza si estás desarrollando Síndrome Metabólico, prediabetes o ya tienes diabetes es con una prueba de sangre por laboratorio. Agenda tu cita para realizar la prueba. </p>
                                                                                                            <p style="line-height: 130% !important" align="justify">
                                                                                                                <b>Si deseas conocer más sobre tu metabolismo, agenda tu cita para hacerte tu
                                                                                                                    evaluación personalizada y con los resultados podemos sugerirte 2 opciones:</b>
                                                                                                            </p>
                                                                                                            
                                                                                                            <ol>
                                                                                                                <li><p>Realiza un <strong style="color: #002545">Metabograma</strong></p>
                                                                                                                        Obtén un análisis exhaustivo y personalizado de tu metabolismo. Incluye
                                                                                                                        historial médico, composición corporal, antropometría y pruebas de laboratorio
                                                                                                                        avanzadas, todo para una visión completa de tu salud.
                                                                                                                </li>
                                                                                                                <li><p> Únete al <strong style="color: #002545">Programa Aceleración Metabólica PAM</strong></p>
                                                                                                                        Participa en un programa específico diseñado para acelerar tu metabolismo y
                                                                                                                        reducir grasa-peso de manera permanente y sostenible.
                                                                                                                </li>
                                                                                                            </ol>
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
                                            <table cellpadding="0" cellspacing="0" class="es-content" align="center">
                                                <tbody>
                                                    <tr>
                                                        <td class="esd-stripe" align="center">
                                                            <table bgcolor="#ffffff" class="es-content-body" align="center" cellpadding="0" cellspacing="0" width="600">
                                                                <tbody>
                                                                    <tr>
                                                                        <td class="es-p20t es-p20r es-p20l esd-structure" align="left">
                                                                            <table cellpadding="0" cellspacing="0" width="100%">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td width="560" class="es-m-p0r esd-container-frame" align="center">
                                                                                            <table cellpadding="0" cellspacing="0" width="100%">
                                                                                                <tbody>
                                                                                                    <tr>
                                                                                                        <td align="left" class="esd-block-text">
                                                                                                            <p><b>Agenda tu cita ahora:</b></p>
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
                                            <table cellpadding="0" cellspacing="0" class="es-content" align="center">
                                                <tbody>
                                                    <tr>
                                                        <td class="esd-stripe" align="center">
                                                            <table bgcolor="#ffffff" class="es-content-body" align="center" cellpadding="0" cellspacing="0" width="600">
                                                                <tbody>
                                                                    <tr>
                                                                        <td class="es-p20t es-p20r es-p20l esd-structure" align="left">
                                                                            <!--[if mso]><table width="560" cellpadding="0" cellspacing="0"><tr><td width="47" valign="top"><![endif]-->
                                                                            <table cellpadding="0" cellspacing="0" class="es-left" align="left">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td width="200" class="esd-container-frame es-m-p20b" align="left">
                                                                                            <table cellpadding="0" cellspacing="0" width="100%">
                                                                                                <tbody>
                                                                                                    <tr>
                                                                                                        <td align="center" class="esd-block-image" style="font-size: 0px;"><a target="_blank"><img class="adapt-img" src="https://appweb.bclinik.com/img/img_correos/whatsapp.png" alt style="display: block;" width="40"></a></td>
                                                                                                        <td align="left" class="esd-block-text">
                                                                                                            <p><b>Whatsapp: </b><a href="https://wa.link/d8atu5"><b> 3324-3501 </b></a><br type="_moz"></p>
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
                                            <table cellpadding="0" cellspacing="0" class="es-content" align="center">
                                                <tbody>
                                                    <tr>
                                                        <td class="esd-stripe" align="center">
                                                            <table bgcolor="#ffffff" class="es-content-body" align="center" cellpadding="0" cellspacing="0" width="600">
                                                                <tbody>
                                                                    <tr>
                                                                        <td class="es-p20t es-p20r es-p20l esd-structure" align="left">
                                                                            <table cellpadding="0" cellspacing="0" width="100%">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td width="560" class="esd-container-frame" align="center" valign="top">
                                                                                            <table cellpadding="0" cellspacing="0" width="100%">
                                                                                                <tbody>
                                                                                                    <tr>
                                                                                                        <td align="left" class="esd-block-text">
                                                                                                            <p><b>Ubícanos en:</b><br type="_moz"></p>
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
                                            <table cellpadding="0" cellspacing="0" class="es-content" align="center">
                                                <tbody>
                                                    <tr>
                                                        <td class="esd-stripe" align="center">
                                                            <table bgcolor="#ffffff" class="es-content-body" align="center" cellpadding="0" cellspacing="0" width="600">
                                                                <tbody>
                                                                    <tr>
                                                                        <td class="es-p20t es-p20r es-p20l esd-structure" align="left">
                                                                            <table cellpadding="0" cellspacing="0" width="100%">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td width="560" class="esd-container-frame" align="center" valign="top">
                                                                                            <table cellpadding="0" cellspacing="0" width="100%">
                                                                                                <tbody>
                                                                                                    <tr>
                                                                                                        <td align="left" class="esd-block-text">
                                                                                                            <p>⮚&nbsp;&nbsp;&nbsp;&nbsp; <a target="_blank" style="text-decoration: none; color: #333333;" href="https://www.google.com/search?q=bio+clinik+zona+10"><b>Edificio 01010 - Zona 10&nbsp;&nbsp;&nbsp;&nbsp; </b></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
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
                                            <table cellpadding="0" cellspacing="0" class="es-content esd-footer-popover" align="center">
                                                <tbody>
                                                    <tr>
                                                        <td class="esd-stripe" align="center">
                                                            <table bgcolor="#ffffff" class="es-content-body" align="center" cellpadding="0" cellspacing="0" width="600">
                                                                <tbody>
                                                                    <tr>
                                                                        <td class="es-p20t es-p20r es-p20l esd-structure" align="left">
                                                                            <table cellpadding="0" cellspacing="0" width="100%">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td width="560" class="esd-container-frame" align="center" valign="top">
                                                                                            <table cellpadding="0" cellspacing="0" width="100%">
                                                                                                <tbody>
                                                                                                    <tr>
                                                                                                        <td align="center" class="esd-block-image" style="font-size: 0px;"><a target="_blank"><img class="adapt-img" src="https://appweb.bclinik.com/img/img_correos/footer.png" alt style="display: block;" width="560"></a></td>
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

                    </html>';

                    // echo $body;
                    // exit();
            
            $mail->Subject = 'Respuesta Encuesta Cribado';
            
            $mail->isHTML(true);
            $mail->MsgHTML($body);

            $mail->Body = $body;

            if (!$mail->send()) {
                throw new \Exception('Error al enviar el correo: ' . $mail->ErrorInfo);
            }

            return response()->json(['message' => 'Datos recibidos y almacenados correctamente'], 200);
        } catch (\Exception $e) {
            Log::error('Error al procesar la solicitud: ' . $e->getMessage());
            return response()->json(['message' => 'Error al procesar la solicitud','a'=>$e->getMessage()], 500);
        }
    }
}
