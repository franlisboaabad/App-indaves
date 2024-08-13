@extends('adminlte::page')

@section('title', 'Empresa')
@section('plugins.Datatables', true)
@section('content_header')
    <h1>Empresa</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <!-- Botón para abrir el modal -->
            {{-- <a class="btn btn-primary btn-sm" href="#" id="openModal">Añadir Empresa</a> --}}

            <table class="table" id="table-clientes">
                <thead>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Dirección</th>
                    <th>Celular</th>
                    <th>Email</th>
                    <th>Web site</th>
                    <th>Estado</th>
                    <th>Opciones</th>
                </thead>
                <tbody>
                    @foreach ($empresas as $empresa)
                        <tr>
                            <td>{{ $empresa->id }}</td>
                            <td>{{ $empresa->name }}</td>
                            <td>{{ $empresa->address }}</td>
                            <td>{{ $empresa->phone }}</td>
                            <td>{{ $empresa->email }}</td>
                            <td>{{ $empresa->website }}</td>
                            <td>
                                @if ($empresa->status == 1)
                                    <span class="badge bg-success">Activo</span>
                                @elseif($empresa->status == 0)
                                    <span class="badge bg-secondary">Inactivo</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('empresas.edit', $empresa ) }}" class="btn btn-info btn-sm">Editar</a>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>



    <!-- Modal HTML Add -->
    <div class="modal fade" id="addEmpresaModal" tabindex="-1" role="dialog" aria-labelledby="addEmpresaModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addEmpresaModalLabel">Añadir Nueva Empresa</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addEmpresaForm" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="name">Nombre</label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Nombre de la empresa" required>
                        </div>
                        <div class="form-group">
                            <label for="address">Dirección</label>
                            <input type="text" class="form-control" id="address" name="address"
                                placeholder="Dirección completa" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Teléfono</label>
                            <input type="text" class="form-control" id="phone" name="phone"
                                placeholder="Número de teléfono">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="Correo electrónico">
                        </div>
                        <div class="form-group">
                            <label for="website">Sitio Web</label>
                            <input type="text" class="form-control" id="website" name="website"
                                placeholder="URL del sitio web">
                        </div>
                        <div class="form-group">
                            <label for="description">Descripción</label>
                            <textarea class="form-control" id="description" name="description" rows="3"
                                placeholder="Descripción de la empresa"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="logo">Logo</label>
                            <input type="file" class="form-control-file" id="logo" name="logo">
                        </div>

                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-success">Guardar</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal HTML para editar -->
    <div class="modal fade" id="editEmpresaModal" tabindex="-1" role="dialog" aria-labelledby="editEmpresaModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editEmpresaModalLabel">Editar Empresa</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editEmpresaForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="empresaId" name="id">

                        <div class="form-group">
                            <label for="editName">Nombre</label>
                            <input type="text" class="form-control" id="editName" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="editAddress">Dirección</label>
                            <input type="text" class="form-control" id="editAddress" name="address" required>
                        </div>
                        <div class="form-group">
                            <label for="editPhone">Teléfono</label>
                            <input type="text" class="form-control" id="editPhone" name="phone">
                        </div>
                        <div class="form-group">
                            <label for="editEmail">Email</label>
                            <input type="email" class="form-control" id="editEmail" name="email">
                        </div>
                        <div class="form-group">
                            <label for="editWebsite">Sitio Web</label>
                            <input type="text" class="form-control" id="editWebsite" name="website">
                        </div>
                        <div class="form-group">
                            <label for="editDescription">Descripción</label>
                            <textarea class="form-control" id="editDescription" name="description" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="editLogo">Logo</label>
                            <input type="file" class="form-control-file" id="editLogo" name="logo">
                        </div>

                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-success">Editar</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



@stop

@section('cs')
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script> <!-- Asegúrate de tener Bootstrap JS --> --}}

    <script>
        $(document).ready(function() {
            // Abre el modal cuando se hace clic en el botón
            $('#openModal').on('click', function(e) {
                e.preventDefault();
                $('#addEmpresaModal').modal('show');
            });

            // Maneja el envío del formulario
            $('#addEmpresaForm').on('submit', function(e) {
                e.preventDefault();

                // Obtén los datos del formulario
                var formData = new FormData(this);

                // Envia los datos mediante AJAX
                $.ajax({
                    url: '{{ route('empresas.store') }}', // Asegúrate de que esta sea la ruta correcta para tu controlador
                    type: 'POST',
                    data: formData,
                    processData: false, // Necesario para que jQuery no intente procesar los datos
                    contentType: false, // Necesario para enviar archivos
                    success: function(response) {
                        // Muestra un mensaje de éxito
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: 'La empresa se ha registrado exitosamente.',
                            timer: 3000,
                            showConfirmButton: false
                        }).then(function() {
                            $('#addEmpresaModal').modal('hide');
                            location
                                .reload(); // Recarga la página para mostrar los cambios
                        });
                    },
                    error: function(xhr) {
                        // Muestra un mensaje de error
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'No se pudo registrar la empresa. Por favor, intente nuevamente.',
                        });
                    }
                });
            });
        });
    </script>



@stop
