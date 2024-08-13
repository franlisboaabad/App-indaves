<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket</title>
    <!-- Importación de la fuente Open Sans desde Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            text-align: center;
            margin: 0;
            padding: 0;
            background-color: #f2f2f2;
            /* Color de fondo para el cuerpo */
            font-size: 14px;
            /* Tamaño de fuente general */
        }

        .ticket {
            max-width: 500px;
            /* Reducir el ancho máximo del ticket */
            margin: 20px auto;
            padding: 20px;
            border: 8px solid #ffda33;
            /* Reducir el grosor del borde */
            border-radius: 15px;
            /* Reducir el radio de borde */
            background-color: #fff;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
        }

        .logo img {
            max-width: 100%;
        }

        .ruc {
            margin-top: 8px;
            font-size: 16px;
            /* Tamaño de fuente para el RUC */
        }

        .ticket-compra {
            border: 3px solid #ffda33;
            /* Reducir el grosor del borde */
            border-radius: 10px;
            padding: 8px;
            margin-top: 15px;
            font-size: 12px;
            /* Tamaño de fuente para la sección de compra */
        }

        .valor-abonado {
            border: 3px solid #ffda33;
            /* Reducir el grosor del borde */
            border-radius: 10px;
            padding: 8px;
            margin-top: 15px;
            font-size: 16px;
            /* Tamaño de fuente para el valor abonado */
        }

        .fecha-compra {
            margin-top: 15px;
            font-size: 12px;
            /* Tamaño de fuente para la fecha de compra */
        }

        .sorteo {
            margin-top: 15px;
        }

        .sorteo h1 {
            color: red;
            font-weight: bold;
            font-size: 28px;
            /* Reducir el tamaño de fuente para el título del sorteo */
            margin: 0;
        }

        .fecha {
            border: 3px solid #ffda33;
            /* Reducir el grosor del borde */
            border-radius: 10px;
            padding: 8px;
            margin-top: 8px;
            font-size: 16px;
            /* Tamaño de fuente para la fecha del sorteo */
        }

        .trasmision {
            margin-top: 15px;
            font-size: 12px;
            /* Tamaño de fuente para la transmisión */
        }

        .footer {
            margin-top: 15px;
        }

        .footer h2 {
            font-size: 16px;
            /* Tamaño de fuente para el título del footer */
        }

        .footer p {
            font-size: 10px;
            /* Tamaño de fuente para el texto del footer */
            line-height: 1.4;
            /* Ajustar el interlineado del texto del footer */
        }

        .ticket-compra ul {
            list-style-type: none;
            /* margin: 0;
      padding: 0; */
            overflow: hidden;
        }

        .ticket-compra li {
            float: left;
        }
    </style>
</head>

<body>
    @php
        use Carbon\Carbon;
    @endphp
    <div class="ticket">
        <div class="logo">
            <img src="https://lh3.googleusercontent.com/u/0/drive-viewer/AKGpiham8YuJ1K5fzrjepPMJNK5S9S3XghBJENNvJPBmZiU_a24LKckW3DozO5N0174NYTnExomTe6i67bLAFfH83LY13M1wDlVRsI4=w1920-h945"
                alt="Logo El Triki">
        </div>

        <div class="ruc">
            <h1>RUC: 20612574911</h1>
        </div>

        <div class="ticket-compra">
            <p><b>N* Ticket compra:</b>
                @foreach ($detalles as $det)
                    {{ $det->correlativo_ticket }} ,
                @endforeach
            </p>
        </div>

        <div class="valor-abonado">
            <p><b>Valor Abonado: </b>S/ {{ $registro->monto }}.00 | <b>Tickets:</b> {{ $ticket->cantidad_tickets }} </p>
        </div>

        <div class="fecha-compra">
            <p><b>FECHA DE COMPRA:</b> {{ Carbon::parse($registro->created_at)->format('d-m-Y | h:i:s A') }} </p>
        </div>

        <div class="sorteo">
            <h1>SORTEO</h1>
            <div class="fecha">
                <p><b>Fecha y Hora: {{ Carbon::parse($sorteo->fecha_de_sorteo)->format('d-m-Y | h:i:s A') }} </b></p>
            </div>
        </div>

        <div class="trasmision">
            <p><b>Trasmitido en vivo por: </b> Facebook | Facebook Live | El Triki </p>
        </div>

        <!-- footer -->
        <div class="footer">
            <h2>EL TRIKI 33 GANADORES SIEMPRE</h2>
            <p>
                He leido y aceptado todos los Terminos y condiciones <br />
                del TRIKI Inversiones SAC contenidos en <br />
                eltriki.com.pe
            </p>
        </div>
    </div>
</body>

</html>
