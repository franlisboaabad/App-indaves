@extends('adminlte::page')

@section('title', 'Nuevo Registro')

@section('plugins.Select2', true)

@section('content_header')
    <h1>Nuevo Registro</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">

                    @include('partials.validaciones')

                    <form action="{{ route('registros.store') }}" method="POST" enctype="multipart/form-data">

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
                            <input type="text" name="numero_identidad"  class="form-control" required
                                value="{{ old('numero_identidad') }}" maxlength="10">
                        </div>

                        <div class="form-group">
                            <label for="">Nombres y Apellidos</label>
                            <input type="text" name="nombre_apellidos"  class="form-control" required
                                value="{{ old('nombre_apellidos') }}">
                        </div>

                        <div class="form-group">
                            <label for="">Celular</label>
                            <input type="text" name="celular" class="form-control" required
                                value="{{ old('celular') }}">
                        </div>

                        <div class="form-group">
                            <label for="">E-mail</label>
                            <input type="email" name="email"  class="form-control" required
                                value="{{ old('email') }}">
                        </div>

                        <div class="form-group">
                            <label for="">Monto</label>
                            <input type="text" name="monto" class="form-control" required
                                value="{{ old('monto') }}">
                        </div>

                        <div class="form-group">
                            <label for="">Comprobante</label>
                            <input type="file" name="image" class="form-control">
                        </div>


                        <div class="form-group">
                            @csrf
                            @method('POST')
                            <button type="submit" class="btn btn-success ">Registrar</button>
                            <a href="{{ route('registros.index') }}" class="btn btn-warning ">Lista de registros</a>
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
