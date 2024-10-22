@extends('adminlte::page')

@section('title', 'Mermas')


@section('content_header')
    <h1>Mermas</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h1 class="card-title">Lista de Mermas</h1>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Fecha Registro</th>
                        <th>Total KG. Merma</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($mermas as $merma)
                        <tr>
                            <td>{{ $merma->id }}</td>
                            <td>{{ $merma->created_at }}</td>
                            <td>{{ $merma->total_peso }}</td>
                            <td>{{ ($merma->estado) ? 'Activo' : 'Inactivo' }}</td>
                            <td><a href="{{ route('mermas.show', $merma) }}" class="btn btn-info btn-sm"> Detalle Mermas</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
@stop

@section('js')

@stop
