@extends('adminlte::page')
@section('plugins.Select2', true)
@section('title', 'Orden de ingreso')

@section('content_header')
    <h1>Orden de ingreso</h1>
@stop

@section('content')

    <div class="card">
        <div class="card-body">
            <form id="ventaForm" method="POST" action="{{ route('ordenes-de-despacho.store') }}">
                @csrf
                <input type="hidden" name="type" value="ingreso">
                <div class="row">

                    <!-- Serie de Venta -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="serie_orden">Serie de Orden</label>
                            <input type="text" id="serie_orden" name="serie_orden" class="form-control" required>
                            @error('serie_orden')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Fecha de Venta -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="fecha_despacho">Fecha de Ingreso</label>
                            <input type="date" id="fecha_despacho" name="fecha_despacho" class="form-control" required>
                            @error('fecha_despacho')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row mt-5">
                    <div class="col-md-2 mb-5">
                        <div class="form-grup">
                            <label for="presentacion_pollo_id">Presentacion de Pollo:</label>
                            <select id="presentacion_pollo_id" name="presentacion_pollo_id" class="form-control">
                                @foreach ($presentacionPollos as $presentacionPollo)
                                    <option
                                        value="{{ $presentacionPollo->id }}"> {{ $presentacionPollo->descripcion }} </option>
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
                    <!-- Número de Jabas -->
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
                            <label for="cantidad_pollos">Número de Aves</label>
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
                            <input type="number" step="0.01" id="peso_bruto" name="peso_bruto" class="form-control">
                            @error('peso_bruto')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>


                    <!-- Botón para agregar detalles -->
                    <div class="col-md-2 pt-4">
                        <button type="button" id="addDetailBtn" class="btn btn-primary mt-2">Agregar al Detalle</button>
                    </div>

                </div>

                <!-- Tabla de Detalles -->
                <div class="mt-4">
                    <table class="table table-bordered" id="detailsTable">
                        <thead>
                        <tr>
                            <th>Presentación</th>
                            <th>Tipo</th>
                            <th>Número de Jabas</th>
                            <th>Número de Aves</th>
                            <th>Peso Bruto</th>
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                        <tr>
                            <th id="presentation">0.00</th>
                            <th id="type">0.00</th>
                            <th id="totalBoxes">0</th>
                            <th id="totalChiken">0.00</th>
                            <th id="totalWeight">0.00</th>
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

        document.addEventListener('DOMContentLoaded', function () {

            obtenerPrecio();
            obtenerTara();

            function getLocalDateString() {
                const today = new Date();
                const year = today.getFullYear();
                const month = String(today.getMonth() + 1).padStart(2, '0'); // Meses empiezan en 0
                const day = String(today.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            }
            document.getElementById('fecha_despacho').value = getLocalDateString();

            document.getElementById('addDetailBtn').addEventListener('click', function () {
                const cantidadPollos = document.getElementById('cantidad_pollos').value;
                const pesoBruto = document.getElementById('peso_bruto').value;
                const numeroJabas = document.getElementById('cantidad_jabas').value;
                // Validar que los campos no estén vacíos
                if (!cantidadPollos || !pesoBruto || !numeroJabas) {
                    alert('Por favor, complete todos los campos.');
                    return;
                }
                // Calcular el peso neto
                const pesoNeto = pesoBruto;


                // Crear una nueva fila para la tabla de detalles
                const tableBody = document.getElementById('detailsTable').getElementsByTagName('tbody')[0];
                const newRow = tableBody.insertRow();

                // Insertar celdas en la nueva fila
                newRow.insertCell(0).textContent = cantidadPollos;
                newRow.insertCell(1).textContent = pesoBruto;
                newRow.insertCell(2).textContent = numeroJabas;
                newRow.insertCell(3).textContent = pesoNeto.toFixed(2);

                newRow.dataset.tipo_pollo_id = $('#tipo_pollo_id').val();
                newRow.dataset.presentacion_pollo_id = $('#presentacion_pollo_id').val();


                // Crear el botón de eliminar y añadirlo a la última celda
                const deleteBtn = document.createElement('button');
                deleteBtn.textContent = 'Eliminar';
                deleteBtn.className = 'btn btn-danger btn-sm';
                deleteBtn.onclick = function () {
                    // Eliminar la fila de la tabla
                    const rowIndex = newRow.rowIndex;
                    if (rowIndex > 0) { // Asegurarse de que el índice sea válido
                        tableBody.deleteRow(rowIndex - 1);
                        updateTotals();
                    }
                };
                newRow.insertCell(4).appendChild(deleteBtn);

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
                    subtotal +=parseFloat(cells[6].textContent);
                }

                // Mostrar los totales en el pie de la tabla
                document.getElementById('totalChiken').textContent = totalChiken.toFixed(2);
                document.getElementById('totalWeight').textContent = totalWeight.toFixed(2);
                document.getElementById('totalTara').textContent = totalTara.toFixed(2);
                document.getElementById('totalNetWeight').textContent = totalNetWeight.toFixed(2);
                document.getElementById('totalBoxes').textContent = totalBoxes;
                document.getElementById('subtotal').textContent = subtotal.toFixed(2);
            }

            //fin detalle pedido

            //buscar documento dni , ruc
            $('#searchDocumentBtn').on('click', function () {
                var documento = $('#documento').val();
                var tipoDocumento = $('#tipo_documento').val();

                $.ajax({
                    url: "{{ route('clientes.search') }}",
                    type: 'POST',
                    data: {
                        documento: documento,
                        tipo_documento: tipoDocumento,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (response) {
                        if (response.success) {
                            $('#nombre_comercial').val(response.data.razon_social);
                            $('#razon_social').val(response.data.razon_social);
                            $('#direccion').val(response.data.direccion);
                            $('#departamento').val(response.data.departamento);
                            $('#provincia').val(response.data.provincia);
                            $('#distrito').val(response.data.distrito);

                            Swal.fire({
                                title: 'Información encontrada',
                                text: 'Datos actualizados.',
                                icon: 'info',
                                confirmButtonText: 'OK'
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: response.message,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function (xhr) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'No se pudo realizar la búsqueda.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });
            //end search


            //guardar cliente
            $('#saveClientBtn').on('click', function () {
                var formData = new FormData($('#createClientForm')[0]);

                $.ajax({
                    url: "{{ route('clientes.store') }}", // Cambia la URL al endpoint adecuado
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        if (response.success) {
                            Swal.fire({
                                title: 'Éxito!',
                                text: 'Cliente creado correctamente.',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $('#createClientModal').modal('hide');
                                    $('#createClientForm')[0].reset();

                                    // Añadir el nuevo cliente al select
                                    addClientToSelect(response.cliente);
                                }
                            });
                        }
                    },
                    error: function (xhr) {
                        if (xhr.status === 400) {
                            // Manejar el caso de cliente ya existente
                            Swal.fire({
                                title: 'Error!',
                                text: xhr.responseJSON.message ||
                                    'Cliente con el mismo documento ya existe.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        } else {
                            // Manejar otros errores de validación
                            var errors = xhr.responseJSON.errors;
                            var errorMessage = '';
                            if (errors) {
                                $.each(errors, function (key, value) {
                                    errorMessage += value[0] + '\n';
                                });
                            } else {
                                errorMessage = 'Ha ocurrido un error inesperado.';
                            }

                            Swal.fire({
                                title: 'Error!',
                                text: errorMessage,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    }
                });
            });


            // Función para añadir el cliente al select
            function addClientToSelect(cliente) {
                var $select = $('#cliente_id');
                var newOption = new Option(cliente.nombre_comercial, cliente.id, true, true);
                $select.append(newOption).trigger('change');
            }

            //Button orden de despacho
            $('#saveOrderBtn').click(function () {
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
                $('#detailsTable tbody tr').each(function () {
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
                    subtotal : subtotal,
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
                            success: function (response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Éxito',
                                    text: response.message,
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(() => {
                                    setPdfUrl(response.pdf_url_a4, response
                                        .pdf_url_ticket);
                                    $('#selectDocumentTypeModal').modal('show');
                                });
                            },
                            error: function (xhr, status, error) {
                                var response = xhr.responseJSON;
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: response.message || 'Ocurrió un error',
                                });
                            }
                        });

                    }
                });


            });

            //fin button orde de despacho

            //ruta de pdf
            // Variable global para guardar la URL del PDF
            let pdfUrl_a4 = '';
            let pdfUrl_ticket = ''

            // Suponiendo que la URL se obtiene cuando se registra el documento
            function setPdfUrl(url_a4, url_ticket) {
                pdfUrl_a4 = url_a4;
                pdfUrl_ticket = url_ticket
            }


            document.getElementById('btnDocumentA4').addEventListener('click', function () {
                if (pdfUrl_a4) {
                    window.open(pdfUrl_a4, '_blank');
                } else {
                    alert('La URL del documento no está disponible.');
                }
            });


            document.getElementById('btnDocumentTicket').addEventListener('click', function () {
                if (pdfUrl_ticket) {
                    window.open(pdfUrl_ticket, '_blank');
                } else {
                    alert('La URL del documento no está disponible.');
                }
            });

            document.getElementById('btnReloadPage').addEventListener('click', function () {
                location.reload();
            });


            $('.select2').select2();


            $('#presentacion_pollo_id').change(function (el) {
                obtenerTara();
                obtenerPrecio();
            });

            $('#tipo_pollo_id').change(function (el) {
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
        });
    </script>
@stop
