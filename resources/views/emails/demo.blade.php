<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket de compra - El Triki</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .logo {
            text-align: center;
            margin-bottom: 20px;
        }
        .ticket-info {
            border-top: 2px solid #ddd;
            padding-top: 20px;
            margin-top: 20px;
        }
        .ticket-info h2 {
            font-size: 24px;
            color: #333;
        }
        .ticket-info p {
            font-size: 16px;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="https://lh3.googleusercontent.com/u/0/drive-viewer/AKGpiham8YuJ1K5fzrjepPMJNK5S9S3XghBJENNvJPBmZiU_a24LKckW3DozO5N0174NYTnExomTe6i67bLAFfH83LY13M1wDlVRsI4=w1920-h945" alt="Logo El Triki">
        </div>
        <div class="ticket-info">
            <h2>Ticket de Compra</h2>
            <p>¡Gracias por tu compra en EL TRIKI!</p>
            <p>A continuación encontrarás los detalles de tu compra:</p>
            <ul>
                <li><strong>Fecha de compra:</strong> {{ $ticket->created_at }} </li>
                <li><strong>Producto:</strong> Ticket para el sorteo El Triki </li>
                <li><strong>Cantidad de tickets:</strong> {{ $ticket->cantidad_tickets }} </li>
            </ul>
            <p>Para cualquier duda o aclaración, por favor contáctanos a <a href="mailto:info@eltriki.com.pe">info@eltriki.com.pe</a></p>
        </div>
    </div>
</body>
</html>
