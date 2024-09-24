@extends('adminlte::page')

@section('title', 'Detalle Orden de Ingreso')

@section('content_header')
    <h1>Detalle Orden de Ingreso</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h1 class="card-title">Información de la Orden Ingreso</h1>
        </div>
        <div class="card-body">
            <h5>Número de Guía: {{ $ordenIngreso->numero_guia }}</h5>
            <h5>Peso Bruto: {{ $ordenIngreso->peso_bruto }} kg</h5>
            <h5>Peso Tara: {{ $ordenIngreso->peso_tara }} kg</h5>
            <h5>Peso Neto: {{ $ordenIngreso->peso_neto }} kg</h5>
            <h5>Estado:
                @if ($ordenIngreso->estado)
                    <span class="badge badge-success"> Activo </span>
                @endif
            </h5>

            <hr>

            <h3>Detalles:</h3>
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th>Tipo de Pollo</th>
                        <th>Presentación</th>
                        <th>Cantidad Pollos</th>
                        <th>Cantidad Jabas</th>
                        <th>Peso Neto</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ordenIngreso->detalle as $detalle)
                        <tr>
                            <td>{{ $detalle->tipo_pollo->descripcion }}</td>
                            <td>{{ $detalle->presentacion_pollo->descripcion }}</td>
                            <td>{{ $detalle->cantidad_pollos }}</td>
                            <td>{{ $detalle->cantidad_jabas }}</td>
                            <td>{{ $detalle->peso_neto }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td>-</td>
                        <td colspan="1"> <b>TOTALES:</b> </td>
                        <td id="totalPollos">0</td>
                        <td id="totalJabas">0</td>
                        <td id="totalPesoNeto">0</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection

@section('css')

@endsection

@section('js')
<script>
$(document).ready(function() {
    function sumarColumnas() {
        let totalPollos = 0;
        let totalJabas = 0;
        let totalPesoNeto = 0;

        // Iterar sobre cada fila de la tabla (excluyendo el encabezado y el pie de tabla)
        $('.table tbody tr').each(function() {
            const cantidadPollos = parseInt($(this).find('td:nth-child(3)').text()) || 0;
            const cantidadJabas = parseInt($(this).find('td:nth-child(4)').text()) || 0;
            const pesoNeto = parseFloat($(this).find('td:nth-child(5)').text()) || 0;

            totalPollos += cantidadPollos;
            totalJabas += cantidadJabas;
            totalPesoNeto += pesoNeto;
        });

        // Mostrar los totales en el tfoot
        $('#totalPollos').text(totalPollos);
        $('#totalJabas').text(totalJabas);
        $('#totalPesoNeto').text(totalPesoNeto.toFixed(2)); // Limitar a 2 decimales
    }

    // Llamar a la función para sumar al cargar la página
    sumarColumnas();
});
</script>
@endsection
