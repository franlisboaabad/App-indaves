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
                    <div class="col-md-2">
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
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="fecha_venta">Fecha de Venta</label>
                            <input type="date" id="fecha_venta" name="fecha_venta" class="form-control" required>
                            @error('fecha_venta')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Select Cliente -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="cliente_id">Cliente</label>
                            <select id="cliente_id" name="cliente_id" class="form-control select2" required>
                                <option value="" disabled selected>Selecciona un cliente</option>
                                @foreach ($clientes as $cliente)
                                    <option value="{{ $cliente->id }}">{{ $cliente->nombre_comercial }}</option>
                                @endforeach
                            </select>
                            @error('cliente_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#createClientModal"
                            style="margin-top: 35px">
                            <i class="fa fa-user"></i> Nuevo Cliente
                        </button>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="saldo">Saldo</label>
                            <input type="text" id="saldo_pendiente" name="saldo_pendiente" class="form-control" readonly
                                value="0">
                        </div>
                    </div>
                </div>


                <div class="row mt-5">

                    <div class="col-md-4 mb-5">
                        <div class="form-grup">
                            <label for="presentacion_pollo_id">Presentacion de Pollo:</label>
                            <select id="presentacion_pollo_id" name="presentacion_pollo_id" class="form-control">
                                @foreach ($presentacionPollos as $presentacionPollo)
                                    <option value="{{ $presentacionPollo->id }}"> {{ $presentacionPollo->descripcion }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 mb-5">
                        <div class="form-grup">
                            <label for="tipo_pollo">Tipo de Pollo:</label>
                            <select id="tipo_pollo_id" name="tipo_pollo_id" class="form-control">
                                @foreach ($tipoPollos as $tipo)
                                    <option value="{{ $tipo->id }}"> {{ $tipo->descripcion }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 mb-5">
                        <div class="form-group">
                            <label for="tara">Tara kg.</label>
                            <input type="text" id="tara" name="tara" class="form-control">
                        </div>
                    </div>

                    <div class="col-md-2 mb-5">
                        <div class="form-group">
                            <label for="precio">Precio.</label>
                            <input type="number" id="precio" name="precio" class="form-control">
                        </div>
                    </div>
                    <!-- Número de Jabas -->
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label for="cantidad_jabas">Número de Jabas</label>
                            <input type="number" id="cantidad_jabas" name="cantidad_jabas" class="form-control">
                            @error('cantidad_jabas')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>


                    <!-- Cantidad de Pollos -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="pollos_jaba">Pollos por jaba</label>
                            <input type="number" id="pollos_jaba" name="pollos_jaba" class="form-control">
                            @error('pollos_jaba')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>


                    <!-- Cantidad de Pollos -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="cantidad_pollos">Cantidad de Pollos</label>
                            <input type="number" id="cantidad_pollos" name="cantidad_pollos" class="form-control">
                            @error('cantidad_pollos')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>


                    <!-- Peso Bruto -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="peso_bruto">Peso Bruto</label>
                            <input type="number" step="0.01" id="peso_bruto" name="peso_bruto"
                                class="form-control">
                            @error('peso_bruto')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>


                    <!-- Botón para agregar detalles -->
                    <div class="col-md-12">
                        <button type="button" id="addDetailBtn" class="btn btn-primary">Agregar al Detalle</button>
                    </div>

                </div>

                <!-- Tabla de Detalles -->
                <div class="mt-4">
                    <table class="table table-bordered" id="detailsTable">
                        <thead>
                            <tr>
                                <th>Cantidad de Pollos</th>
                                <th>Peso Bruto</th>
                                <th>Cantidad de Jabas</th>
                                <th>Tara</th>
                                <th>Precio</th>
                                <th>Peso Neto</th>
                                <th>Sub Total</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Las filas de la tabla se agregarán aquí mediante JavaScript -->
                        </tbody>
                        <tfoot>
                            <tr>
                                {{-- <th>Total</th> --}}
                                <th id="totalChiken">0.00</th>
                                <th id="totalWeight">0.00</th>
                                <th id="totalBoxes">0</th>
                                <th id="totalTara">0.00</th>
                                <th id="precio">0.00</th>
                                <th id="totalNetWeight">0.00</th>
                                <th id="subtotal">0.00</th>

                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Información de Venta en Dos Columnas -->
                <div class="row mt-4">
                    <div class="form-group">
                        <input type="hidden" name="peso_neto" id="peso_neto">
                    </div>


                    <!-- Información General de la Venta -->
                    <div class="col-md-4 mb-4">
                        <div class="form-group">
                            <label for="forma_de_pago">Forma de Pago</label>
                            <select name="forma_de_pago" id="forma_de_pago" class="form-control">
                                <option value="0">Contado</option>
                                <option value="1">Credito</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4 mb-4">
                        <div class="form-group">
                            <label for="metodo_pago_id">Método de Pago</label>
                            <select name="metodo_pago_id" id="metodo_pago_id" class="form-control">
                                @foreach ($metodos as $metodo)
                                    <option value="{{ $metodo->id }}">{{ $metodo->descripcion }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4 mb-4"></div>
                    <div class="col-md-4 mb-4">
                        <div class="form-group">
                            <label for="monto_total">Monto Total a Pagar</label>
                            <input type="text" id="monto_total" name="monto_total" class="form-control" readonly>
                        </div>
                    </div>

                    <!-- Información de Pago -->
                    <div class="col-md-2 mb-4">
                        <div class="form-group"> <br>
                            <label for="checkPagoCompleto" class="checkbox-label">
                                Desea hacer el pago completo?
                                <input type="checkbox" id="checkPagoCompleto">
                            </label>
                        </div>
                    </div>



                    <div class="col-md-2 mb-4">
                        <div class="form-group">
                            <label for="monto_recibido">Monto Recibido</label>
                            <input type="text" id="monto_recibido" name="monto_recibido" class="form-control">
                        </div>
                    </div>

                    <div class="col-md-4 mb-4">
                        <div class="form-group">
                            <label for="saldo">Saldo</label>
                            <input type="text" id="saldo" name="saldo" class="form-control" readonly>
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

    <!-- Modal Add cliente -->
    @include('admin.clientes.modal')


    <!-- Modal Imprimir documentos -->
    <div class="modal fade" id="selectDocumentTypeModal" tabindex="-1" aria-labelledby="selectDocumentTypeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="selectDocumentTypeModalLabel">Seleccionar Tipo de Documento</h5>
                    <!-- Button to close the modal -->
                    {{-- <button type="button" class="btn-close" id="btnCloseModal" aria-label="Close"></button> --}}
                </div>
                <div class="modal-body">
                    <p>Seleccione el tipo de documento que desea generar:</p>
                </div>
                <div class="modal-footer">
                    <a target="_blank" class="btn btn-primary" id="btnDocumentA4">Documento A4</a>
                    <a target="_blank" class="btn btn-secondary" id="btnDocumentTicket">Documento Ticket</a>
                    <!-- Button to close the modal and reload the page -->
                    <button type="button" class="btn btn-danger" id="btnReloadPage">Cerrar y Recargar</button>
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

        .checkbox-label {
            display: flex;
            align-items: center;
            font-size: 16px;
            /* Tamaño del texto */
            cursor: pointer;
            /* Cambia el cursor al pasar sobre el texto */
        }

        .checkbox-label input[type="checkbox"] {
            width: 20px;
            /* Tamaño del checkbox */
            height: 20px;
            /* Tamaño del checkbox */
            margin-right: 10px;
            /* Espacio entre el checkbox y el texto */
            cursor: pointer;
            /* Cambia el cursor al pasar sobre el checkbox */
        }
    </style>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const tipo_pollos = @json($tipoPollos);
        const presentacion_pollos = @json($presentacionPollos);
        const prices = @json($prices);
        const clientes = @json($clientes);
        document.addEventListener('DOMContentLoaded', function() {
            obtenerPrecio();
            obtenerTara();
            obtenerSaldo();

            //peso api
            $('#peso_bruto').on('focus', function() {
                // Realiza la solicitud AJAX cuando el campo reciba foco
                $.ajax({
                    url: 'http://127.0.0.1:8000/api/peso', // URL del endpoint
                    method: 'GET',
                    success: function(response) {
                        // Asigna el peso al campo de texto
                        if (response.peso !== null) {
                            $('#peso_bruto').val(response.peso);
                        } else {
                            $('#peso_bruto').val('No hay peso registrado');
                        }
                    },
                    error: function() {
                        $('#peso_bruto').val('Error al obtener peso');
                    }
                });
            });


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


            // Función para eliminar una fila
            $('#detailsTable').on('click', '.remove-row', function() {
                $(this).closest('tr').remove();
                updateTotals();
            });

            $('#btnGenerar').on('click', function(event) {
                event.preventDefault(); // Evita el envío inmediato del formulario
                const clienteId = $('#cliente_id').val();
                const serie_venta = $('#serie_venta').val();
                const fecha_venta = $('#fecha_venta').val();
                // Validar el cliente_id
                if (!clienteId) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Atención',
                        text: 'Por favor, seleccione un cliente.',
                    });
                    return;
                }


                // Recoger los datos de la tabla
                let totalChiken = $('#totalChiken').text();
                let totalWeight = $('#totalWeight').text();
                let totalBoxes = $('#totalBoxes').text();
                let totalTara = $('#totalTara').text();
                let totalNetWeight = $('#totalNetWeight').text();
                let subtotal = $('#subtotal').text();

                // Recoger los datos de la tabla
                let detalles = [];
                $('#detailsTable tbody tr').each(function() {
                    let cantidadPollos = $(this).find('td:eq(0)').text();
                    let pesoBruto = $(this).find('td:eq(1)').text();
                    let cantidadJabas = $(this).find('td:eq(2)').text();
                    let tara = $(this).find('td:eq(3)').text();
                    let precio = $(this).find('td:eq(4)').text();
                    let pesoNeto = $(this).find('td:eq(5)').text();
                    let subtotal = $(this).find('td:eq(6)').text();

                    detalles.push({
                        cantidad_pollos: cantidadPollos,
                        peso_bruto: pesoBruto,
                        cantidad_jabas: cantidadJabas,
                        tara: tara,
                        peso_neto: pesoNeto,
                        precio,
                        subtotal: subtotal,
                        tipo_pollo_id: $(this).data('tipo_pollo_id'),
                        presentacion_pollo_id: $(this).data('presentacion_pollo_id'),
                    });
                });

                // Validar que hay detalles en la tabla
                if (detalles.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Atención',
                        text: 'La tabla no tiene datos.',
                    });
                    return;
                }

                let data = {
                    cliente_id: clienteId,
                    serie_venta: serie_venta,
                    fecha_venta: fecha_venta,
                    cantidad_pollos: totalChiken,
                    peso_total_bruto: totalWeight,
                    cantidad_jabas: totalBoxes,
                    tara: totalTara,
                    peso_total_neto: totalNetWeight,
                    subtotal: subtotal,
                    forma_de_pago: $('#forma_de_pago').val(),
                    metodo_pago_id: $('#metodo_pago_id').val(),
                    monto_recibido: $('#monto_recibido').val(),
                    monto_total: $('#monto_total').val(),
                    saldo_pendiente: $('#saldo_pendiente').val(),
                    detalles: detalles, // Aquí se envían los detalles
                    _token: $('meta[name="csrf-token"]').attr('content') // Token CSRF para protección
                };
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
                            data: data, // Serializa los datos del formulario
                            success: function(response) {
                                Swal.fire({
                                    title: 'Éxito',
                                    text: 'Venta generada exitosamente.',
                                    icon: 'success',
                                    showConfirmButton: false, // Oculta el botón de confirmación
                                    timer: 1500 // Tiempo en milisegundos antes de recargar la página
                                }).then(() => {
                                    // Redirigir o limpiar el formulario según sea necesario
                                    // window.location.reload(); // Opcional: recargar la página
                                    $('#selectDocumentTypeModal').modal('show');
                                    setPrint(response.venta);
                                });
                            },
                            error: function(xhr) {
                                var response = xhr.responseJSON;
                                Swal.fire({
                                    title: 'Error',
                                    text: response.message ||
                                        'Hubo un problema al generar la venta. Inténtalo de nuevo.',
                                    icon: 'error'
                                });
                            }
                        });
                    }
                });
            });


            //seleccionar la forma de pago 0 contado, 1 credito
            $('#forma_de_pago').change(function() {
                var selectedValue = $(this).val();
                var metodoPagoSelect = $('#metodo_pago_id');

                // Valor del método de pago para "Crédito"
                var metodoPagoCreditoValue =
                    '5'; // Asegúrate de que este valor coincida con el valor real para "Crédito"

                if (selectedValue == '1') { // Si se selecciona "Crédito"
                    metodoPagoSelect.val(metodoPagoCreditoValue); // Selecciona el método de pago "Crédito"

                    let saldo = $('#monto_total').val();

                    $('#monto_recibido').val('0.00');
                    $('#monto_recibido').prop('readonly', true);
                    $('#checkPagoCompleto').prop('readonly', true);
                    $('#saldo').val(saldo);
                    $('#checkPagoCompleto').prop('disabled', true);


                } else {
                    metodoPagoSelect.val(
                        '1'); // Selecciona un valor por defecto, o podrías dejarlo en blanco
                    $('#saldo').val('0.00');
                    $('#monto_recibido').prop('readonly', false);
                    $('#checkPagoCompleto').prop('disabled', false);
                }
            });

            //check calculo del monto completo
            $('#checkPagoCompleto').change(function() {
                if ($(this).is(':checked')) {
                    // Obtener el monto total a pagar
                    var montoTotal = parseFloat($('#monto_total').val()) || 0;

                    // Establecer el monto recibido igual al monto total a pagar
                    $('#monto_recibido').val(montoTotal);

                    // Calcular y mostrar el saldo
                    $('#saldo').val('0.00');
                } else {
                    // Si no está marcado, limpiar los campos
                    $('#monto_recibido').val('0.00');
                    $('#saldo').val('0.00');
                }
            });

            //calcular el monto recibido
            $('#monto_recibido').on('input', function() {
                var montoTotal = parseFloat($('#monto_total').val()) || 0;
                var montoRecibido = parseFloat($(this).val()) || 0;

                // Calcular y mostrar el saldo
                var saldo = montoTotal - montoRecibido;
                $('#saldo').val(saldo.toFixed(2));
            });


            //ruta de pdf
            // Variable global para guardar la URL del PDF
            let pdfUrl_a4 = '';
            let pdfUrl_ticket = ''

            // Suponiendo que la URL se obtiene cuando se registra el documento
            function setPrint(venta) {
                $('#btnDocumentA4').attr('href', venta.url_pdf);
                $('#btnDocumentTicket').attr('href', venta.url_ticket);
            }
            //btnReload pagina
            $('#btnReloadPage').click(function(e) {
                e.preventDefault();
                window.location.reload(); // Opcional: recargar la página
            });


            document.getElementById('addDetailBtn').addEventListener('click', function() {
                // Obtener los valores de los inputs
                var cantidadPollos = document.getElementById('cantidad_pollos').value;
                var pesoBruto = document.getElementById('peso_bruto').value;
                var numeroJabas = document.getElementById('cantidad_jabas').value;
                var tara = document.getElementById('tara').value;
                const precio = document.getElementById('precio').value;

                // Tara por defecto
                var taraPorDefecto = tara; // 6 kg por jaba

                // Validar que los campos no estén vacíos
                if (!cantidadPollos || !pesoBruto || !numeroJabas) {
                    alert('Por favor, complete todos los campos.');
                    return;
                }

                // Calcular la tara
                var tara = numeroJabas * taraPorDefecto;

                // Calcular el peso neto
                var pesoNeto = pesoBruto - tara;

                //calcular sub total
                var subtotal = precio * pesoNeto;

                // Crear una nueva fila para la tabla de detalles
                const tableBody = document.getElementById('detailsTable').getElementsByTagName('tbody')[0];
                const newRow = tableBody.insertRow();

                // Insertar celdas en la nueva fila
                newRow.insertCell(0).textContent = cantidadPollos;
                newRow.insertCell(1).textContent = pesoBruto;
                newRow.insertCell(2).textContent = numeroJabas;
                newRow.insertCell(3).textContent = tara.toFixed(2);
                newRow.insertCell(4).textContent = precio;
                newRow.insertCell(5).textContent = pesoNeto.toFixed(2);
                newRow.insertCell(6).textContent = subtotal.toFixed(2);

                newRow.dataset.tipo_pollo_id = $('#tipo_pollo_id').val();
                newRow.dataset.presentacion_pollo_id = $('#presentacion_pollo_id').val();


                // Crear el botón de eliminar y añadirlo a la última celda
                const deleteBtn = document.createElement('button');
                deleteBtn.textContent = 'Eliminar';
                deleteBtn.className = 'btn btn-danger btn-sm';
                deleteBtn.onclick = function() {
                    // Eliminar la fila de la tabla
                    const rowIndex = newRow.rowIndex;
                    if (rowIndex > 0) { // Asegurarse de que el índice sea válido
                        tableBody.deleteRow(rowIndex - 1);
                        updateTotals();
                    }
                };
                newRow.insertCell(7).appendChild(deleteBtn);

                // Limpiar los campos del formulario
                document.getElementById('cantidad_pollos').value = '';
                document.getElementById('peso_bruto').value = '';
                document.getElementById('cantidad_jabas').value = '';
                document.getElementById('pollos_jaba').value = '';
                document.getElementById('cantidad_jabas').select();

                // Actualizar los totales
                updateTotals();
            });

            function updateTotals() {
                var tableBody = document.getElementById('detailsTable').getElementsByTagName('tbody')[0];
                var rows = tableBody.getElementsByTagName('tr');

                var totalChiken = 0;
                var totalWeight = 0;
                var totalTara = 0;
                var totalNetWeight = 0;
                var totalBoxes = 0;
                var subtotal = 0;

                // Sumar los valores de cada fila
                for (var i = 0; i < rows.length; i++) {
                    var cells = rows[i].getElementsByTagName('td');
                    totalChiken += parseInt(cells[0].textContent);
                    totalWeight += parseFloat(cells[1].textContent);
                    totalTara += parseFloat(cells[3].textContent);
                    totalNetWeight += parseFloat(cells[5].textContent);
                    totalBoxes += parseInt(cells[2].textContent);
                    subtotal += parseFloat(cells[6].textContent);
                }

                // Mostrar los totales en el pie de la tabla
                document.getElementById('totalChiken').textContent = totalChiken.toFixed(2);
                document.getElementById('totalWeight').textContent = totalWeight.toFixed(2);
                document.getElementById('totalTara').textContent = totalTara.toFixed(2);
                document.getElementById('totalNetWeight').textContent = totalNetWeight.toFixed(2);
                document.getElementById('totalBoxes').textContent = totalBoxes;
                document.getElementById('subtotal').textContent = subtotal.toFixed(2);


                const saldo = +$('#saldo_pendiente').val();
                const totalPay = subtotal - saldo;
                //monto total a pagar
                $('#monto_total').val(totalPay.toFixed(2));
            }

            $('#presentacion_pollo_id').change(function(el) {
                obtenerTara();
                obtenerPrecio();
            });

            $('#tipo_pollo_id').change(function(el) {
                obtenerPrecio();
            });

            function obtenerPrecio() {
                const value_presentacion = $('#presentacion_pollo_id').val();
                const value_tipo_pollo = $('#tipo_pollo_id').val();

                const precioBuscado = prices.find(price => price.presentacion_pollo_id == value_presentacion &&
                    price.tipo_pollo_id == value_tipo_pollo);

                if (precioBuscado) {
                    $('#precio').val(precioBuscado?.precio);
                }
            }

            function obtenerTara() {
                const value = $('#presentacion_pollo_id').val();
                const type = presentacion_pollos.find(type => type.id == value);
                if (type) {
                    $('#tara').val(type?.tara)
                }
            }

            function obtenerSaldo() {
                const clienteId = $('#cliente_id').val();
                if (clienteId) {
                    const clienteBuscado = clientes.find(cliente => cliente.id == clienteId);
                    $('#saldo_pendiente').val(clienteBuscado.saldos_sum_total || 0)
                }
            }

            $('#cliente_id').change(function() {
                obtenerSaldo();
            });

            $('#cantidad_pollos').focus(function(e) {
                e.preventDefault();

                let cantidad_jabas = $('#cantidad_jabas').val();
                let pollos_jaba = $('#pollos_jaba').val();

                $('#cantidad_pollos').val(cantidad_jabas * pollos_jaba);

            });

            $('.select2').select2()
        });
    </script>
@stop
