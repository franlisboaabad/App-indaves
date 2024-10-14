<!DOCTYPE html>
<html>

<head>
    <title> PEDIDO  | PIURA AVES </title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 14px;
            margin: 0;
            padding: 0;
            color: #333; /* Color de texto principal */
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #007bff; /* Color del borde inferior de la cabecera */
            padding-bottom: 10px;
            /* background-color: #f8f9fa;  */
            /* Color de fondo de la cabecera */
        }

        .header img {
            width: 100px;
        }

        .header h1 {
            margin: 5px 0;
            font-size: 18px;
            color: #007bff; /* Color del título de la cabecera */
        }

        .header p {
            margin: 2px 0;
            color: #555; /* Color del texto en la cabecera */
        }

        h2 {
            border-bottom: 1px solid #007bff; /* Color del borde inferior de los encabezados h2 */
            padding-bottom: 5px;
            margin-bottom: 10px;
            font-size: 14px;
            color: #007bff; /* Color del texto de los encabezados h2 */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 14px;
        }

        table, th, td {
            border: 1px solid #dee2e6; /* Color del borde de la tabla */
        }

        th, td {
            padding: 8px;
            text-align: left;
        }

        th {
            /* background-color: #e9ecef;  */
            /* Color de fondo de los encabezados de la tabla */
            color: #333;
            /* Color del texto en los encabezados de la tabla */
        }

        tr:nth-child(even) {
            /* background-color: #f2f2f2;  */
            /* Color de fondo alternativo para las filas de la tabla */
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            border-top: 2px solid #007bff; /* Color del borde superior del pie de página */
            padding-top: 10px;
            /* background-color: #f8f9fa;  */
            /* Color de fondo del pie de página */
        }

        .footer h3 {
            margin: 0;
            font-size: 14px;
            color: #e74c3c; /* Color del título en el pie de página */
        }

        .footer p {
            margin: 5px 0;
            /* color: #555;  */
            /* Color del texto en el pie de página */
        }
    </style>
</head>

<body>
    <!-- Cabecera -->
    <div class="header">
        <img src="{{ $empresa->imagen_url }}" alt="Logo" class="img-fluid">
        <h1>{{ $empresa->name }}</h1>
        <p>{{ $empresa->address }}</p>
        <p>Pedidos: {{ $empresa->phone }}</p>
        <p>{{ $empresa->email }}</p>
    </div>

    <!-- Información de la Venta -->
    <div>
        <h2>LISTA DE PEDIDOS | FECHA :  {{ \Carbon\Carbon::parse($pedido->fecha_pedido)->format('d \d\e F \d\e Y') }} </h2>
    </div>


    <!-- Detalles de la Orden -->
    <div>
        <h2>DETALLE DEL PEDIDO</h2>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Cliente</th>
                    <th># Tipo Presa (Macho)</th>
                    <th># Tipo Brasa (Hembra)</th>
                    <th># Tipo (Especial)</th>

                </tr>
            </thead>
            <tbody>
                @foreach ($pedido->detalles as $detalle)
                    <tr>
                        <td> {{  $detalle->id }} </td>
                        <td>{{ $detalle->cliente->razon_social }} </td>
                        <td> {{ $detalle->cantidad_presa }} </td>
                        <td> {{ $detalle->cantidad_brasa }} </td>
                        <td> {{ ($detalle->cantidad_tipo) ? $detalle->cantidad_tipo : 0  }} </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="2">TOTALES</th>
                    <th>{{ $pedido->total_presa }}</th>
                    <th>{{ $pedido->total_brasa }}</th>
                    <th>{{ $pedido->total_tipo }}</th>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Footer -->
    <div class="footer">
        <h3>LA EMPRESA NO ACEPTA DEVOLUCIONES</h3>
        <p>INDAVES - Gracias por su preferencia.</p>
    </div>
</body>

</html>
