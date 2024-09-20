@extends('adminlte::page')

@section('title', 'Lista de roles')

@section('content_header')
    <h1>Lista de Roles & Permisos</h1>
@stop

@section('content')


    <a href="{{ route('roles.create') }}" class="btn btn-primary mb-3">Nuevo Rol</a>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Roles</h3>
        </div>
        <div class="card-body">

            <table class="table table-bordered">
                <thead>
                    <th>#</th>
                    <th>Role</th>
                    <th>Opciones</th>
                </thead>
                <tbody>
                    @foreach ($roles as $role)
                        <tr>
                            <td>{{ $role->id }}</td>
                            <td>{{ $role->name }}</td>
                            <td>
                                <form action="{{ route('roles.destroy', $role) }}" method="POST">

                                    <a href="{{ route('roles.edit', $role ) }}" class="btn btn-info btn-sm">Editar</a>
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('¿Esta seguro de eliminar el registro?')">Eliminar</button>
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
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop
