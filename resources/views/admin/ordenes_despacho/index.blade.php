@extends('adminlte::page')

@section('title', 'Ordenes de despacho')
@section('plugins.Datatables', true)
@section('content_header')
    <h1>Lista de Ordenes</h1>
@stop

@section('content')

    <div class="card">
        <div class="card-body">


            <table class="table table-bordered" id="table-clientes">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Cliente</th>
                        <th>Serie Orden</th>
                        <th>Fecha Despacho</th>
                        <th>Peso Total Bruto</th>
                        <th>Cantidad Jabas</th>
                        <th>Tara</th>
                        <th>Peso Total Neto</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ordenes as $orden)
                        <tr>
                            <td>{{ $orden->id }}</td>
                            <td>{{ $orden->cliente->razon_social }}</td>
                            <td>{{ $orden->serie_orden }}</td>
                            <td>{{ \Carbon\Carbon::parse($orden->fecha_despacho)->format('d/m/Y') }}</td>
                            <td>{{ number_format($orden->peso_total_bruto, 2) }}</td>
                            <td>{{ $orden->cantidad_jabas }}</td>
                            <td>{{ number_format($orden->tara, 2) }}</td>
                            <td>{{ number_format($orden->peso_total_neto, 2) }}</td>
                            <td>
                                @if ($orden->estado_despacho)
                                    <span class="badge badge-success">Despachado</span>
                                @else
                                <span class="badge badge-warning">Por Despachar</span>
                                @endif
                            </td>

                            <td>
                                <!-- Aquí puedes añadir botones para ver, editar o eliminar -->
                                <a href="{{ route('ordenes-de-despacho.show', $orden->id) }}" class="btn btn-info btn-sm">Ver</a>
                                <a href="{{ route('ordenes-de-despacho.edit', $orden->id) }}" class="btn btn-warning btn-sm">Editar</a>
                                <form action="{{ route('ordenes-de-despacho.destroy', $orden->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>



@stop

@section('css')
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {

            $('#table-clientes').DataTable();

        });
    </script>
@stop
