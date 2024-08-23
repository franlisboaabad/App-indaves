@extends('adminlte::page')
@section('plugins.Select2', true)
@section('title', 'Registrar Venta')

@section('content_header')
    <h1>Registrar Venta</h1>
@stop

@section('content')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Venta</h3>
        </div>
        <div class="card-body">
            <form id="ventaForm" method="POST" action="{{ route('ventas.store') }}">
                @csrf
                <div class="row">

                    <!-- Serie de Venta -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="serie_venta">Serie de Venta</label>
                            <input type="text" id="serie_venta" name="serie_venta" class="form-control" required
                                value="{{ $serie->number }}-{{ $serie->serie }}" readonly>
                            @error('serie_venta')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Fecha de Venta -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="fecha_venta">Fecha de Venta</label>
                            <input type="date" id="fecha_venta" name="fecha_venta" class="form-control" required>
                            @error('fecha_venta')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Espacio vacío para alineación -->
                    <div class="col-md-4"></div>

                    <!-- Select Cliente -->
                    <div class="col-md-4">

                        <div class="form-group">
                            <label for="cliente_id">Seleccionar Orden de despacho</label>
                            <select id="orden_despacho_id" name="orden_despacho_id" class="form-control select2" required>
                                <option value="" disabled selected>Selecciona una Orden</option>
                                @foreach ($ordenes as $orden)
                                    <option value="{{ $orden->id }}">{{ $orden->serie_orden }}</option>
                                @endforeach
                            </select>
                            @error('cliente_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-8"></div>


                </div>


                <hr>
                <!-- Tabla de Detalles -->
                <h3>Detalle de Orden</h3>
                <div class="mt-4">
                    <table class="table table-bordered" id="detailsTable">
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
                            <!-- Las filas de la tabla se agregarán aquí mediante JavaScript -->
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Total</th>
                                <th id="totalWeight">0.00</th>
                                <th id="totalBoxes">0</th>
                                <th id="totalTara">0.00</th>
                                <th id="totalNetWeight">0.00</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="form-group">
                    <input type="hidden" name="peso_neto" id="peso_neto">
                </div>




                <!-- Información de Venta en Dos Columnas -->
                <div class="row mt-4">

                    <div class="col-md-4 mb-4">
                        <label for="">Forma de pago</label>
                        <select name="forma_de_pago" id="forma_de_pago" class="form-control">
                            <option value="0">Contado</option>
                            <option value="1">Credito</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-4">
                        <label for="">Metodo de pago</label>
                        <select name="metodo_pago_id" id="metodo_pago_id" class="form-control">
                            @foreach ($metodos as $metodo)
                                <option value="{{ $metodo->id }}"> {{ $metodo->descripcion }} </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mb-4">

                    </div>


                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="precio_venta">Precio de Venta (por unidad)</label>
                            <input type="text" id="precio_venta" name="precio_venta" class="form-control"
                                value="{{ $precio->precio ?? 0 }}" placeholder="Ingrese el precio de venta">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="monto_total">Monto Total a Pagar</label>
                            <input type="text" id="monto_total" name="monto_total" class="form-control" readonly>
                        </div>
                    </div>
                </div>

                <!-- Botón de Enviar en el Footer -->
                <div class="text-right mt-4">
                    <button type="button" class="btn btn-success" id="btnGenerar">Generar Venta</button>
                </div>


            </form>
        </div>
    </div>





    <!-- Modal -->
    <div class="modal fade" id="createClientModal" tabindex="-1" role="dialog" aria-labelledby="createClientModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createClientModalLabel">Crear Nuevo Cliente</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="createClientForm">
                        @csrf

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="tipo_documento">Tipo de Documento</label>
                                    <select id="tipo_documento" name="tipo_documento" class="form-control" required>
                                        <option value="0">SIN DOCUMENTO</option>
                                        <option value="1">DNI</option>
                                        <option value="2">RUC</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="documento">Documento</label>
                                    <div class="input-group">
                                        <input type="text" id="documento" name="documento" class="form-control"
                                            required>
                                        <div class="input-group-append">
                                            <button type="button" id="searchDocumentBtn"
                                                class="btn btn-outline-secondary">
                                                <i class="fa fa-search"></i> Buscar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">

                                <div class="form-group">
                                    <label for="nombre_comercial">Nombre Comercial</label>
                                    <input type="text" id="nombre_comercial" name="nombre_comercial"
                                        class="form-control" required>
                                </div>
                            </div>

                            <div class="col">

                                <div class="form-group">
                                    <label for="razon_social">Razón Social</label>
                                    <input type="text" id="razon_social" name="razon_social" class="form-control"
                                        required>
                                </div>
                            </div>
                        </div>




                        <div class="form-group">
                            <label for="direccion">Dirección</label>
                            <input type="text" id="direccion" name="direccion" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="departamento">Departamento</label>
                            <input type="text" id="departamento" name="departamento" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="provincia">Provincia</label>
                            <input type="text" id="provincia" name="provincia" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="distrito">Distrito</label>
                            <input type="text" id="distrito" name="distrito" class="form-control" required>
                        </div>


                        <div class="row">

                            <div class="col">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" id="email" name="email" class="form-control" required>
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-group">
                                    <label for="celular">Celular</label>
                                    <input type="text" id="celular" name="celular" class="form-control" required>
                                </div>

                            </div>
                        </div>



                        <div class="form-group">
                            <button type="button" id="saveClientBtn" class="btn btn-success">Guardar</button>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

@stop

@section('css')
    <style>
        .select2-selection {
            height: 40px !important;
        }
    </style>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Función para obtener la fecha en formato YYYY-MM-DD en la zona horaria local
            function getLocalDateString() {
                const today = new Date();
                const year = today.getFullYear();
                const month = String(today.getMonth() + 1).padStart(2, '0'); // Meses empiezan en 0
                const day = String(today.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            }

            // Establecer la fecha actual en el campo de entrada de fecha
            document.getElementById('fecha_venta').value = getLocalDateString();


            $('#orden_despacho_id').on('change', function() {
                var ordenId = $(this).val();
                if (ordenId) {
                    $.ajax({
                        url: '/ordenes/' + ordenId,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            if (data) {
                                // Limpiar la tabla antes de agregar nuevas filas
                                $('#detailsTable tbody').empty();


                                // Agregar filas con los detalles de la orden
                                data.detalles.forEach(function(detalle) {
                                    var row = '<tr>' +
                                        '<td>' + detalle.cantidad_pollos + '</td>' +
                                        '<td>' + detalle.peso_bruto + '</td>' +
                                        '<td>' + detalle.cantidad_jabas + '</td>' +
                                        '<td>' + detalle.tara + '</td>' +
                                        '<td>' + detalle.peso_neto + '</td>' +
                                        '</tr>';
                                    $('#detailsTable tbody').append(row);
                                });

                                // Calcular y actualizar los totales
                                updateTotals();
                                $('#orden-detalle').show();
                                updatePago();

                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error);
                        }
                    });
                } else {
                    $('#orden-detalle').hide();
                }
            });

            // Función para calcular y actualizar los totales
            function updateTotals() {
                var totalWeight = 0;
                var totalBoxes = 0;
                var totalTara = 0;
                var totalNetWeight = 0;

                $('#detailsTable tbody tr').each(function() {
                    var cells = $(this).find('td');
                    totalWeight += parseFloat($(cells[1]).text()) || 0;
                    totalBoxes += parseInt($(cells[2]).text()) || 0;
                    totalTara += parseFloat($(cells[3]).text()) || 0;
                    totalNetWeight += parseFloat($(cells[4]).text()) || 0;
                });

                $('#totalWeight').text(totalWeight.toFixed(2));
                $('#totalBoxes').text(totalBoxes);
                $('#totalTara').text(totalTara.toFixed(2));
                $('#totalNetWeight').text(totalNetWeight.toFixed(2));

                //tomar el peso neto para enviar
                $('#peso_neto').val(totalNetWeight.toFixed(2));

            }

            // Función para eliminar una fila
            $('#detailsTable').on('click', '.remove-row', function() {
                $(this).closest('tr').remove();
                updateTotals();
            });

            // Actualizar el monto a pagar cuando cambie el precio de venta
            $('#precio_venta').on('input', function() {
                var precioVenta = parseFloat($(this).val()) || 0;
                var totalNetWeight = parseFloat($('#totalNetWeight').text()) || 0;
                $('#monto_total').val((totalNetWeight * precioVenta).toFixed(2));
            });

            function updatePago() {
                var precioVenta = parseFloat($('#precio_venta').val()) || 0;
                var totalNetWeight = parseFloat($('#totalNetWeight').text()) || 0;
                $('#monto_total').val((totalNetWeight * precioVenta).toFixed(2));
            }


            $('#btnGenerar').on('click', function(event) {
                event.preventDefault(); // Evita el envío inmediato del formulario

                // Mostrar la ventana de confirmación con SweetAlert
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¿Quieres generar esta venta?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, generar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Si el usuario confirma, enviar los datos del formulario usando AJAX
                        $.ajax({
                            url: "{{ route('ventas.store') }}", // URL del método store
                            type: 'POST', // Método HTTP
                            data: $('#ventaForm')
                        .serialize(), // Serializa los datos del formulario
                            success: function(response) {
                                Swal.fire({
                                    title: 'Éxito',
                                    text: 'Venta generada exitosamente.',
                                    icon: 'success'
                                }).then(() => {
                                    // Redirigir o limpiar el formulario según sea necesario
                                    window.location
                                .reload(); // Opcional: recargar la página
                                });
                            },
                            error: function(xhr) {
                                var response = xhr.responseJSON;
                                Swal.fire({
                                    title: 'Error',
                                    text:   response.message || 'Hubo un problema al generar la venta. Inténtalo de nuevo.',
                                    icon: 'error'
                                });
                            }
                        });
                    }
                });
            });

            $('.select2').select2();

        });
    </script>
@stop
