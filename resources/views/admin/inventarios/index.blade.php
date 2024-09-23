@extends('adminlte::page')

@section('title', 'Inventario')
@section('plugins.Datatables', true)
@section('content_header')
    <h1>Inventario</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <table id="inventoriesTable" class="table table-bordered">
                <thead>
                <tr>
                    <th>ID
                    <th>Presentación</th>
                    <th>Tipo</th>
                    <th>Cantidad Pollos</th>
                    <th>Total Peso</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($inventarios as $inventory)
                    <tr>
                        <td>{{ $inventory->id }}</td>
                        <td>{{ $inventory->presentacion_pollo_descripcion }}</td>
                        <td>{{ $inventory->tipo_pollo_descripcion }}</td>
                        <td>{{ $inventory->total_pollos }}</td>
                        <td>{{ $inventory->total_peso }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
@stop

@section('js')
    <script>
        $(function () {
            $("#inventoriesTable").DataTable({
                "responsive": true,
                "autoWidth": false,
                language: {
                    "url": "/js/spanish.json"
                },
            });
        });
    </script>
@stop
