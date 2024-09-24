@extends('adminlte::page')

@section('title', 'Reporte Ingresos')
@section('plugins.Datatables', true)
@section('content_header')
    <h4>Reporte de Cuentas por Cobrar</h4>
@stop

@section('content')
   <reporte-cuentas-por-cobrar
       :presentations="{{ $presentacion_pollos }}"
       :types="{{$tipo_pollos}}"
       :clientes="{{$clientes}}"
       route-send="{{ route('reportes.cuentas-por-cobrar.search') }}"
       route-export-pdf="{{ route('reportes.cuentas-por-cobrar.export',['format' => 'pdf']) }}"
       route-export-excel="{{ route('reportes.cuentas-por-cobrar.export',['format' => 'excel']) }}"
       csrf="{{ csrf_token() }}"
   ></reporte-cuentas-por-cobrar>
@stop
