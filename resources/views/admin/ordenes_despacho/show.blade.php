@extends('adminlte::page')

@section('title', 'Detalles de la Orden de Despacho')

@section('content_header')
    <h1>Detalles de la Orden de Despacho</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Información de la Orden</h3>
            <!-- Botón para abrir el modal -->
            {{-- <button class="btn btn-primary float-right" onclick="printOrder()">Imprimir Orden</button> --}}

        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <!-- Información de la Orden -->
            <div class="mb-4">
                <p><strong>Cliente:</strong> {{ $orden->cliente->razon_social }}</p>
                <p><strong>Serie de Orden:</strong> {{ $orden->serie_orden }}</p>
                <p><strong>Fecha de Despacho:</strong> {{ \Carbon\Carbon::parse($orden->fecha_despacho)->format('d/m/Y') }}
                </p>
                <p><strong>Peso Total Bruto:</strong> {{ number_format($orden->peso_total_bruto, 2) }}</p>
                <p><strong>Cantidad de Jabas:</strong> {{ $orden->cantidad_jabas }}</p>
                <p><strong>Tara:</strong> {{ number_format($orden->tara, 2) }}</p>
                <p><strong>Peso Total Neto:</strong> {{ number_format($orden->peso_total_neto, 2) }}</p>
                <p><strong>Estado:</strong> {{ $orden->estado_despacho ? 'Despachado' : 'Por despachar' }}</p>
            </div>

            <!-- Detalles de la Orden -->
            <div>
                <h4>Detalles de la Orden</h4>
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>Presentación</th>
                            <th>Tipo</th>
                            <th>Cantidad de Pollos</th>
                            <th>Cantidad de Jabas</th>
                            <th>Peso Bruto</th>
                            <th>Tara</th>
                            <th>Peso Neto</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orden->detalles as $detalle)
                            <tr>
                                <td>{{ $detalle->presentacion_pollo_descripcion }}</td>
                                <td>{{ $detalle->tipo_pollo_descripcion }}</td>
                                <td>{{ $detalle->cantidad_pollos }}</td>
                                <td>{{ $detalle->cantidad_jabas }}</td>
                                <td>{{ number_format($detalle->peso_bruto, 2) }}</td>
                                <td>{{ number_format($detalle->tara, 2) }}</td>
                                <td>{{ number_format($detalle->peso_neto, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="tfoot-dark">
                        <tr>
                            <td colspan="2">TOTALES</td>
                            <td id="totalPollos">0</td>
                            <td id="totalJabas">0</td>
                            <td id="totalPesoBruto">0</td>
                            <td id="totalTara">0</td>
                            <td id="totalPesoNeto">0</td>

                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->



    <!-- Modal para mostrar el PDF -->
    <div class="modal fade" id="printOrderModal" tabindex="-1" role="dialog" aria-labelledby="printOrderModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="printOrderModalLabel">Orden de Despacho</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <iframe id="pdfIframe" src="" style="width: 100%; height: 500px;" frameborder="0"></iframe>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" onclick="printPdf()">Imprimir</button>
                </div>
            </div>
        </div>
    </div>


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
            // Obtener la URL del PDF
            var pdfUrl =
                "{{ asset('storage/' . $orden->url_orden_documento_a4) }}"; // Cambia esto si es necesario para obtener la URL correcta

            // Establecer la URL del PDF en el iframe
            document.getElementById('pdfIframe').src = pdfUrl;

            // Mostrar el modal
            $('#printOrderModal').modal('show');
        }

        function printPdf() {
            var iframe = document.getElementById('pdfIframe');
            iframe.contentWindow.print(); // Imprimir el contenido del iframe
        }


        function sumarColumnas() {

            let totalPollos = 0;
            let totalJabas = 0;
            let totalPesoBruto = 0;
            let totalTara  = 0;
            let totalPNeto = 0;

            // Iterar sobre cada fila de la tabla (excluyendo el encabezado y el pie de tabla)
            $('.table tbody tr').each(function() {
                const cantidadPollos = parseInt($(this).find('td:nth-child(3)').text()) || 0;
                const cantidadJabas = parseInt($(this).find('td:nth-child(4)').text()) || 0;
                const totalPBruto = parseFloat($(this).find('td:nth-child(5)').text()) || 0;
                const Tara = parseInt($(this).find('td:nth-child(6)').text()) || 0;
                const pesoNeto = parseFloat($(this).find('td:nth-child(7)').text()) || 0;

                totalPollos += cantidadPollos;
                totalJabas += cantidadJabas;
                totalPesoBruto += totalPBruto;
                totalTara += Tara;
                totalPNeto += pesoNeto;

            });

            console.log(totalTara);


            // Mostrar los totales en el tfoot
            $('#totalPollos').text(totalPollos);
            $('#totalJabas').text(totalJabas);
            $('#totalPesoBruto').text(totalPesoBruto.toFixed(2)); // Limitar a 2 decimales
            $('#totalTara').text(totalTara);
            $('#totalPesoNeto').text(totalPNeto.toFixed(2)); // Limitar a 2 decimales
        }

        // Llamar a la función para sumar al cargar la página
        sumarColumnas();
    </script>
@endsection
