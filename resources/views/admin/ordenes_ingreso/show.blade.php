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

            <h3>Detalles de la Orden</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Tipo de Pollo</th>
                        <th>Presentación</th>
                        <th>Cantidad Pollos</th>
                        {{-- <th>Peso Bruto</th> --}}
                        <th>Cantidad Jabas</th>
                        {{-- <th>Tara</th> --}}
                        <th>Peso Neto</th>
                        {{-- <th>Precio</th>
                        <th>Subtotal</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach($ordenIngreso->detalle as $detalle)
                        <tr>
                            <td>{{ $detalle->tipo_pollo_id }}</td>
                            <td>{{ $detalle->presentacion_pollo_id }}</td>
                            <td>{{ $detalle->cantidad_pollos }}</td>
                            {{-- <td>{{ $detalle->peso_bruto }}</td> --}}
                            <td>{{ $detalle->cantidad_jabas }}</td>
                            {{-- <td>{{ $detalle->tara }}</td> --}}
                            <td>{{ $detalle->peso_neto }}</td>
                            {{-- <td>{{ $detalle->precio }}</td>
                            <td>{{ $detalle->subtotal }}</td> --}}
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('css')

@endsection

@section('js')

@endsection
