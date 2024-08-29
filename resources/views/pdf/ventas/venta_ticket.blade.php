<!DOCTYPE html>
<html>

<head>
    <title> Venta Ticket | INDAVES</title>
    <style>
        /* Estilos para impresión en impresora 80x80mm */
        @page {
            size: 80mm 150mm; /* Tamaño de la página */
            margin: 0; /* Sin márgenes */
        }

        @media print {
            body {
                font-family: Consolas, monospace; /* Cambiar a la fuente Consolas */
                font-size: 5px; /* Tamaño de fuente reducido para caber en una página pequeña */
                margin: 0;
                padding: 0;
                width: 80mm; /* Asegura que el contenido se ajuste al ancho de la página */
                height: 150mm; /* Asegura que el contenido se ajuste a la altura de la página */
                overflow: hidden; /* Evita que el contenido se desborde */
            }

            .header, .footer {
                text-align: center;
                margin: 0;
                padding: 0;
            }

            .header img {
                width: 60px; /* Ajusta el tamaño del logo para que se ajuste bien */
                height: auto; /* Mantiene la proporción */
            }

            .header h1 {
                font-size: 10px;
                margin: 2px 0;
                padding: 0;
            }

            .header p {
                margin: 2px 0;
                font-size: 6px; /* Tamaño de fuente más pequeño */
            }

            h2 {
                font-size: 8px;
                margin: 4px 0;
                padding: 0;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                font-size: 7px; /* Tamaño de fuente reducido para la tabla */
                margin: 0;
            }

            table, th, td {
                border: 1px solid black; /* Borde visible para la tabla */
            }

            th, td {
                padding: 2px; /* Reducir padding para ajustar mejor */
                text-align: center; /* Centra el texto */
            }

            th {
                background-color: #f2f2f2;
                font-weight: bold;
            }

            .footer h3 {
                font-size: 8px;
                margin: 4px 0;
                padding: 0;
            }

            .footer p {
                font-size: 6px;
                margin: 2px 0;
            }
        }
    </style>
</head>

<body>
    <!-- Cabecera -->
    <div class="header">
        <img src="{{ public_path('logo.png') }}" alt="Logo">
        <h1>{{ $empresa->name }}</h1>
        <p>{{ $empresa->address }}</p>
        <p>Pedidos: {{ $empresa->phone }}</p>
        <p>{{ $empresa->email }}</p>
    </div>

    <!-- Información de la Venta -->
    <div>
        <h2>Información de la Venta</h2>
        <p><strong>ID de Venta:</strong> {{ $venta->serie_venta }}</p>
        <p><strong>Cliente:</strong> {{ $venta->ordenDespacho->cliente->razon_social }}</p>
        <p><strong>Fecha:</strong> {{ $venta->created_at->format('d/m/Y') }}</p>
        <p><strong>Forma de pago:</strong> {{ $venta->forma_de_pago ? 'Crédito' : 'Contado' }}</p>
        <p><strong>Monto a pagar:</strong> {{ number_format($venta->monto_total, 2) }}</p>
        <p><strong>Recibido:</strong> {{ number_format($venta->monto_recibido, 2) }}</p>
        <p><strong>Saldo:</strong> {{ number_format($venta->saldo, 2) }}</p>
    </div>

    <!-- Información de la Orden -->
    <div>
        <h2>Orden de Despacho</h2>
        <p><strong>Serie de Orden:</strong> {{ $orden->serie_orden }}</p>
        <p><strong>Sr(a):</strong> {{ $orden->cliente->razon_social }}</p>
        <p><strong>Fecha de Despacho:</strong> {{ \Carbon\Carbon::parse($orden->fecha_despacho)->format('d/m/Y') }}</p>
        <p><strong>Presentación:</strong> {{ $orden->presentacion_pollo ? 'Pollo Beneficiado' : 'Pollo Vivo' }}</p>
    </div>

    <!-- Detalles de la Orden -->
    <div>
        <h2>Detalles de la Orden</h2>
        <table>
            <thead>
                <tr>
                    <th>Cantidad</th>
                    <th>Peso Bruto</th>
                    <th>Cantidad Jabas</th>
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
