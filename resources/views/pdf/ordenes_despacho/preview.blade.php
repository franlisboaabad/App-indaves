<!DOCTYPE html>
<html>

<head>
    <title>Orden de Despacho | INDAVES</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header img {
            width: 120px;
            height: auto;
        }

        .header h1 {
            margin: 10px 0;
            font-size: 28px;
            color: #0056b3;
        }

        .header p {
            margin: 5px 0;
            font-size: 14px;
        }

        .header .company-details {
            font-size: 12px;
            color: #555;
        }

        h2 {
            border-bottom: 2px solid #0056b3;
            padding-bottom: 5px;
            color: #0056b3;
            font-size: 22px;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
            color: #333;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #777;
        }

        .footer h3 {
            margin: 10px 0;
            font-size: 16px;
            color: #e74c3c;
        }

        .footer p {
            margin: 5px 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Cabecera -->
        <div class="header">
            <img src="{{ public_path('logo.png') }}" alt="Logo">
            <h1>{{ $empresa->name }}</h1>
            <p class="company-details">{{ $empresa->address }}</p>
            <p class="company-details">Pedidos: {{ $empresa->phone }} | {{ $empresa->email }}</p>
        </div>

        <!-- Información de la Orden -->
        <div>
            <h2>Orden de Despacho</h2>
            <p><strong>Serie de Orden:</strong> {{ $orden->serie_orden }}</p>
            <p><strong>Cliente:</strong> {{ $orden->cliente->razon_social }}</p>
            <p><strong>Fecha de Despacho:</strong> {{ \Carbon\Carbon::parse($orden->fecha_despacho)->format('d/m/Y') }}</p>
        </div>

        <!-- Detalles de la Orden -->
        <div>
            <h2>Detalles de la Orden</h2>
            <table>
                <thead>
                    <tr>
                        <th>Cantidad de Pollos</th>
                        <th>Peso Bruto (kg)</th>
                        <th>Cantidad de Jabas</th>
                        <th>Tara (kg)</th>
                        <th>Peso Neto (kg)</th>
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
    </div>
</body>

</html>
