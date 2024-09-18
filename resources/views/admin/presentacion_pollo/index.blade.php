@extends('adminlte::page')
@section('plugins.Datatables', true)
@section('title', 'Presentacion de pollo')


@section('content_header')
    <h1>Presentacion de pollo</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered" id="presentacionTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Descripcion</th>
                                <th>Tara</th>
                                <th>Estado</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($presentaciones as $presentacion)
                                <tr>
                                    <td>{{ $presentacion->id }}</td>
                                    <td>{{ $presentacion->descripcion }}</td>
                                    <td>{{ $presentacion->tara }}</td>
                                    <td>{{ $presentacion->estado }}</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                            data-bs-target="#editModal" data-id="{{ $presentacion->id }}"
                                            data-descripcion="{{ $presentacion->descripcion }}"
                                            data-tara="{{ $presentacion->tara }}" data-estado="{{ $presentacion->estado }}">
                                            Editar
                                        </a>
                                        <a href="#" class="btn btn-sm btn-danger">Eliminar</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Editar Presentación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        <input type="hidden" id="presentacionId">
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <input type="text" class="form-control" id="descripcion" required>
                        </div>
                        <div class="mb-3">
                            <label for="tara" class="form-label">Tara</label>
                            <input type="text" class="form-control" id="tara" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="saveChanges">Guardar cambios</button>
                </div>
            </div>
        </div>
    </div>



@stop

@section('css')
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $("#presentacionTable").DataTable({
                "responsive": true,
                "autoWidth": false,
                language: {
                    "url": "/js/spanish.json"
                },
            });


            //abrir modal
            $('#editModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Botón que activó el modal
                var id = button.data('id');
                var descripcion = button.data('descripcion');
                var tara = button.data('tara');
                var estado = button.data('estado');

                // Rellenar el formulario del modal
                var modal = $(this);
                modal.find('#presentacionId').val(id);
                modal.find('#descripcion').val(descripcion);
                modal.find('#tara').val(tara);
            });


            // Cuando se haga clic en el botón de guardar cambios
            $('#saveChanges').on('click', function() {
                var id = $('#presentacionId').val();
                var descripcion = $('#descripcion').val();
                var tara = $('#tara').val();

                $.ajax({
                    url: '/presentacion-pollo/' + id,
                    type: 'PUT',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr(
                        'content'), // Token CSRF para la solicitud
                        descripcion: descripcion,
                        tara: tara,
                        id: id
                    },
                    success: function(response) {

                        // Cierra el modal
                        $('#editModal').modal('hide');
                        location.reload();
                    },
                    error: function(xhr) {
                        console.log('Error:', xhr.responseText);
                    }
                });
            });



        });
    </script>
@stop
