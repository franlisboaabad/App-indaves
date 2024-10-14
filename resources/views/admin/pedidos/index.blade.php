@extends('adminlte::page')

@section('title', 'Pedidos')
@section('plugins.Datatables', true)
@section('content_header')
    <h1>Pedidos</h1>
@stop

@section('content')

<a href="{{ route('pedidos.create') }}" class="btn btn-primary mb-3">Nuevo Pedido</a>

    <div class="card">
        <div class="card-header">
            <h1 class="card-title">Lista de pedidos</h1>
        </div>
        <div class="card-body">


            <table class="table" id="table-pedidos">
                <thead>
                    <th>#</th>
                    <th>Fecha de pedido</th>
                    <th>Total Presa</th>
                    <th>Total Brasa</th>
                    <th>Total Tipo</th>
                    <th>Acciones</th>
                </thead>
                <tbody>
                    @foreach ($pedidos as $pedido)
                        <tr>
                            <td>{{ $pedido->id }}</td>
                            <td>{{ $pedido->fecha_pedido }}</td>
                            <td>{{ $pedido->total_presa }}</td>
                            <td>{{ $pedido->total_brasa }}</td>
                            <td>{{ $pedido->total_tipo }}</td>
                            <td>

                                <div class="btn-group">
                                    <button type="button" class="btn btn-danger btn-sm dropdown-toggle"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Acciones
                                    </button>
                                    <div class="dropdown-menu">
                                        <a href="{{ route('pedidos.print',  ['id' => $pedido->id, 'format' => 'a4'] ) }}" class="dropdown-item" target="_Blank" > PDF </a>

                                        @can('admin.pedidos.edit')
                                            <a href="{{ route('pedidos.edit', $pedido) }}"
                                            class="dropdown-item">Editar</a>
                                        @endcan

                                        <form action="" method="POST">

                                            @can('admin.pedidos.destroy')
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" id="btn_Delete" class="dropdown-item"
                                                    data-url="{{ route('pedidos.destroy', $pedido) }}">Eliminar</button>
                                            @endcan
                                        </form>

                                    </div>
                                </div>


                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>


        </div>
    </div>
@stop

@section('css')

@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $('#table-pedidos').DataTable();
        });
    </script>
@stop
