<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tickets generado | EL TRIKI</title>
    <style>
        /* Estilos para ECS/80 */
        @page {
            size: 80mm 150mm;
            margin: 0;
            padding: 0px;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            text-align: center;
            width: 80mm;
            height: 80mm;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        div {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        img {
            width: 50mm;
            height: 50mm;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    {{-- <h1>{{ $titulo }}</h1>
    <img src="{{ $qrCode }}" alt=""> --}}
    <h1>RUC: 20612574911</h1>
    <p>Tickets de compra:</p>
    <p>Valor Abonado:</p>
    <p>Cantidad de Tickets: {{ $tickets }} </p>

    <h2>SORTEO</h2>
    <p></p>
    <p>Trasmision en vivo</p>
    <h3>EL TRIKI 33 GANADORES SIEMPRE</h3>
    <p>He leido y aceptado TODOS los terminos y condiciones del TRIKI Inversiones SAC contenidos en www.eltriki.pe </p>

</body>
</html>
