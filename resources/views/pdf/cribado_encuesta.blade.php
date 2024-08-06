<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <style>
        body {
            margin: 10%;
            padding: 0;
        }

        .container {
            page-break-before: always;
        }

        .container:first-of-type {
            page-break-before: auto;
        }

        .img-fluid {
            max-width: 100%;
            height: auto;
        }

        .centered-content {
            display: table;
            margin: 0 auto;
            padding: 20px 0;
            /* Optional: add vertical padding */
        }

        .centered-content img {
            vertical-align: middle;
            display: table-cell;
            padding-right: 10px;
            /* Adds space between image and text */
        }

        .centered-content .text-content {
            display: table-cell;
            vertical-align: middle;
            text-align: left;
            /* Aligns text to the left if needed */
            padding-left: 10px;
            /* Optional: space between image and text */
        }

        .text-content p {
            margin: 0;
            /* Removes default margin */
        }

        .text-center {
            text-align: center;
        }

        .image-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .img-size {
            width: 100%;
            height: auto;
        }

        .custom-button {
            background-color: #002545;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border: none;
            border-radius: 4px;
        }

        .custom-button:hover {
            background-color: #003366;
        }

        .button-container {
            text-align: right;
        }
    </style>
</head>

<body>
    <div class="container">
        <img src="https://appweb.bclinik.com/img/img_correos/header.png" class="img-fluid" alt="header" />
        <p style="text-align: right;">Fecha: {{ date('d-m-Y') }} </p>


        <p style="text-align: left;"><span style="color: #002545;">Hola</span> {{ $nombre }} {{ $apellido }}
        </p>
        <p style="text-align: left;">Gracias por confiarnos tus datos:</p>
        <p style="text-align: left;">Tu <span style="color: #002545;">IMC</span> es de {{ $imc }} que
            corresponde a la categoria {{ $categoria }}.</p>
        <div class="centered-content">
            <img src="https://appweb.bclinik.com/img/img_correos/semaforo.png" alt="semaforo" style="width: 130px" />
            <div class="text-content">
                <p>
                    Por lo que el nivel de riesgo de desarrollar Síndrome Metabólico es:
                </p>





                @if ($imc < 25)
                    <h3 style="color: #76b82a;font-size: 2em;font-weight: bold;">Bajo</h3>
                @elseif ($imc >= 25 && $imc <= 29)
                    <h3 style="color: #d7d436;font-size: 2em;font-weight: bold;">Medio</h3>
                @elseif ($imc >= 30 && $imc <= 39)
                    <h3 style="color: red;font-size: 2em;font-weight: bold;">Alto</h3>
                @else
                    <h3 style="color: red;font-size: 2em;font-weight: bold;">Muy Alto</h3>
                @endif







                <p class="text-center" style="color: #002545;">
                    El índice de masa corporal es la relación de tu peso con tu estatura, es el indicador más confiable
                    para saber si tienes sobrepeso o ya estás en obesidad.
                </p>
            </div>
        </div>
        <div class="image-container">
            <img src="https://appweb.bclinik.com/img/img_correos/imc.png" class="img-size" alt="Imagen">
        </div>
    </div>

    <div class="container">
        <ul>
            <li><span style="color: #f1c232;">25 a 29.99</span> sobrepeso también llamado pre obesidad.</li>
            <li><span style="color: #ff9900;">Entre 30 a 34.99</span> estás a tiempo de retroceder categorías y llegar a
                lo normal.</li>
            <li><span style="color: #ff0000;">Entre 35 a 39.99 </span>estas a tiempo de retroceder categorías y evitar
                el Síndrome Metabólico.</li>
            <li><span style="color: #990000;">Mayor de 40</span> no solo puedes retroceder categorías, además, controlas
                el Síndrome Metabólico que haya aparecido:</li>
        </ul>
        <p style="text-align: center;">(Obesidad, Diabetes, Hipertensión, Colesterol y Triglicéridos altos).</p>
        <p style="color: #002545;">Interpretación de resultados</p>
        <p style="text-align: justify;">La OMS Organización Mundial de la Salud, establece que una definición comúnmente
            en uso con los siguientes valores, acordados en 1997, publicados en 2000 y ajustados en 2010.</p>
        <img src="https://appweb.bclinik.com/img/img_correos/tabla.png" class="img-size" />

        <h3 style="text-align: center; color: #002545;">Hábitos y costumbres</h3>
        <p>El nivel de riesgo de desarrollar Síndrome Metabólico por tus hábitos y costumbres es:</p>

        <div class="centered-content">
            <img src="https://appweb.bclinik.com/img/img_correos/semaforo.png" alt="semaforo" style="width: 130px" />


            <div class="text-content">


                @if ($sumatoria <= 5)
                    <p style="color: red;font-size: 2em;font-weight: bold;">Alto</p>
                    <p class="text-center" style="color: #002545;">
                        ¡Debes cambiar tu Estilo de Vida!
                    </p>
                    <p>
                        Tienes el metabolismo lento
                    </p>
                @elseif ($sumatoria >= 6 && $sumatoria <= 10)
                    <p style="color: #d7d436;font-size: 2em;font-weight: bold;">Medio</p>
                    <p class="text-center" style="color: #002545;">
                        ¡Tu Estilo de Vida puede mejorar!
                    </p>
                    <p>
                        Tienes el metabolismo irregular
                    </p>
                @else
                    <p style="color: #76b82a;font-size: 2em;font-weight: bold;">Bajo</p>
                    <p class="text-center" style="color: #002545;">
                        ¡Tienes un Estilo de Vida Saludable!
                    </p>
                    <p>
                        Tienes el metabolismo acelerado
                    </p>
                @endif






            </div>
        </div>
    </div>

    <div class="container">
        <h2 style="color: #002545; text-align: center;">Pre Diabetes y Diabetes</h2>
        <p>El riesgo de desarrollar Síndrome Metabólico, específicamente Prediabetes y Diabetes por antecedentes
            familiares, factores de riesgo propios y hereditarios es:</p>

        <div class="centered-content">
            <img src="https://appweb.bclinik.com/img/img_correos/semaforo.png" alt="semaforo" style="width: 130px" />
            <div class="text-content">
                <p>Por antecedentes familiares la posibilidad es {{ $antecedentes_familiares }}%</p>
                <p>Por factores de riesgo propios la posibilidad es {{ $factores_riesgo }}%</p>
                <p>Por factores hereditarios la posibilidad es {{ $factores_hereditarios }}%</p>
                <p>La posibilidad total de desarrollar prediabetes es {{ $promedio }}%</p>
            </div>
        </div>
        <p style="text-align: justify">
            Prediabetes significa que tienes un nivel de glucosa sanguínea más alto de lo normal. Aún no es lo
            suficientemente alto como para considerarse diabetes tipo 2, pero si no haces cambios en el estilo de vida,
            tienes una alta probabilidad de desarrollarla. La Prediabetes se cura, la Diabetes no.
        </p>
        <p style="text-align: justify">
            La certeza si estás desarrollando Síndrome Metabólico, prediabetes o ya tienes diabetes es con una prueba de
            sangre por laboratorio. Agenda tu cita para realizar la prueba.
        </p>
        <p style="text-align: justify; font-weight:bold">Si deseas conocer más sobre tu metabolismo, agenda tu cita para
            hacerte tu evaluación personalizada y con los resultados podemos sugerirte 2 opciones:</p>


        <ol>
            <li>Realiza un <span style="color: #002545">Metabograma</span> <br>
                <p style="text-indent: 2em;">
                    Obtén un análisis exhaustivo y personalizado de tu metabolismo. Incluye historial médico,
                    composición corporal, antropometría y pruebas de laboratorio avanzadas, todo para una visión
                    completa de tu salud.
                </p>
            </li>
            <li>Únete al <span style="color: #002545">Programa Aceleración Metabólica PAM</span>
                <p style="text-indent: 2em;">
                    Participa en un programa específico diseñado para acelerar tu metabolismo y reducir grasa-peso de
                    manera permanente y sostenible.
                </p>
            </li>
        </ol>

    </div>


    <div class="container">
        <p style="margin-bottom: 10px;">
            <img style="width: 30px;" src="https://appweb.bclinik.com/img/img_correos/whatsapp.png" alt="whatsapp" />
            <strong>Whatsapp: <a style="color: #333333;   text-decoration: none;" href="https://wa.link/9jb7ih"
                    target="_blank">3324-3501</a></strong>
        </p>

        <p><strong>Ubícanos en:</strong></p>
        <ul>
            <li style="color: #333333">
                <p style="color: #333333">
                    <a target="_blank" style="text-decoration: none; color: #333333" 	href="https://www.google.com/search?q=%2BBio+Clinik+Estaci%C3%B3n+Metab%C3%B3lica+01010&rlz=1C1ALOY_esGT1034GT1034&oq=%2BBio+Clinik+Estaci%C3%B3n+Metab%C3%B3lica+01010&gs_lcrp=EgZjaHJvbWUyBggAEEUYOdIBBzgyOWowajmoAgCwAgE&sourceid=chrome&ie=UTF-8">

                            <stron>Edificio 01010 - Zona 10</stron></a
                    >
                </p>
            </li>
        </ul>
        <img src="https://appweb.bclinik.com/img/img_correos/footer.png" alt="footer" />

    </div>
</body>

</html>
