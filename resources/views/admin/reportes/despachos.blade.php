@extends('adminlte::page')

@section('title', 'Reporte Despachos')
@section('plugins.Select2', true)
@section('content_header')
    <h4>Reporte de Despachos</h4>
@stop

@section('content')
    <reporte-despachos
        :presentations="{{ $presentacion_pollos }}"
        :types="{{$tipo_pollos}}"
        :clientes="{{$clientes}}"
        route-send="{{ route('reportes.despachos.search') }}"
        route-export-pdf="{{ route('reportes.despachos.export',['format' => 'pdf']) }}"
        route-export-excel="{{ route('reportes.despachos.export',['format' => 'excel']) }}"
        csrf="{{ csrf_token() }}"
    ></reporte-despachos>
@stop

@section('css')
    <style>
        .select2-selection {
            height: 40px !important;
        }

    </style>
@section('js')
    <script>
        $('.select2').select2()
    </script>
@stop
