@extends('adminlte::page')

@section('title', 'Detalles de la Orden de Despacho')

@section('content_header')
    <h1>Detalles de la Orden de Despacho</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Información de la Orden</h3>
            <button class="btn btn-primary float-right" onclick="printOrder()">Imprimir Orden</button>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <!-- Información de la Orden -->
            <div class="mb-4">
                <h4>Orden de Despacho</h4>
                <p><strong>Cliente ID:</strong> {{ $orden->cliente->razon_social }}</p>
                <p><strong>Serie de Orden:</strong> {{ $orden->serie_orden }}</p>
                <p><strong>Fecha de Despacho:</strong> {{ \Carbon\Carbon::parse($orden->fecha_despacho)->format('d/m/Y') }}
                </p>
                <p><strong>Peso Total Bruto:</strong> {{ number_format($orden->peso_total_bruto, 2) }}</p>
                <p><strong>Cantidad de Jabas:</strong> {{ $orden->cantidad_jabas }}</p>
                <p><strong>Tara:</strong> {{ number_format($orden->tara, 2) }}</p>
                <p><strong>Peso Total Neto:</strong> {{ number_format($orden->peso_total_neto, 2) }}</p>
                <p><strong>Estado:</strong> {{ $orden->estado_despacho }}</p>
            </div>

            <!-- Detalles de la Orden -->
            <div>
                <h4>Detalles de la Orden</h4>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Cantidad de Pollos</th>
                            <th>Peso Bruto</th>
                            <th>Cantidad de Jabas</th>
                            <th>Tara</th>
                            <th>Peso Neto</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orden->detalles as $detalle)
                            <tr>
                                <td>{{ $detalle->cantidad_pollos }}</td>
                                <td>{{ number_format($detalle->peso_bruto, 2) }}</td>
                                <td>{{ $detalle->cantidad_jabas }}</td>
                                <td>{{ number_format($detalle->tara, 2) }}</td>
                                <td>{{ number_format($detalle->peso_neto, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
@endsection

@section('css')
    <style>
        @media print {
            .no-print {
                display: none;
            }

            /* Aquí puedes añadir otros estilos para la impresión */
        }
    </style>
@endsection

@section('js')
    <script>
        function printOrder() {
            window.print();
        }
    </script>
@endsection
