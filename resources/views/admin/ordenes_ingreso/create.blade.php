@extends('adminlte::page')
@section('plugins.Select2', true)
@section('title', 'Orden de ingreso')

@section('content_header')
    <h1>Orden de ingreso</h1>
@stop

@section('content')
    <nota-ingreso
        :presentations="{{ $presentacion_pollos }}"
        :types="{{$tipo_pollos}}"
        route-save="{{ route('ordenes-ingreso.store') }}"/>
@stop
