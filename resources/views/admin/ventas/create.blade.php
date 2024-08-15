@extends('adminlte::page')
@section('plugins.Select2', true)
@section('title', 'Registrar Venta')

@section('content_header')
    <h1>Registrar Venta</h1>
@stop

@section('content')


    <div class="card">
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
                    <div class="col-md-6">
                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#createClientModal"
                            style="margin-top: 35px">
                            <i class="fa fa-user"></i> Nuevo Cliente
                        </button>
                    </div>
                    <div class="col-md-2"></div>

                    <!-- Cantidad de Pollos -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="cantidad_pollos">Cantidad de Pollos</label>
                            <input type="number" id="cantidad_pollos" name="cantidad_pollos" class="form-control" required>
                            @error('cantidad_pollos')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Peso Bruto -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="peso_bruto">Peso Bruto</label>
                            <input type="number" step="0.01" id="peso_bruto" name="peso_bruto" class="form-control"
                                required>
                            @error('peso_bruto')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Número de Jabas -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="numero_jabas">Número de Jabas</label>
                            <input type="number" id="numero_jabas" name="numero_jabas" class="form-control" required>
                            @error('numero_jabas')
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
                                <th>Peso Neto</th>
                                <th>Acciones</th>
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
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>


                <!-- Botón de Submit -->
                <br>
                <button type="submit" class="btn btn-success">Registrar Venta</button>
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

            //variables

            // funciones

            // metodos

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
            document.getElementById('fecha_venta').value = getLocalDateString();



            //funcionalidad para detalle de pedido


            document.getElementById('addDetailBtn').addEventListener('click', function() {
                // Obtener los valores de los inputs
                var cantidadPollos = document.getElementById('cantidad_pollos').value;
                var pesoBruto = document.getElementById('peso_bruto').value;
                var numeroJabas = document.getElementById('numero_jabas').value;

                // Tara por defecto
                var taraPorDefecto = 6; // 6 kg por jaba

                // Validar que los campos no estén vacíos
                if (!cantidadPollos || !pesoBruto || !numeroJabas) {
                    alert('Por favor, complete todos los campos.');
                    return;
                }

                // Calcular la tara
                var tara = numeroJabas * taraPorDefecto;

                // Calcular el peso neto
                var pesoNeto = pesoBruto - tara;

                // Crear una nueva fila para la tabla de detalles
                var tableBody = document.getElementById('detailsTable').getElementsByTagName('tbody')[0];
                var newRow = tableBody.insertRow();

                // Insertar celdas en la nueva fila
                newRow.insertCell(0).textContent = cantidadPollos;
                newRow.insertCell(1).textContent = pesoBruto;
                newRow.insertCell(2).textContent = numeroJabas;
                newRow.insertCell(3).textContent = tara.toFixed(2);
                newRow.insertCell(4).textContent = pesoNeto.toFixed(2);

                // Crear el botón de eliminar y añadirlo a la última celda
                var deleteBtn = document.createElement('button');
                deleteBtn.textContent = 'Eliminar';
                deleteBtn.className = 'btn btn-danger btn-sm';
                deleteBtn.onclick = function() {
                    // Eliminar la fila de la tabla
                    var rowIndex = newRow.rowIndex;
                    if (rowIndex > 0) { // Asegurarse de que el índice sea válido
                        tableBody.deleteRow(rowIndex - 1);
                        updateTotals();
                    }
                };
                newRow.insertCell(5).appendChild(deleteBtn);

                // Limpiar los campos del formulario
                document.getElementById('cantidad_pollos').value = '';
                document.getElementById('peso_bruto').value = '';
                document.getElementById('numero_jabas').value = '';
                document.getElementById('cantidad_pollos').select();

                // Actualizar los totales
                updateTotals();
            });

            function updateTotals() {
                var tableBody = document.getElementById('detailsTable').getElementsByTagName('tbody')[0];
                var rows = tableBody.getElementsByTagName('tr');
                var totalWeight = 0;
                var totalTara = 0;
                var totalNetWeight = 0;
                var totalBoxes = 0;

                // Sumar los valores de cada fila
                for (var i = 0; i < rows.length; i++) {
                    var cells = rows[i].getElementsByTagName('td');
                    totalWeight += parseFloat(cells[1].textContent);
                    totalTara += parseFloat(cells[3].textContent);
                    totalNetWeight += parseFloat(cells[4].textContent);
                    totalBoxes += parseInt(cells[2].textContent);
                }

                // Mostrar los totales en el pie de la tabla
                document.getElementById('totalWeight').textContent = totalWeight.toFixed(2);
                document.getElementById('totalTara').textContent = totalTara.toFixed(2);
                document.getElementById('totalNetWeight').textContent = totalNetWeight.toFixed(2);
                document.getElementById('totalBoxes').textContent = totalBoxes;
            }



            //fin detalle pedido



            $('#searchDocumentBtn').on('click', function() {
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
                    success: function(response) {
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
                    error: function(xhr) {
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



            $('#saveClientBtn').on('click', function() {
                var formData = new FormData($('#createClientForm')[0]);

                $.ajax({
                    url: "{{ route('clientes.store') }}", // Cambia la URL al endpoint adecuado
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
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
                    error: function(xhr) {
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
                                $.each(errors, function(key, value) {
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

            $('.select2').select2();

        });
    </script>
@stop