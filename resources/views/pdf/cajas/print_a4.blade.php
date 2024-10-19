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
        @php
            $totalCantidadPollos = 0;
            $totalPesoNeto = 0;
            $totalPromedio = 0;
            $totalPrecio = 0;
            $totalTotalVenta = 0;
            $totalSaldo = 0;
            $total = 0;
            $totalMontoPagado = 0;
            $totalPendiente = 0;
        @endphp
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
            @php
                $totalCantidadPollos  += $pago->cantidad_pollos;
                $totalPesoNeto  += $pago->peso_total_neto;
                $totalPromedio  += $pago->promedio;
                $totalPrecio  += $pago->precio;
                $totalTotalVenta  += $pago->total_venta;
                $totalSaldo  += $pago->saldo;
                $total  += $pago->total;
                $totalMontoPagado  += $pago->monto_pagado;
                $totalPendiente  += $pago->pendiente;
            @endphp
        @empty
            <tr>
                <td colspan="4">No hay pagos registrados para esta caja.</td>
            </tr>
        @endforelse
        <tr>
            <td class="text-center">TOTALES</td>
            <td class="text-center" style="background: #9ed0f3;">{{$totalCantidadPollos}}</td>
            <td class="text-center" style="background: #9ed0f3;">{{$totalPesoNeto}}</td>
            <td class="text-center" style="background: #9ed0f3;">{{$totalPromedio}}</td>
            <td class="text-center" style="background: #9ed0f3;">{{$totalPrecio}}</td>
            <td class="text-center" style="background: #9ed0f3;">{{$totalTotalVenta}}</td>
            <td class="text-center" style="background: #9ed0f3;">{{$totalSaldo}}</td>
            <td class="text-center" style="background: #9ed0f3;">{{$total}}</td>
            <td class="text-center" style="background: #9ed0f3;">{{$totalMontoPagado}}</td>
            <td class="text-center" style="background: #9ed0f3;">{{$totalPendiente}}</td>
        </tr>
        </tbody>
    </table>
</div>


</body>

</html>
