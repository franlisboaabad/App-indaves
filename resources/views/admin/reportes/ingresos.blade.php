@extends('adminlte::page')

@section('title', 'Reporte Ingresos')
@section('plugins.Datatables', true)
@section('content_header')
    <h1>Reporte de Ingresos</h1>
@stop

@section('content')
   <reporte-ingresos
       :presentations="{{ $presentacion_pollos }}"
       :types="{{$tipo_pollos}}"
       route-send="{{ route('reportes.ingresos.search') }}"
       route-export-pdf="{{ route('reportes.ingresos.export',['format' => 'pdf']) }}"
       route-export-excel="{{ route('reportes.ingresos.export',['format' => 'excel']) }}"
       csrf="{{ csrf_token() }}"
   ></reporte-ingresos>
@stop
