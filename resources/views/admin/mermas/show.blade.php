@extends('adminlte::page')

@section('title', 'Ver Merma')


@section('content_header')
    <h1>Detalle de Merma</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h1 class="card-title">Detalle:</h1>
        </div>

        <div class="card-body">

            <h2>Peso Total: {{ $merma->total_peso }}</h2>

            <div class="detalle-merma mt-5">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tipo Ingreso Producto</th>
                            <th>Presentacion</th>
                            <th>Tipo de Producto</th>
                            <th>Peso Merma</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($merma->detalles as $detalle)
                            <tr>
                                <td> {{ $detalle->id }} </td>
                                <td>{{ ($detalle->tipo_ingreso) ? 'POR CAMION' : 'POR STOCK' }}</td>
                                <td> {{ $detalle->presentacion }} </td>
                                <td> {{ $detalle->tipo }} </td>
                                <td> {{ $detalle->peso }} </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>


            </div>

            <a href="{{ route('mermas.index') }}" class="btn btn-warning">Lista Mermas</a>

        </div>

    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
@stop

@section('js')

@stop
