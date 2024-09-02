@extends('adminlte::page')

@section('title', 'Lista de clientes')
@section('plugins.Datatables', true)
@section('content_header')
    <h1>Listado de clientes</h1>
@stop

@section('content')

    <a href="#" class="btn btn-primary mb-3" data-toggle="modal" data-target="#createClientModal">Agregar Nuevo
        Cliente</a>


    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Clientes</h3>
        </div>
        <div class="card-body">
            <!-- Botón para abrir el modal -->
            <table class="table table-bordered" id="table-clientes">
                <thead>
                <th>#</th>
                <th>Tipo documento</th>
                <th>Documento</th>
                <th>Nombre comercial</th>
                <th>Razon Social</th>
                <th>Dirección</th>
                <th>Estado</th>
                <th>Opc</th>
                </thead>
                <tbody>
                @foreach ($clientes as $cliente)
                    <tr>
                        <td>{{ $cliente->id }}</td>
                        <td>{{ $cliente->tipo_documento }}</td>
                        <td>{{ $cliente->documento }}</td>

                        <td>{{ $cliente->nombre_comercial }}</td>
                        <td>{{ $cliente->razon_social }}</td>
                        <td>{{ $cliente->direccion }}</td>

                        <td>
                            @if ($cliente->estado)
                                <span class="badge badge-success">Activo</span>
                            @else
                                <span class="badge badge-danger">Inactivo</span>
                            @endif
                        </td>
                        <td>
                            <form action="" method="POST">

                                {{-- @can('admin.clientes.show')
                                    <a href="{{ route('clientes.show', $cliente) }}" class="btn btn-warning btn-xs">Ver</a>
                                @endcan --}}

                                @can('admin.clientes.edit')
                                    <a href="{{ route('clientes.edit', $cliente) }}"
                                       class="btn btn-sm btn-info">Editar</a>
                                @endcan

                                @can('admin.clientes.destroy')
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm btn-delete"
                                            data-url="{{ route('clientes.destroy', $cliente) }}">Eliminar
                                    </button>
                                @endcan
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

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
                                           class="form-control"
                                           required>
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
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" id="saveClientBtn" class="btn btn-success">Guardar</button>
                </div>
            </div>
        </div>
    </div>

@stop

@section('css')
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function () {

            $('#table-clientes').DataTable();

            $('#saveClientBtn').on('click', function () {
                const formData = new FormData($('#createClientForm')[0]);

                $.ajax({
                    url: "{{ route('clientes.store') }}", // Cambia la URL al endpoint adecuado
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        Swal.fire({
                            title: 'Éxito!',
                            text: 'Cliente creado correctamente.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $('#createClientModal').modal('hide');
                                $('#createClientForm')[0].reset();
                                // Opcional: Refrescar la página o actualizar la tabla de clientes
                                location.reload(); // Recargar la página después de que el alert se haya mostrado
                            }
                        });
                    },
                    error: function (xhr) {
                        var errors = xhr.responseJSON.errors;
                        var errorMessage = '';
                        $.each(errors, function (key, value) {
                            errorMessage += value[0] + '\n';
                        });

                        Swal.fire({
                            title: 'Error!',
                            text: errorMessage,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });
            //end store

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


            // Delegación de eventos para manejar clics en botones de eliminar
            $(document).on('click', '.btn-delete', function () {
                var url = $(this).data('url');

                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "Este cliente será desactivado.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, desactivar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: 'POST',
                            data: {
                                _token: "{{ csrf_token() }}",
                                _method: 'DELETE' // Laravel usa POST para manejar solicitudes de eliminación cuando se hace con AJAX
                            },
                            success: function (response) {
                                if (response.success) {
                                    Swal.fire(
                                        'Desactivado!',
                                        response.message,
                                        'success'
                                    ).then(() => {
                                        // Opcional: Puedes recargar la página o actualizar la vista
                                        location.reload(); // Recarga la página
                                    });
                                } else {
                                    Swal.fire(
                                        'Error!',
                                        response.message,
                                        'error'
                                    );
                                }
                            },
                            error: function (xhr) {
                                Swal.fire(
                                    'Error!',
                                    'No se pudo desactivar el cliente.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });
            //end delete


        });
    </script>
@stop
