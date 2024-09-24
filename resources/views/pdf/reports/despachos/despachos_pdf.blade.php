<!DOCTYPE html>
<html>

<head>
    <title>REPORTE DESPACHOS</title>
    @include('pdf.styles')
</head>

<body>

@include('pdf.partials.header-pdf',['empresa' => $empresa,'title' => 'REPORTE DE DESPACHOS'])

<div>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Cliente</th>
            <th>Fecha Despacho</th>
            <th>Serie</th>
            <th>Total Aves</th>
            <th>Peso Bruto</th>
            <th>Peso Tara</th>
            <th>Peso Neto</th>
        </tr>
        </thead>
        <tbody>
        @forelse($records as $item)
            <tr>
                <td class="text-center">{{ $item->cliente_razon_social }}</td>
                <td class="text-center">{{ $item->fecha_despacho }}</td>
                <td class="text-center">{{ $item->serie_orden }}</td>
                <td class="text-center">{{ $item->cantidad_pollos }}</td>
                <td class="text-center">{{ $item->peso_total_bruto }}</td>
                <td class="text-center">{{ $item->tara }}</td>
                <td class="text-center">{{ $item->peso_total_neto }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="4">No hay movimientos</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
