<!DOCTYPE html>
<html dir="ltr" lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invitación</title>

    <style>
        body {

            margin: 0;
            padding: 0;
            background-color: #ffffff;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            color: #595959;
            padding: 10px;
        }
        .header img, .footer img {
            width: 100%;
            max-width: 600px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .content img {
            width: 90%;
            max-width: 540px;
        }
        p {
            margin: 0;
            padding: 10px 0;
        }
        a {
            color: #11455d;
            text-decoration: none;
        }
        .list-item {
            padding: 5px 0;
        }
        .highlight-yellow { color: #F1C232; }
        .highlight-orange { color: #FF9900; }
        .highlight-red { color: #FF0000; }
        .highlight-darkred { color: #990000; }
    </style>
</head>
<body class="body">
    <div class="container">
        <div class="header">
            <img src="https://appweb.bclinik.com/img/img_correos/header.png" alt="header">
        </div>
        <div class="text-right">
            <p>Fecha: {{ $date }}</p>
        </div>
        <div class="content">
            <p>Hola {{ $nombre_invitado }} {{ $apellido_invitado }}</p>
            <p>Tu amigo {{ $nombre }} {{ $apellido }}, nos ha solicitado que te enviemos esta información.</p>
            <p class="text-center" style="color:#11455d;">¿Quieres saber si tu peso es adecuado para tu edad?</p>
            <p class="text-center" style="color:#11455d;">¿Si tu metabolismo está lento?</p>
            <p class="text-center" style="color:#11455d;">¿Cuál es la causa principal por la que subes de peso?</p>
            <p class="text-center" style="color:#11455d;">¿Qué es lo que no te deja bajar de peso?</p>
            <p class="text-center">Averígualo ingresa a <a href="https://bclinik.com/imc/">www.bclinik.com/imc</a></p>
            <div class="text-center">
                <img src="https://appweb.bclinik.com/img/img_correos/imc.png" alt="imc">
            </div>
            <ul style="list-style-type: none; padding: 0;">
                <li class="list-item"><em><strong class="highlight-yellow">Entre 25 a 29.99</strong> es sobrepeso también llamado <span style="color:#11455d;">pre obesidad.</span></em></li>
                <li class="list-item"><em><strong class="highlight-orange">Entre 30 a 34.99</strong> estás a tiempo de retroceder categorías y llegar a lo normal.</em></li>
                <li class="list-item"><em><strong class="highlight-red">Entre 35 a 39.99</strong> estás a tiempo de retroceder categorías y evitar el Síndrome Metabólico.</em></li>
                <li class="list-item"><em><strong class="highlight-darkred">Mayor de 40</strong> no solo puedes retroceder categorías, además, controlas el Síndrome Metabólico que haya aparecido: <br>(Obesidad, Diabetes, Hipertensión, Colesterol y Triglicéridos altos).</em></li>
            </ul>
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
        </div>
        <div class="footer">
            <img src="https://appweb.bclinik.com/img/img_correos/footer.png" alt="footer">
        </div>
    </div>
</body>
</html>
