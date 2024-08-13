@extends('adminlte::page')

@section('title', 'Editar Sorteo')

@section('plugins.Select2', true)

@section('content_header')
    <h1>Registro de sorteos</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">

                    @include('partials.validaciones')

                    <form action="{{ route('sorteos.update', $sorteo ) }}" method="POST">

                        <div class="form-group">
                            <label for="">*Nombre identificador de sorteo</label>
                            <input type="text" name="nombre_sorteo" id="nombre_sorteo" class="form-control" required
                                value="{{ old('nombre_sorteo', $sorteo->nombre_sorteo) }}">
                        </div>

                        <div class="form-group">
                            <label for="">*Opciones</label>
                            <input type="text" name="opciones" class="form-control" required
                            value="{{ old('opciones', $sorteo->opciones) }}" maxlength="2">
                        </div>


                        <div class="form-group">
                            <label for="">*Cantidad de Tickets</label>
                            <input type="text" name="cantidad_tickets" class="form-control" required
                            value="{{ old('cantidad_tickets', $sorteo->cantidad_tickets) }}" maxlength="6">
                        </div>



                        <div class="form-group">
                            <label for="">*Fecha de sorteo</label>
                            <input type="datetime-local" name="fecha_de_sorteo" id="fecha_de_sorteo" class="form-control" required
                                value="{{ old('fecha_de_sorteo', $sorteo->fecha_de_sorteo) }}">
                        </div>

                        <div class="form-group">
                            <label for="">*Premios</label>
                            <input type="text" name="premios" id="premios" class="form-control" required
                                value="{{ old('premios', $sorteo->premios) }}">
                        </div>

                        <div class="form-group">
                            <label for="">Descripcion</label>
                            <textarea name="descripcion_del_sorteo" class="form-control" id="" cols="30" rows="10"> {{ $sorteo->descripcion }}  </textarea>
                        </div>

                        <div class="form-group">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-success" onclick="return confirm('¿Esta seguro de actualizar los datos?')">Editar sorteo</button>
                            <a href="{{ route('sorteos.index') }}" class="btn btn-warning">Lista de sorteos</a>
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
