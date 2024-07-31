<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Resultado IMC</title>
        <style>
            body {
                margin: 0;
                padding: 8%;
            }
            .container {
                page-break-before: always;
            }
            .container:first-of-type {
                page-break-before: auto;
            }
            #texto {
                display: inline-block;
                vertical-align: top;
            }
        </style>
    </head>
    <body>
        <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0">
            <tbody>
                <tr>
                    <td>
                        <table>
                            {{-- inicio del primer bloque --}}
                            <table width="600px" cellpadding="0" cellspacing="0" border="0" align="center" style="color: #595959;" class="container">
                                <tr>
                                    <td>
                                        <img src="https://appweb.bclinik.com/img/img_correos/header.png" alt="header" />
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for style="text-align: right; display: block; padding: 10px;">Fecha: {{ $date }}</label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p>Hola {{ $nombre_de_quien_solicita }}</p>
                                        <p style="color: #333333;">&nbsp; &nbsp; &nbsp; &nbsp; {{ $nombre_de_la_empresa }} &nbsp;</p>
                                        <p style="color: #333333;">&nbsp;&nbsp; &nbsp; &nbsp; {{ $puesto_en_la_empresa }}</p>
                                        <p>Bienvenido al Centro de Investigación Metabólica + Bio Clinik. celebramos tu interés en nuestro:</p>
                                        <h2 style="color: #0f4761; text-align: center;">
                                            Cribado Empresarial
                                        </h2>
                                        <p style="text-align: justify;">
                                            Formar parte de nuestras <span style="font-style: italic; color: #002545;">Pruebas Metabólicas Digitales</span> es un gran paso hacia el logro del bienestar y la consolidación de equipos de
                                            trabajo cada vez más productivos. Este programa, ofrecido por el <span style="font-style: italic; color: #002545;">Centro de Investigación Metabólica +Bio Clinik,</span> es una herramienta para
                                            detectar la prevalencia, (el número total de personas en un grupo específico que tienen o tuvieron una o varias de las enfermedades que conforman el Síndrome Metabólico) entre los colaboradores de
                                            su empresa.
                                        </p>
                                        <p style="color: #002545; font-weight: bold;">
                                            ¿Específicamente qué es el Cribado Empresarial?
                                        </p>
                                        <p style="text-align: justify;">
                                            Consiste en la realización de pruebas diagnósticas a personas, en principio sanas, para distinguir aquellas que posiblemente estén enfermas de las que posiblemente no lo estén (tamizaje).
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: center;">
                                        <img style="width: 90%; text-align: center; margin: 0 auto;" src="https://appweb.bclinik.com/img/img_correos/cribado01.png" alt="imc" />
                                    </td>
                                </tr>
                            </table>
                            {{-- final del primer bloque --}} {{-- inicio del segundo bloque --}}
                            <table width="600px" cellpadding="0" cellspacing="0" border="0" align="center" style="color: #595959;" class="container">
                                <tr>
                                    <p style="text-align: justify;">
                                        El objetivo es la detección temprana y oportuna de los riesgos medios y altos en que se encuentran los colaboradores de desarrollar en todo o en parte el Síndrome Metabólico, síndrome que incluye
                                        Sobrepeso, Obesidad, Prediabetes, Diabetes y/ o dislipidemia (Colesterol y/o Triglicéridos anormales).
                                    </p>
                                    <p style="text-align: justify; color: #002545; font-weight: bold;">
                                        Beneficios del Cribado Empresarial:
                                    </p>
                                    <p style="text-align: justify;">
                                        Formulamos un scanner, una representación visual del estado de salud de sus colaboradores, podrá conocer el estado de salud metabólico de sus equipos de trabajo y tomar decisiones para:
                                    </p>
                                    <style></style>

                                    <ul>
                                        <li style="text-align: justify;">
                                            Reducción del ausentismo: identificar problemas de salud antes de que se manifiesten plenamente, ayuda a prevenir bajas laborales
                                        </li>
                                        <li style="text-align: justify;">
                                            Prevención de accidentes laborales: al conocer el estado metabólico de los colaboradores, se pueden evitar situaciones de riesgo.
                                        </li>
                                        <li style="text-align: justify;">
                                            Mejora del entorno laboral: fomentar la salud y el bienestar contribuye a un ambiente de trabajo positivo.
                                        </li>
                                        <li style="text-align: justify;">
                                            Mayor eficiencia del equipo: colaboradores saludables, mayor productividad.
                                        </li>
                                        <li style="text-align: justify;">
                                            Cultura de bienestar: promover la calidad de vida laboral y el autocuidado.
                                        </li>
                                    </ul>

                                    <p style="color: #002545;"><strong>Importancia de la encuesta en línea a la que nos referimos: </strong></p>
                                    <p style="text-align: justify;">
                                        La información que solicitamos a cada participante en la encuesta es la base de este proceso. Para garantizar la equidad y el respeto hacia cada uno de ellos, la comprensión y consentimiento
                                        voluntario , son fundamentales.
                                    </p>
                                    <p>
                                        A continuación, detallamos algunos puntos importantes:
                                    </p>
                                </tr>
                                <tr>
                                    <td>
                                        <div>
                                            <img src="https://appweb.bclinik.com/img/img_correos/concentimiento.png" style="float: left;" />
                                            <div>
                                                <ul style="padding-left: 40px; margin: 0;">
                                                    <li style="text-align: justify;">
                                                        <span style="color: #002545;">Consentimiento informado:</span> Cada colaborador debe aceptar de manera explícita el <span style="color: #002545;">“consentimiento informado”</span> para
                                                        participar, lo que asegura su comprensión y voluntariedad sin ninguna coacción.
                                                    </li>
                                                    <li style="text-align: justify;">
                                                        <span style="color: #002545;">Participación voluntaria : </span> Su participación es completamente voluntaria y puede ser interrumpida en cualquier momento sin ningún perjuicio.
                                                    </li>
                                                    <li style="text-align: justify;">
                                                            <span style="color: #002545;">Confidencialidad:</span> La identidad de cada colaborador será tratada de manera anónima. La información recopilada será analizada de forma conjunta y utilizada
                                                            para elaborar el informe estadístico del censo metabólico.
                                                        </li>

                                            <li style="text-align: justify;">
                                                <span style="color: #002545;">Acceso a resultados:</span> La información será conservada por cinco años contados desde la entrega de los resultados a la cual podrá también acceder el grupo de
                                                investigación. Cada colaborador recibirá su informe individual. Se enviará a la empresa el informe colectivo con una explicación completa de los resultados estadísticos.
                                            </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </td>
                                </tr>


                            </table>
                            {{-- final del segundo bloque --}} {{-- inicio del tercer bloque --}}
                            <table width="600px" cellpadding="0" cellspacing="0" border="0" align="center" style="color: #595959;" class="container">
                                <tr>
                                    <td>
                                        <style>
                                            ul li {
                                                margin-bottom: 10px;
                                            }
                                        </style>

                                        <ul>

                                            <li style="text-align: justify;"><span style="color: #002545;">Tiempo estimado:</span> La encuesta le tomará aproximadamente 10 minutos.</li>
                                        </ul>

                                        <p style="line-height: 130% !important;" align="justify">
                                            En el Centro de Investigación Metabólica +Bio Clinik estamos comprometidos con ayudar a mejorar la salud y bienestar de todos los colaboradores de su empresa. Si tiene alguna pregunta o necesita
                                            más información, no dude en contactarnos.
                                        </p>
                                        <p>Saludos cordiales,</p>
                                        <p style="font-weight: bold;">
                                            <span style="color: #002545;"> Centro de Investigación Metabólica +Bio Clinik</span>

                                        </p>
                                        <p style="color: #595959">
                                            Agenda tu cita ahora:
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: center;">
                                        <p style="color: #002545; font-weight: bold;">
                                            Heydy Cachutt
                                        </p>
                                        <p>
                                            <span style="color: #595959;">Alianzas</span>
                                        </p>
                                        <img style="width: 60%; height:100px text-align: center; margin: 0 auto;" src="https://appweb.bclinik.com/img/img_correos/cribado02.png" alt="imc" />
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div>
                                            <p style="margin-bottom: 10px;">
                                                <img style="width: 30px;" src="https://appweb.bclinik.com/img/img_correos/whatsapp.png" alt="whatsapp" />
                                                <strong>Whatsapp: <a style="color: #333333;   text-decoration: none;" href="https://wa.link/wcqioe" target="_blank">4785-8946</a></strong>
                                            </p>
                                            <p style="margin-bottom: 10px;">
                                                <img style="width: 30px;" src="https://appweb.bclinik.com/img/img_correos/email.png" alt="email" />
                                                <strong>Email: <a style="color: #333333; text-decoration: none;" href="mailto:heydycachutt@bclinik.com" target="_blank">heydycachutt@bclinik.com</a></strong>
                                            </p>
                                            <p style="margin-bottom: 10px;">
                                                <img style="width: 30px;" src="https://appweb.bclinik.com/img/img_correos/link.png" alt="web" />
                                                <strong><a style="color: #333333; text-decoration: none;" href="https://www.bclinik.com" target="_blank">www.bclinik.com</a></strong>
                                            </p>

                                            <img style="width: 100px;" src="https://appweb.bclinik.com/img/img_correos/qr.jpg" alt="QR Code" style="float: right; margin-top: -150px;" />
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <p><strong>Ubícanos en:</strong></p>
                                        <ul>
                                            <li style="color: #333333;">
                                                <a target="_blank" style="text-decoration: none; color: #333333;" href="https://www.google.com/search?q=bio+clinik+zona+10"> <strong>Edificio 01010 - Zona 10</strong></a>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="text-align: center; padding: 80px;">
                                        <img src="https://appweb.bclinik.com/img/img_correos/footer.png" alt="footer" />
                                    </td>
                                </tr>
                            </table>

                            {{-- final del tercer bloque --}}
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </body>
</html>
