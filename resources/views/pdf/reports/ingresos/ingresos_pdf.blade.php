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
            <th>Usuario Registro</th>
            <th>Fecha Ingreso</th>
            <th>Número de Guía</th>
            <th>Total Aves</th>
            <th>Peso Bruto</th>
            <th>Peso Tara</th>
            <th>Peso Neto</th>
        </tr>
        </thead>
        <tbody>
        @forelse($records as $item)
            <tr>
                <td class="text-center">{{ $item->user_name }}</td>
                <td class="text-center">{{ $item->fecha_ingreso }}</td>
                <td class="text-center">{{ $item->numero_guia }}</td>
                <td class="text-center">{{ $item->detalle_sum_cantidad_pollos }}</td>
                <td class="text-center">{{ $item->peso_bruto }}</td>
                <td class="text-center">{{ $item->peso_tara }}</td>
                <td class="text-center">{{ $item->peso_neto }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="4">No hay movimientos</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
