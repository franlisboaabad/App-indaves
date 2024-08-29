@extends('adminlte::page')

@section('title', 'Lista de Precios')
@section('plugins.Datatables', true)
@section('content_header')
    <h1>Lista de Precios</h1>
@stop

@section('content')
    <!-- Mensajes de éxito o error -->
    @include('partials.validaciones')

    <!-- Botón para crear un nuevo precio -->
    @can('admin.precios.create')
        <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#addPriceModal">Nuevo Precio</button>
    @endcan

    <!-- Tabla de precios -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Precios</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <table id="preciosTable" class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Descripcion</th>
                        <th>Precio</th>
                        <th>Fecha registro</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($precios as $precio)
                        <tr>
                            <td>{{ $precio->id }}</td>
                            <td>{{ $precio->descripcion }}</td>
                            <td>S/. {{ number_format($precio->precio, 2) }}</td>
                            <td>{{ $precio->created_at }}</td>
                            <td>
                                @if ($precio->estado)
                                    <span class="badge badge-success">Activo</span>
                                @else
                                    <span class="badge badge-danger">Inactivo</span>
                                @endif
                            </td>
                            <td>
                                {{-- @can('admin.precios.show')
                                    <a href="{{ route('lista-de-precios.show', $precio->id) }}" class="btn btn-info btn-sm">Ver</a>
                                @endcan --}}
                                @can('admin.precios.edit')
                                    <a href="#" class="btn btn-info btn-sm" data-toggle="modal"
                                        data-target="#editPriceModal" data-id="{{ $precio->id }}"
                                        data-precio="{{ $precio->precio }}"
                                        data-descripcion="{{ $precio->descripcion }}">Editar</a>
                                @endcan
                                @can('admin.precios.destroy')
                                    <!-- Botón para eliminar el recurso con confirmación -->
                                    <button class="btn btn-danger btn-sm btnEliminar"
                                        data-id="{{ $precio->id }}">Eliminar</button>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
    </div>


    <!-- Modal Aregar nuevo -->
    <div class="modal fade" id="addPriceModal" tabindex="-1" role="dialog" aria-labelledby="addPriceModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPriceModalLabel">Nuevo Precio</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="#" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="precio">Precio:</label>
                            <input type="number" step="0.01" class="form-control" id="precio" name="precio"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="descripcion">Descripción:</label>
                            <input type="text" class="form-control" id="descripcion" name="descripcion">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Editar precio -->
    <div class="modal fade" id="editPriceModal" tabindex="-1" role="dialog" aria-labelledby="editPriceModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPriceModalLabel">Editar Precio</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editPriceForm" method="POST" action="{{ route('lista-de-precios.update', 'id') }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="precio">Precio</label>
                            <input type="number"  step="0.01" class="form-control" id="precio" name="precio" required>
                        </div>
                        <div class="form-group">
                            <label for="descripcion">Descripción</label>
                            <input type="text" class="form-control" id="descripcion" name="descripcion" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


@stop

@section('css')
    <!-- Estilos específicos -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
@stop

@section('js')
    <!-- Scripts específicos -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(function() {
            $("#preciosTable").DataTable({
                "responsive": true,
                "autoWidth": false,
            });


            // Manejo del envío del formulario en el modal
            $('#addPriceModal form').on('submit', function(event) {
                event.preventDefault(); // Evita el envío tradicional del formulario

                var form = $(this);
                var formData = form.serialize();
                var actionUrl = form.attr('action');

                $.ajax({
                    url: actionUrl,
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Éxito',
                            text: response.message || 'El precio ha sido agregado exitosamente.',
                            showConfirmButton: false,
                            timer:1500
                        }).then(function() {
                            $('#addPriceModal').modal('hide');
                            // Opcionalmente, recargar la tabla o hacer otras actualizaciones
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON.message ||
                                'Hubo un problema al agregar el precio.',
                        });
                    }
                });
            });


            //Manejo del modal para obtener la informacion
            $('#editPriceModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Botón que activó la modal
                var id = button.data('id'); // Extraer datos de atributos data-*
                var precio = button.data('precio');
                var descripcion = button.data('descripcion');

                var modal = $(this);
                modal.find('#editPriceForm').attr('data-id', id); // Guarda el ID en el formulario
                modal.find('#precio').val(precio);
                modal.find('#descripcion').val(descripcion);
            });

            //Editar precio
            $('#editPriceForm').on('submit', function(event) {
                event.preventDefault(); // Evita que el formulario se envíe de manera tradicional

                var form = $(this);
                var id = form.attr('data-id'); // Obtiene el ID del formulario
                var url = '/lista-de-precios/' + id; // Construye la URL para la solicitud AJAX

                $.ajax({
                    url: url,
                    type: 'PUT',
                    data: form.serialize(), // Serializa los datos del formulario
                    success: function(response) {
                        // Muestra una alerta de éxito
                        Swal.fire({
                            title: 'Éxito!',
                            text: 'El precio se ha actualizado correctamente.',
                            icon: 'success',
                            showConfirmButton: false,
                            timer:1500
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload(); // Recarga la página para actualizar la lista
                            }
                        });
                    },
                    error: function(xhr) {
                        // Muestra una alerta de error
                        Swal.fire({
                            title: 'Error!',
                            text: 'Hubo un problema al actualizar el precio.',
                            icon: 'error',
                            showConfirmButton: false,
                            timer:1500
                        });
                    }
                });
            });


            //eliminar precio
            $(document).on('click', '.btnEliminar', function() {
                var precioId = $(this).data('id'); // Obtener el ID del precio
                var url = '{{ route('lista-de-precios.destroy', ':id') }}'.replace(':id', precioId);
                var token = '{{ csrf_token() }}'; // CSRF token for AJAX requests

                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "Una vez eliminado, no podrás recuperar este registro.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            method: 'POST',
                            data: {
                                _token: token,
                                _method: 'DELETE'
                            },
                            success: function(response) {
                                Swal.fire({
                                    title:'¡Eliminado!',
                                    text:response.message || 'El precio ha sido eliminado.',
                                    icon:'success',
                                    showConfirmButton: false,
                                    timer:1500
                                }

                                ).then(function() {
                                    location
                                        .reload(); // Opcional: Recargar la página para reflejar los cambios
                                });
                            },
                            error: function(xhr) {
                                Swal.fire(
                                    'Error',
                                    xhr.responseJSON.message ||
                                    'Hubo un problema al eliminar el precio.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });



        });
    </script>
@stop
