@extends('adminlte::page')

@section('title', 'Ordenes de ingreso')
@section('plugins.Datatables', true)
@section('content_header')
    <h1>Orden de Ingreso</h1>
@stop

@section('content')

    <div class="row">
        <div class="col-md-12">

            <a href="{{ route('ordenes-ingreso.create') }}" class="btn btn-primary mb-3" >Nueva Orden</a>


            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Ordenes</h3>
                </div>
                <div class="card-body">
                    <table class="table" id="table-ordenes">
                        <thead>
                            <th>#</th>
                            <th>N° guía</th>
                            <th>Peso Bruto</th>
                            <th>Peso Tara</th>
                            <th>Peso Neto</th>
                            <th>Total Aves</th>
                            <th>Estado</th>
                            <th>Fecha de registro</th>
                            <th>Acciones</th>
                        </thead>
                        <tbody>
                            @foreach ($ordenes_ingreso as $orden)
                                <tr>
                                    <td>{{ $orden->id }}</td>
                                    <td>{{ $orden->numero_guia }}</td>
                                    <td>{{ $orden->peso_bruto }}</td>
                                    <td>{{ $orden->peso_tara }}</td>
                                    <td>{{ $orden->peso_neto }}</td>
                                    <td>{{ $orden->detalle_sum_cantidad_pollos }}</td>
                                    <td>
                                        @if ($orden->estado)
                                            <span class="badge badge-success">Activo</span>
                                        @endif
                                    </td>
                                    <td>{{ $orden->created_at }}</td>
                                    <td>


                                        <form action="{{ route('ordenes-ingreso.destroy', $orden) }}" method="POST" class="delete-form" id="delete-form-{{ $orden->id }}">
                                            @csrf
                                            @method('DELETE')

                                            <a href="{{ route('ordenes-ingreso.show', $orden) }}" class="btn btn-primary btn-primary btn-sm mr-2">Ver</a>
                                            <button type="button" class="btn btn-danger btn-sm btn-delete" data-id="{{ $orden->id }}">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>



    <!-- Modal registro -->
    <div class="modal fade" id="nuevoOrdenModal" tabindex="-1" role="dialog" aria-labelledby="nuevoOrdenModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="nuevoOrdenModalLabel">Registrar Nueva Orden</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="ordenIngresoForm">
                    <div class="modal-body">
                        <!-- Campos del formulario -->
                        <div class="form-group">
                            <label for="numero_guia">Número de guía</label>
                            <input type="number" class="form-control" id="numero_guia" name="numero_guia" required>
                        </div>

                        <div class="form-group">
                            <label for="cantidad_jabas">Cantidad de Jabas</label>
                            <input type="number" class="form-control" id="cantidad_jabas" name="cantidad_jabas" required>
                        </div>
                        <div class="form-group">
                            <label for="cantidad_pollos_pendientes">Cantidad de Pollos Pendientes</label>
                            <input type="number" readonly
                                   class="form-control" id="cantidad_pollos_pendientes"
                                   name="cantidad_pollos_pendientes"
                                   value="{{$cantidad_pollos_pendientes}}"
                            >
                        </div>
                        <div class="form-group">
                            <label for="cantidad_pollos">Cantidad de Pollos</label>
                            <input type="number" class="form-control" id="cantidad_pollos" name="cantidad_pollos" required>
                        </div>
                        <div class="form-group">
                            <label for="peso_total">Peso Total (kg)</label>
                            <input type="number" step="0.01" class="form-control" id="peso_total" name="peso_total"
                                required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-success">Registrar Orden</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('css')

@stop

@section('js')
    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $('#table-ordenes').DataTable({
                language: {
                    "url": "/js/spanish.json"
                },
            });


            // Manejar el envío del formulario
            $('#ordenIngresoForm').on('submit', function(e) {
                e.preventDefault(); // Evitar el envío del formulario de manera tradicional

                // Obtener los datos del formulario
                const formData = $(this).serialize();

                // Enviar los datos al servidor usando AJAX
                $.ajax({
                    url: '/ordenes-ingreso', // Ruta a la API de Laravel para almacenar la orden
                    method: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                            'content') // Añadir el token CSRF para la seguridad
                    },
                    success: function(response) {
                        // Mostrar un mensaje de éxito con SweetAlert
                        Swal.fire({
                            title: 'Éxito!',
                            text: 'La orden ha sido registrada correctamente.',
                            icon: 'success',
                            showConfirmButton: false, // Oculta el botón de confirmación
                            timer: 1500, // Tiempo en milisegundos antes de recargar la página
                            // timerProgressBar: true // Opcional: muestra una barra de progreso para el temporizador
                        }).then(() => {
                            // Recargar la página después de que el temporizador termine
                            $('#nuevoOrdenModal').modal('hide');
                            $('#ordenIngresoForm')[0].reset();
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        // Mostrar un mensaje de error con SweetAlert
                        Swal.fire({
                            title: 'Error!',
                            text: 'Hubo un problema al registrar la orden. Por favor, inténtalo de nuevo.',
                            icon: 'error',
                            confirmButtonText: 'Ok'
                        });
                    }
                });
            });


            $('.btn-delete').on('click', function(e) {
                e.preventDefault(); // Evita que el formulario se envíe de manera tradicional

                const form = $(this).closest('form'); // Encuentra el formulario más cercano

                // Mostrar el diálogo de confirmación con SweetAlert
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¡No podrás revertir esto!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminar!',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Realizar la solicitud AJAX para eliminar el registro
                        $.ajax({
                            url: form.attr('action'), // URL del formulario
                            type: 'POST', // Usar POST para enviar el formulario
                            data: form.serialize(), // Serializa los datos del formulario
                            success: function(response) {
                                // Mostrar un mensaje de éxito y recargar la página
                                Swal.fire(
                                    'Eliminado!',
                                    'El registro ha sido eliminado.',
                                    'success'
                                ).then(() => {
                                    location
                                .reload(); // Recargar la página para reflejar los cambios
                                });
                            },
                            error: function(xhr) {
                                // Mostrar un mensaje de error
                                Swal.fire(
                                    'Error!',
                                    'Ocurrió un error. Por favor, intenta nuevamente.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });
            //fin eliminar


        });
    </script>
@stop
