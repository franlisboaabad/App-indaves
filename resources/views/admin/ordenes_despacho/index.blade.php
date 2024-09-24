@extends('adminlte::page')

@section('title', 'Ordenes de despacho')
@section('plugins.Datatables', true)
@section('content_header')
    <h1>Lista de Ordenes</h1>
@stop

@section('content')

    <div class="card">
        <div class="card-body">


            <table class="table table-bordered" id="table-clientes">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Cliente</th>
                        <th>Serie Orden</th>
                        <th>Fecha Despacho</th>
                        <th>Peso Total Bruto</th>
                        <th>Cantidad Jabas</th>
                        <th>Tara</th>
                        <th>Peso Total Neto</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ordenes as $orden)
                        <tr>
                            <td>{{ $orden->id }}</td>
                            <td>{{ $orden->cliente->razon_social }}</td>
                            <td>{{ $orden->serie_orden }}</td>
                            <td>{{ \Carbon\Carbon::parse($orden->fecha_despacho)->format('d/m/Y') }}</td>
                            <td>{{ number_format($orden->peso_total_bruto, 2) }}</td>
                            <td>{{ $orden->cantidad_jabas }}</td>
                            <td>{{ number_format($orden->tara, 2) }}</td>
                            <td>{{ number_format($orden->peso_total_neto, 2) }}</td>
                            <td>
                                @if ($orden->estado_despacho)
                                    <span class="badge badge-success">Despachado</span>
                                @else
                                    <span class="badge badge-warning">Por Despachar</span>
                                @endif
                            </td>

                            <td>
                                <!-- Example single danger button -->
                                <div class="btn-group">
                                    <button type="button" class="btn btn-danger btn-sm dropdown-toggle" data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        Acciones
                                    </button>
                                    <div class="dropdown-menu">
                                        <!-- Aquí puedes añadir botones para ver, editar o eliminar -->
                                        <a class="dropdown-item"
                                            href="{{ route('ordenes-de-despacho.show', $orden->id) }}">Ver</a>

                                        {{-- <a href="{{ route('ordenes-de-despacho.edit', $orden->id) }}" class="dropdown-item">Editar</a> --}}

                                        @if (!$orden->ventas->isNotEmpty())
                                            <a class="dropdown-item"
                                                href="{{ route('ordenes-de-despacho.venta', ['id' => $orden->id]) }}">
                                                Generar venta </a>
                                        @endif


                                        <a class="dropdown-item"
                                            href="{{ route('ordenes-de-despacho.print', ['id' => $orden->id, 'format' => 'a4']) }}"
                                            target="_Blank"> PDF </a>

                                        <form id="delete-form-{{ $orden->id }}"
                                            action="{{ route('ordenes-de-despacho.destroy', $orden) }}" method="POST"
                                            style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="dropdown-item"
                                                onclick="confirmDelete({{ $orden->id }})">Eliminar</button>
                                        </form>


                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>



@stop

@section('css')
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {

            $('#table-clientes').DataTable({
                language: {
                    "url": "/js/spanish.json"
                },
            });

            // Maneja el clic en el botón de eliminación
            $(document).on('click', '.delete-button', function() {
                const id = $(this).data('id'); // Obtener el ID del registro

                // Mostrar alerta de confirmación
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¡No podrás revertir esto!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Si el usuario confirma, enviar solicitud AJAX para eliminar el registro
                        $.ajax({
                            url: `/ordenes-de-despacho/${id}`,
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                    'content') // Incluye el token CSRF
                            },
                            success: function(response) {
                                Swal.fire(
                                    'Eliminado!',
                                    'El registro ha sido eliminado.',
                                    'success'
                                ).then(() => {
                                    // Eliminar la fila de la tabla
                                    $(`button[data-id="${id}"]`).closest('tr')
                                        .remove();
                                });
                            },
                            error: function(xhr) {
                                Swal.fire(
                                    'Error!',
                                    'No se pudo eliminar el registro.',
                                    'error'
                                );
                            }
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
                        url: '{{ url('ordenes-de-despacho') }}/' + userId,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Eliminado!',
                                text: 'Orden despacho eliminado con éxito',
                                timer: 3000,
                                showConfirmButton: false
                            }).then(function() {
                                location
                            .reload(); // Recargar la página para ver el nuevo usuario
                            });
                        },
                        error: function(xhr) {
                            Swal.fire(
                                'Error!',
                                'No se pudo eliminar la orden de despacho',
                                'error'
                            );
                        }
                    });
                }
            });
        }
    </script>

@stop
