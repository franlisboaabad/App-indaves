@extends('adminlte::page')

@section('title', 'Lista de cajas')
@section('plugins.Datatables', true)
@section('content_header')
    <h1>Lista de Cajas</h1>
@stop

@section('content')
<a class="btn btn-primary mb-3" href="#" id="openModal">Aperturar caja</a>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Cajas</h3>
        </div>
        <div class="card-body">


            <table class="table table-bordered" id="table-clientes">
                <thead>
                    <th>#</th>
                    <th>Usuario</th>
                    <th>Fecha Apertura</th>
                    <th>Monto de Apertura</th>
                    <th>Fecha Cierre</th>
                    <th>Monto de cierre</th>
                    <th>Estado</th>
                    <th>Opciones</th>
                </thead>
                <tbody>
                    @foreach ($cajas as $caja)
                        <tr>
                            <td>{{ $caja->id }}</td>
                            <td>{{ $caja->usuario->email }}</td>
                            <td>{{ $caja->fecha_apertura }}</td>
                            <td>{{ $caja->monto_apertura }}</td>
                            <td>{{ $caja->fecha_cierre }}</td>
                            <td>{{ $caja->monto_cierre }}</td>
                            <td>
                                @if ($caja->estado_caja == 1)
                                    <span class="badge bg-success">Abierto</span>
                                @elseif($caja->estado_caja == 0)
                                    <span class="badge bg-secondary">Cerrado</span>
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('cajas.destroy', $caja->id) }}" method="POST" class="delete-form"
                                    id="delete-form-{{ $caja->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <a href="{{ route('cajas.show', $caja->id) }}" class="btn btn-info btn-sm">Ver |
                                        Cerrar</a>
                                    <button type="button" class="btn btn-danger btn-sm btn-delete"
                                        data-id="{{ $caja->id }}">Eliminar</button>
                                </form>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>



    <!-- Modal -->
    <div class="modal fade" id="aperturaCajaModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Aperturar Caja</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="aperturaCajaForm">
                        <div class="form-group">
                            <label for="monto_apertura">Monto de Apertura:</label>
                            <input type="number" class="form-control" id="monto_apertura" name="monto_apertura" required value="0">
                        </div>
                        <button type="submit" class="btn btn-success">Aperturar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


@stop

@section('css')

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {

            $('#openModal').on('click', function() {
                $('#aperturaCajaModal').modal('show');
            });

            $('#aperturaCajaForm').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: '{{ route('cajas.store') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        monto_apertura: $('#monto_apertura').val(),
                    },
                    success: function(response) {
                        $('#aperturaCajaModal').modal('hide');

                        // Mostrar el SweetAlert2
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: 'Caja aperturada exitosamente.',
                            timer: 1500, // Muestra el alert por 3 segundos
                            showConfirmButton: false
                        }).then(function() {
                            location
                                .reload(); // Recargar la página después de que el alert se haya mostrado
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Hubo un error al guardar la caja.',
                            timer: 1500, // Muestra el alert por 3 segundos
                            showConfirmButton: false
                        });
                    }
                });
            });



            //eliminar caja

            // Maneja el clic en el botón de eliminar
            $('.btn-delete').on('click', function(e) {
                e.preventDefault();

                // Obtener el ID del formulario a eliminar
                var formId = $(this).data('id');
                var form = $('#delete-form-' + formId);

                // Mostrar SweetAlert2
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¡No podrás recuperar este registro después de eliminarlo!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminarlo',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Enviar la solicitud AJAX para eliminar
                        $.ajax({
                            url: form.attr('action'),
                            type: 'POST',
                            data: form.serialize(),
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: '¡Éxito!',
                                        text: response.message,
                                        timer: 1500,
                                        showConfirmButton: false
                                    }).then(function() {
                                        location
                                            .reload(); // Recargar la página después de que el alert se haya mostrado
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: response.message,
                                        timer: 1500,
                                        showConfirmButton: false
                                    });
                                }
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Hubo un error al procesar la solicitud.',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                            }
                        });
                    }
                });
            });


        });
    </script>
    <script src="{{ asset('js/tarea.js') }}"></script>
@stop
