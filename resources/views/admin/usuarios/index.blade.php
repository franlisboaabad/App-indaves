@extends('adminlte::page')

@section('title', 'Lista de usuarios')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <p>Lista de usuarios</p>

      <!-- Botón para abrir el modal -->
      <a href="#" class="btn btn-primary mb-3" data-toggle="modal" data-target="#createUserModal">Nuevo Usuario</a>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Usuarios</h3>
        </div>
        <div class="card-body">
            <!-- Modal para crear un nuevo usuario -->
            <div class="modal fade" id="createUserModal" tabindex="-1" role="dialog" aria-labelledby="createUserModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="createUserModalLabel">Crear Nuevo Usuario</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="createUserForm">
                                @csrf
                                <div class="form-group">
                                    <label for="name">Nombre</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                <div class="form-group">
                                    <label for="password">Contraseña</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                <div class="form-group">
                                    <label for="password_confirmation">Confirmar Contraseña</label>
                                    <input type="password" class="form-control" id="password_confirmation"
                                        name="password_confirmation" required>
                                </div>
                                <button type="submit" class="btn btn-success">Guardar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


            <table class="table table-bordered" id="table-usuarios">
                <thead>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Opciones</th>
                </thead>
                <tbody>
                    @foreach ($usuarios as $usuario)
                        <tr>
                            <td>{{ $usuario->id }}</td>
                            <td>{{ $usuario->name }}</td>
                            <td>{{ $usuario->email }}</td>
                            <td>
                                <a href="{{ route('usuarios.edit', $usuario) }}" class="btn btn-info btn-sm">Editar</a>

                                <form id="delete-form-{{ $usuario->id }}"
                                    action="{{ route('usuarios.destroy', $usuario) }}" method="POST"
                                    style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm"
                                        onclick="confirmDelete({{ $usuario->id }})">Eliminar</button>
                                </form>


                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>






@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
    {{-- <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script> --}}

    <script>
        $(document).ready(function() {

            $('#createUserForm').on('submit', function(event) {
                event.preventDefault(); // Prevenir el comportamiento por defecto del formulario

                var formData = $(this).serialize(); // Obtener los datos del formulario

                $.ajax({
                    url: '{{ route('usuarios.store') }}', // Asegúrate de que esta ruta esté definida en tus rutas
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: 'Usuario creado exitosamente.',
                            timer: 3000,
                            showConfirmButton: false
                        }).then(function() {
                            $('#createUserModal').modal('hide'); // Cerrar el modal
                            location
                                .reload(); // Recargar la página para ver el nuevo usuario
                        });
                    },
                    error: function(xhr) {
                        var errors = xhr.responseJSON.errors;
                        var errorMessage = '';

                        // Construir un mensaje de error
                        $.each(errors, function(key, value) {
                            errorMessage += value + '<br>';
                        });

                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            html: errorMessage,
                            timer: 5000,
                            showConfirmButton: true
                        });
                    }
                });
            });




        });
    </script>

    <script>
        function confirmDelete(userId) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminarlo!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Realizar la solicitud AJAX para eliminar el usuario
                    $.ajax({
                        url: '{{ url('usuarios') }}/' + userId,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Eliminado!',
                                text: 'Usuario ha sido eliminado.',
                                timer: 3000,
                                showConfirmButton: false
                            }).then(function() {
                                location.reload(); // Recargar la página para ver el nuevo usuario
                            });
                        },
                        error: function(xhr) {
                            Swal.fire(
                                'Error!',
                                'No se pudo eliminar el usuario.',
                                'error'
                            );
                        }
                    });
                }
            });
        }
    </script>

@stop
