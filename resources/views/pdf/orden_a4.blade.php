<!DOCTYPE html>
<html>

<head>
    <title>Orden de Despacho | INDAVES</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header img {
            width: 100px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .header p {
            margin: 5px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <!-- Cabecera -->
    <div class="header">
        <img src="{{ public_path('logo.png') }}" alt="Logo">
        <h1>{{ $empresa->name }}</h1>
        <p>{{ $empresa->address }}</p>
        <p>Pedidos: {{ $empresa->phone }} </p>
        <p>{{ $empresa->email }}</p>
    </div>

    <!-- Información de la Orden -->
    <div>
        <h2>Orden de Despacho</h2>
        <p><strong>Serie de Orden:</strong> {{ $orden->serie_orden }}</p>
        <p><strong>Sr(a):</strong> {{ $orden->cliente->razon_social }}</p>
        <p><strong>Fecha de Despacho:</strong> {{ \Carbon\Carbon::parse($orden->fecha_despacho)->format('d/m/Y') }}</p>
    </div>

    <!-- Detalles de la Orden -->
    <div>
        <h2>Detalles de la Orden</h2>
        <table>
            <thead>
                <tr>
                    <th>Cantidad de Pollos</th>
                    <th>Peso Bruto</th>
                    <th>Cantidad de Jabas</th>
                    <th>Tara</th>
                    <th>Peso Neto</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orden->detalles as $detalle)
                    <tr>
                        <td>{{ $detalle->cantidad_pollos }}</td>
                        <td>{{ number_format($detalle->peso_bruto, 2) }}</td>
                        <td>{{ $detalle->cantidad_jabas }}</td>
                        <td>{{ number_format($detalle->tara, 2) }}</td>
                        <td>{{ number_format($detalle->peso_neto, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th>Total</th>
                    <th>{{ number_format($orden->peso_total_bruto, 2) }}</th>
                    <th>{{ $orden->cantidad_jabas }}</th>
                    <th>{{ number_format($orden->tara, 2) }}</th>
                    <th>{{ number_format($orden->peso_total_neto, 2) }}</th>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Footer -->
    <div class="footer">
        <h3>La empresa no acepta devoluciones</h3>
        <p>Gracias por su preferencia.</p>
    </div>
</body>

</html>
