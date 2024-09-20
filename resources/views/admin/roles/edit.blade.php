@extends('adminlte::page')

@section('title', 'Editar rol')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Agregar Rol</h3>
                </div>
                <div class="card-body">

                    @include('partials.validaciones')

                    <form  action="{{ route('roles.update', $role) }}" method="POST">

                        <div class="form-group">
                            <label for="name">Nombre del Rol</label>
                            <input type="text" id="name" name="name" placeholder="Ingrese Rol" class="form-control" required value="{{ $role->name }}">
                        </div>

                        <hr>
                        <h5>Lista de Permisos</h5>
                        <div class="form-group">
                            @foreach ($permisos as $permiso)
                                <div class="custom-control custom-switch mb-2">
                                    <input type="checkbox" class="custom-control-input" id="permiso{{ $permiso->id }}"
                                        name="permisos[]" value="{{ $permiso->id }}" {{ $role->hasPermissionTo($permiso->name) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="permiso{{ $permiso->id }}">
                                        {{ $permiso->description }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <hr>

                        <div class="form-group">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-success">Editar Rol</button>
                            <a href="{{ route('roles.index') }}" class="btn btn-danger">Lista de Roles</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@stop

@section('css')

@stop

@section('js')

@stop
