@extends('adminlte::page')

@section('title', 'Lista de sorteos | EL TRIKI')

@section('content_header')
    <h1>Lista de sorteos</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <a href="{{ route('sorteos.create') }}" class="btn btn-primary btn-xs">Nuevo Sorteo</a>
            <hr>

            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre de sorteo</th>
                        <th>Cantidad opciones</th>
                        <th>Fecha de registro</th>
                        <th>Fecha de sorteo</th>
                        <th>Premios</th>
                        <th>Tickets vendidos</th>
                        {{-- <th>Descripcion</th> --}}
                        <th>Estado</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($sorteos as $sorteo)
                        <tr>
                            <td>{{ $sorteo->id }}</td>
                            <td>{{ $sorteo->nombre_sorteo }}</td>
                            <td>{{ $sorteo->opciones }}</td>
                            <td>{{ $sorteo->created_at }}</td>
                            <td>{{ $sorteo->fecha_de_sorteo }}</td>
                            <td>{{ $sorteo->premios }}</td>
                            <td>{{ $sorteo->cantidad_vendida }}</td>
                            {{-- <td>{{ $sorteo->descripcion_del_sorteo }}</td> --}}
                            <td>
                                @if ($sorteo->estado)
                                    <span class="badge badge-success">Activo</span>
                                @else
                                    <span class="badge badge-danger">Inactivo</span>
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('sorteos.destroy', $sorteo ) }}" method="POST">
                                    @csrf
                                    @method('DELETE')

                                    @if ($sorteo->estado === 1)
                                        <a href="{{ route('sorteos.edit', $sorteo ) }}" class="btn btn-xs btn-warning">Editar</a>
                                        <button type="submit" class="btn btn-xs btn-danger" onclick="return confirm('¿Esta seguro de desactivar el sorteo?')">Desactivar</button>
                                    @else

                                    @endif


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

@stop

@section('js')

@stop
