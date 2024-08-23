@extends('adminlte::page')

@section('title', 'Lista de Ventas')
@section('plugins.Datatables', true)
@section('content_header')
    <h1>Listado de Ventas</h1>
@stop

@section('content')

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered" id="table-ventas">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Serie Venta</th>
                        <th>Fecha Venta</th>
                        <th>Peso Neto</th>
                        <th>Forma de Pago</th>
                        <th>Monto Total</th>
                        <th>Monto Recibido</th>
                        <th>Saldo</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ventas as $venta)
                        <tr>
                            <td>{{ $venta->id }}</td>
                            <td>{{ $venta->serie_venta }}</td>
                            {{-- <td>{{ \Carbon\Carbon::parse($venta->fecha_venta)->format('d/m/Y') }}</td> --}}
                            <td>{{ $venta->fecha_venta }}</td>
                            <td>{{ $venta->peso_neto }}</td>
                            <td>{{ $venta->forma_de_pago }}</td>
                            <td>{{ number_format($venta->monto_total, 2) }}</td>
                            <td>{{ number_format($venta->monto_recibido, 2) }}</td>
                            <td>{{ number_format($venta->saldo, 2) }}</td>
                            <td>{{ $venta->estado }}</td>
                            <td>
                                <a href="{{ route('ventas.show', $venta->id) }}" class="btn btn-info btn-sm">Ver</a>
                                <a href="{{ route('ventas.edit', $venta->id) }}" class="btn btn-warning btn-sm">Editar</a>
                                <form action="{{ route('ventas.destroy', $venta->id) }}" method="POST" style="display:inline;">
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
            $('#table-ventas').DataTable({
                // Opciones de DataTables, como la longitud de la página, la búsqueda, etc.

            });
        });
    </script>
@stop
