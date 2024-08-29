@extends('adminlte::page')

@section('title', 'Detalle de Venta')

@section('content_header')
    <h1>Detalle de Venta</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Información de Venta</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <strong>Orden de Despacho:</strong>
                    <p>{{ $venta->orden_despacho_id }} | Serie y Numero de Orden: {{ $venta->ordenDespacho->serie_orden }} </p>

                    <strong>Serie de Venta:</strong>
                    <p>{{ $venta->serie_venta }}</p>

                    <strong>Cliente:</strong>
                    <p>{{ $venta->ordenDespacho->cliente->razon_social }}</p>

                    <strong>Fecha de Venta:</strong>
                    <p>{{ $venta->fecha_venta }}</p>

                    <strong>Peso Neto:</strong>
                    <p>{{ $venta->peso_neto }}</p>

                    <strong>Forma de Pago:</strong>
                    <p>{{ $venta->forma_de_pago ? 'Credito' : 'Contado' }}</p>

                    <strong>Método de Pago ID:</strong>
                    <p>{{ $venta->metodoPago->descripcion }}</p>
                </div>
                <div class="col-md-6">
                    <strong>Monto Total:</strong>
                    <p>{{ $venta->monto_total }}</p>

                    <strong>Monto Recibido:</strong>
                    <p>{{ $venta->monto_recibido }}</p>

                    <strong>Saldo:</strong>
                    <p>{{ $venta->saldo }}</p>

                    <strong>Pagada:</strong>
                    <p>{{ $venta->pagada ? 'Sí' : 'No' }}</p>

                    <strong>URL Documento A4:</strong>
                    <p><a href="{{ $venta->url_venta_documento_a4 }}" target="_blank">Ver Documento A4</a></p>

                    <strong>URL Documento Ticket:</strong>
                    <p><a href="{{ $venta->url_venta_documento_ticket }}" target="_blank">Ver Documento Ticket</a></p>

                    <strong>Estado:</strong>
                    <p>{{ $venta->estado }}</p>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <a href="{{ route('ventas.index') }}" class="btn btn-primary">Volver a la Lista</a>
        </div>
    </div>
@stop
