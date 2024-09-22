@extends('adminlte::page')
@section('plugins.Select2', true)
@section('title', 'Orden de despacho')

@section('content_header')
    <h1>Orden de despacho</h1>
@stop

@section('content')

    <div class="card">
        <div class="card-body">
            <form id="ventaForm" method="POST" action="{{ route('ordenes-de-despacho.store') }}">
                @csrf

                <div class="row">

                    <!-- Serie de Venta -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="serie_orden">Serie de Orden</label>
                            <input type="text" id="serie_orden" name="serie_orden" class="form-control" required
                                value="{{ $serie->number }}-{{ $serie->serie }}" readonly>
                            @error('serie_orden')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Fecha de Venta -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fecha_despacho">Fecha de Despacho</label>
                            <input type="date" id="fecha_despacho" name="fecha_despacho" class="form-control" required>
                            @error('fecha_despacho')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Select Cliente -->
                    <div class="col-md-4">

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

                    <!-- Espacios vacíos para alineación -->
                    <div class="col-md-2">
                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#createClientModal"
                            style="margin-top: 35px">
                            <i class="fa fa-user"></i> Nuevo Cliente
                        </button>
                    </div>
                    <div class="col-md-2"></div>
                </div>

                <hr>

                <div class="row mt-5">

                    <div class="col-md-2 mb-5">
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
                    <div class="col-md-2 mb-5">
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
                            <label for="tara">Cantidad disponible</label>
                            <input type="text" id="cantidad_disponible_tipo" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-md-2 mb-5">
                        <div class="form-group">
                            <label for="tara">Peso disponible</label>
                            <input type="text" id="peso_disponible_tipo" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-md-2 mb-5">
                        <div class="form-group">
                            <label for="tara">Tara kg.</label>
                            <input type="text" id="tara" name="tara" class="form-control">
                        </div>
                    </div>

                    <div class="col-md-2 mb-5 d-none">
                        <div class="form-group">
                            <label for="precio">Precio.</label>
                            <input type="number" id="precio" name="precio" class="form-control">
                        </div>
                    </div>
                    <!-- Número de Jabas -->

                    <div class="col-md-2"></div>


                    <div class="col-md-2 mb-3">
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
                    <div class="col-md-2">
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
                    <div class="col-md-3 pt-2">
                        <button type="button" id="addDetailBtn" class="btn btn-primary mt-4">Agregar al Detalle</button>
                    </div>

                </div>

                <!-- Tabla de Detalles -->
                <div class="mt-4">
                    <table class="table table-bordered" id="detailsTable">
                        <thead>
                            <tr>
                                <th>Presentación de Pollo</th>
                                <th>Tipo de Pollo</th>
                                <th>Cantidad de Pollos</th>
                                <th>Peso Bruto</th>
                                <th>Cantidad de Jabas</th>
                                <th>Tara</th>
                                <th class="d-none">Precio</th>
                                <th>Peso Neto</th>
                                <th class="d-none">Sub Total</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Las filas de la tabla se agregarán aquí mediante JavaScript -->
                        </tbody>
                        <tfoot>
                            <tr>
                                {{-- <th>Total</th> --}}
                                <th id="presentacionPolloId">-</th>
                                <th id="tipoPolloId">-</th>
                                <th id="totalChiken">0.00</th>
                                <th id="totalWeight">0.00</th>
                                <th id="totalBoxes">0</th>
                                <th id="totalTara">0.00</th>
                                <th id="precio" class="d-none">0.00</th>
                                <th id="totalNetWeight">0.00</th>
                                <th id="subtotal" class="d-none">0.00</th>

                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>


                <!-- Botón de Submit -->
                <br>
                <button type="button" id="saveOrderBtn" class="btn btn-success">Registrar Orden</button>
            </form>


        </div>
    </div>




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
                    <a class="btn btn-primary" id="btnDocumentA4" target="_blank">Documento A4</a>
                    <a class="btn btn-secondary" id="btnDocumentTicket" target="_blank">Documento Ticket</a>
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
    </style>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <script>
        const tipo_pollos = @json($tipoPollos);
        const presentacion_pollos = @json($presentacionPollos);
        const prices = @json($prices);
        const stocks = @json($stockPollo);

        document.addEventListener('DOMContentLoaded', function() {

            obtenerPrecio();
            obtenerTara();
            obtenerDisponibilidad();
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
            //calcular cantidad de pollos
            $('#cantidad_pollos').focus(function(e) {
                e.preventDefault();

                let cantidad_jabas = $('#cantidad_jabas').val();
                let pollos_jaba = $('#pollos_jaba').val();

                $('#cantidad_pollos').val(cantidad_jabas * pollos_jaba);

            });
            let detailIndex = 1;
            // Función para obtener la fecha en formato YYYY-MM-DD en la zona horaria local
            function getLocalDateString() {
                const today = new Date();
                const year = today.getFullYear();
                const month = String(today.getMonth() + 1).padStart(2, '0'); // Meses empiezan en 0
                const day = String(today.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            }

            // Establecer la fecha actual en el campo de entrada de fecha
            document.getElementById('fecha_despacho').value = getLocalDateString();


            //funcionalidad para detalle de pedido
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

                newRow.dataset.tipo_pollo_id = $('#tipo_pollo_id').val();
                newRow.dataset.presentacion_pollo_id = $('#presentacion_pollo_id').val();
                newRow.dataset.precio = precio;
                newRow.dataset.subtotal = subtotal;

                const presentation = presentacion_pollos.find(presentation => presentation.id == newRow
                    .dataset.presentacion_pollo_id);
                const type = tipo_pollos.find(type => type.id == newRow.dataset.tipo_pollo_id);
                // Insertar celdas en la nueva fila
                newRow.insertCell(0).textContent = presentation.descripcion;
                newRow.insertCell(1).textContent = type.descripcion;
                newRow.insertCell(2).textContent = cantidadPollos;
                newRow.insertCell(3).textContent = pesoBruto;
                newRow.insertCell(4).textContent = numeroJabas;
                newRow.insertCell(5).textContent = tara.toFixed(2);
                newRow.insertCell(6).textContent = pesoNeto.toFixed(2);

                const deleteBtn = document.createElement('button');
                deleteBtn.textContent = 'Eliminar';
                deleteBtn.className = 'btn btn-danger btn-sm btn-delete';
                deleteBtn.setAttribute('type', 'button');

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

            $(document).on('click', '.btn-delete', function() {
                $(this).parents('tr').remove();
                updateTotals();
            })

            function updateTotals() {
                const tableBody = document.getElementById('detailsTable').getElementsByTagName('tbody')[0];
                const rows = tableBody.getElementsByTagName('tr');

                let totalChiken = 0;
                let totalWeight = 0;
                let totalTara = 0;
                let totalNetWeight = 0;
                let totalBoxes = 0;
                let subtotal = 0;

                // Sumar los valores de cada fila
                for (let i = 0; i < rows.length; i++) {
                    const cells = rows[i].getElementsByTagName('td');
                    totalChiken += parseInt(cells[2].textContent);
                    totalWeight += parseFloat(cells[3].textContent);
                    totalBoxes += parseInt(cells[4].textContent);
                    totalTara += parseFloat(cells[5].textContent);
                    totalNetWeight += parseFloat(cells[6].textContent);
                }

                // Mostrar los totales en el pie de la tabla
                document.getElementById('totalChiken').textContent = totalChiken.toFixed(2);
                document.getElementById('totalWeight').textContent = totalWeight.toFixed(2);
                document.getElementById('totalTara').textContent = totalTara.toFixed(2);
                document.getElementById('totalNetWeight').textContent = totalNetWeight.toFixed(2);
                document.getElementById('totalBoxes').textContent = totalBoxes.toFixed(2);
            }




            //Button orden de despacho
            $('#saveOrderBtn').click(function() {
                // Recoger los datos del formulario
                let clienteId = $('#cliente_id').val();
                let serieOrden = $('#serie_orden').val();
                let fechaDespacho = $('#fecha_despacho').val();
                var presentacion_pollo = $('#presentacion_pollo').val();
                var tipo_pollo_id = $('#tipo_pollo_id').val();


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
                    let cantidadPollos = $(this).find('td:eq(2)').text();
                    let pesoBruto = $(this).find('td:eq(3)').text();
                    let cantidadJabas = $(this).find('td:eq(4)').text();
                    let tara = $(this).find('td:eq(5)').text();
                    let pesoNeto = $(this).find('td:eq(6)').text();
                    let precio = $(this).data('precio');
                    let subtotalItem = $(this).data('subtotal');
                    detalles.push({
                        cantidad_pollos: cantidadPollos,
                        peso_bruto: pesoBruto,
                        cantidad_jabas: cantidadJabas,
                        tara: tara,
                        peso_neto: pesoNeto,
                        tipo_pollo_id: $(this).data('tipo_pollo_id'),
                        presentacion_pollo_id: $(this).data('presentacion_pollo_id'),
                        precio: precio,
                        subtotal: subtotalItem
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

                // Preparar los datos a enviar
                let data = {
                    cliente_id: clienteId,
                    serie_orden: serieOrden,
                    fecha_despacho: fechaDespacho,
                    presentacion_pollo: presentacion_pollo,
                    tipo_pollo_id: tipo_pollo_id,
                    cantidad_pollos: totalChiken,
                    peso_total_bruto: totalWeight,
                    cantidad_jabas: totalBoxes,
                    tara: totalTara,
                    peso_total_neto: totalNetWeight,
                    detalles: detalles, // Aquí se envían los detalles
                    _token: $('meta[name="csrf-token"]').attr('content') // Token CSRF para protección
                };

                // Enviar los datos al controlador usando AJAX
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: '¿Deseas registrar la orden?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, generar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {

                        $.ajax({
                            url: '{{ route('ordenes-de-despacho.store') }}', // Utiliza el nombre de la ruta
                            method: 'POST',
                            data: data,
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Éxito',
                                    text: response.message,
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(() => {
                                    setPrint(response.data);
                                    $('#selectDocumentTypeModal').modal('show');
                                });
                            },
                            error: function(xhr, status, error) {
                                var response = xhr.responseJSON;
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: response.message ||
                                        'Ocurrió un error',
                                });
                            }
                        });

                    }
                });


            });

            function setPrint(orden) {
                $('#btnDocumentA4').attr('href', orden.url_pdf);
                $('#btnDocumentTicket').attr('href', orden.url_ticket);
            }

            document.getElementById('btnReloadPage').addEventListener('click', function() {
                location.reload();
            });


            $('.select2').select2();


            $('#presentacion_pollo_id').change(function(el) {
                obtenerTara();
                obtenerPrecio();
            });

            $('#tipo_pollo_id').change(function(el) {
                obtenerPrecio();
                obtenerDisponibilidad();
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

            function obtenerDisponibilidad() {
                const type = $('#tipo_pollo_id').val();
                const stock = stocks.find(stock => stock.tipo_pollo_id == type);
                if (stock) {
                    $('#cantidad_disponible_tipo').val(stock.total_pollos);
                    $('#peso_disponible_tipo').val(stock.total_peso);
                }
            }







        });
    </script>
@stop


@include('admin.clientes.partials.nuevo_cliente')
