@extends('adminlte::page')
@section('plugins.Select2', true)
@section('title', 'Registrar Venta')

@section('content_header')
    <h1>Registrar Venta</h1>
@stop

@section('content')

    <venta-desde-despacho
        :orden="{{ $orden }}"
        :payment-methods="{{$metodos}}"
        serie="{{ $serie->number }}-{{$serie->serie}}"
        :cliente="{{ $cliente }}"
        route-save="{{ route('ventas.store') }}"/>
@stop
