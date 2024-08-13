@extends('adminlte::page')

@section('title', 'Editar Registro')

@section('plugins.Select2', true)

@section('content_header')
    <h1>Editar Registro</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">

                    @include('partials.validaciones')

                    <form action="{{ route('registros.update', $registro) }}" method="POST" enctype="multipart/form-data">

                        <div class="form-group">
                            <label for="">Seleccionar Sorteo</label>
                            <select name="sorteo_id" class="form-control">
                                @foreach ($sorteos as $sorteo)
                                    <option value="{{ $sorteo->id }}">{{ $sorteo->nombre_sorteo }}</option>
                                @endforeach
                            </select>
                        </div>


                        <div class="form-group">
                            <label for="">Numero de DNI / C.E</label>
                            <input type="text" name="numero_identidad" class="form-control" required
                                value="{{ old('numero_identidad', $registro->numero_identidad) }}" maxlength="10">
                        </div>

                        <div class="form-group">
                            <label for="">Nombres y Apellidos</label>
                            <input type="text" name="nombre_apellidos" class="form-control" required
                                value="{{ old('nombre_apellidos', $registro->nombre_apellidos) }}">
                        </div>

                        <div class="form-group">
                            <label for="">Celular</label>
                            <input type="text" name="celular" class="form-control" required
                                value="{{ old('celular', $registro->celular) }}">
                        </div>

                        <div class="form-group">
                            <label for="">E-mail</label>
                            <input type="email" name="email" class="form-control" required
                                value="{{ old('email', $registro->email) }}">
                        </div>

                        <div class="form-group">
                            <label for="">Monto</label>
                            <input type="text" name="monto" class="form-control" required
                                value="{{ old('monto', $registro->monto) }}">
                        </div>

                        <div class="form-group">
                            <label for="">Comprobante</label>
                            <input type="file" name="image" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="" style="color:red">Estado de registro</label>

                            <select name="estado_registro" id="" class="form-control" >
                                @if ($registro->estado_registro === 0)
                                    <option value="0" selected>En proceso</option>
                                    <option value="1">Aprobado</option>
                                    <option value="2">Rechazado</option>
                                @elseif($registro->estado_registro === 1)
                                    <option value="0">En proceso</option>
                                    <option value="1" selected>Aprobado</option>
                                    <option value="2">Rechazado</option>
                                @elseif($registro->estado_registro === 2)
                                    <option value="0">En proceso</option>
                                    <option value="1">Aprobado</option>
                                    <option value="2" selected>Rechazado</option>
                                @endif
                            </select>
                        </div>



                        <div class="form-group">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-success">Editar</button>
                            <a href="{{ route('registros.index') }}" class="btn btn-warning">Lista de registros</a>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        @if ($registro->image)
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <img src="{{ $registro->getImagen }}" class="img-fluid">
                    </div>
                </div>
            </div>
        @endif

    </div>
@stop

@section('css')

@stop

@section('js')

@stop
