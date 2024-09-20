<!DOCTYPE html>
<html>

<head>
    <title> RESUMEN | INDAVES</title>
    @include('pdf.styles')
</head>

<body>

@include('pdf.partials.header-pdf',['empresa' => $empresa,'title' => 'RESUMEN DIARIO'])


<div>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>CLIENTE</th>
            <th>UNIDAD</th>
            <th>KG</th>
            <th>PROM</th>
            <th>PRECIO</th>
            <th>VTA. DIA</th>
            <th>SALDO ANTERIOR</th>
            <th>TOTAL</th>
            <th>ABONO</th>
            <th>SALDO A COBRAR</th>
        </tr>
        </thead>
        <tbody>
        @forelse($reports as $pago)
            <tr>
                <td>{{ $pago->cliente }}</td>
                <td class="text-center">{{ $pago->cantidad_pollos }}</td>
                <td class="text-center">{{ $pago->peso_total_neto }}</td>
                <td class="text-center">{{ $pago->promedio }}</td>
                <td class="text-center">{{ $pago->precio }}</td>
                <td class="text-center">{{ $pago->total_venta }}</td>
                <td class="text-center">{{ $pago->saldo }}</td>
                <td class="text-center">{{ $pago->total }}</td>
                <td class="text-center">{{ $pago->monto_pagado }}</td>
                <td class="text-center">{{ $pago->pendiente }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="4">No hay pagos registrados para esta caja.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>


</body>

</html>
