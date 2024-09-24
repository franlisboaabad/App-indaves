<!DOCTYPE html>
<html>

<head>
    <title>REPORTE INGRESOS</title>
    @include('pdf.styles')
</head>

<body>

@include('pdf.partials.header-pdf',['empresa' => $empresa,'title' => 'REPORTE DE INGRESOS'])

<div>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Cliente</th>
            <th>Fecha Venta</th>
            <th>Serie</th>
            <th>Total</th>
            <th>Total Pagado</th>
            <th>Total Pendiente</th>
        </tr>
        </thead>
        <tbody>
        @forelse($records as $item)
            <tr>
                <td class="text-center">{{ $item->cliente_razon_social }}</td>
                <td class="text-center">{{ $item->fecha_venta }}</td>
                <td class="text-center">{{ $item->serie_venta }}</td>
                <td class="text-center">{{ $item->monto_total }}</td>
                <td class="text-center">{{ $item->monto_recibido }}</td>
                <td class="text-center">{{ $item->monto_pendiente }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="4">No hay movimientos</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
