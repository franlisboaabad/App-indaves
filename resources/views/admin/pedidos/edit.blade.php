@extends('adminlte::page')

@section('title', 'Editar pedido')

@section('plugins.Select2', true)

@section('content_header')
    <h1>Editar</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h1 class="card-title">Editar Pedido</h1>
                </div>
                <div class="card-body">
                    <form action="{{ route('pedidos.store') }}" method="POST">

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <input type="hidden" id="pedidoId" value="{{ $pedido->id }}">
                                    <input type="date" name="fecha_pedido" id="fecha_pedido" class="form-control"
                                        value="{{ $pedido->fecha_pedido }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="form-group">
                                    <label for="">Seleccionar cliente</label>
                                    <select name="cliente_id" id="cliente_id" class="form-control select2">
                                        @foreach ($clientes as $cliente)
                                            <option value="{{ $cliente->id }}"> {{ $cliente->nombre_comercial }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-group">
                                    <label for="">Cantidad Pollo Presa</label>
                                    <input type="text" name="cantidad_presa" id="cantidad_presa" class="form-control"
                                        placeholder="Ingrese cantidad Presa">
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-group">
                                    <label for="">Cantidad Pollo Brasa</label>
                                    <input type="text" name="cantidad_brasa" id="cantidad_brasa" class="form-control"
                                        placeholder="Ingrese cantidad Brasa">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <button type="button" class="btn btn-info btn-block">Agregar a pedido</button
                                        type="button">
                                </div>
                            </div>
                        </div>

                        <hr>
                        <p>Detalle del pedido</p>

                        <table class="table" id="detailsPedido">
                            <thead>
                                <tr>
                                    <th>id</th>
                                    <th>Cliente</th>
                                    <th>Cantidad Presa</th>
                                    <th>Cantidad Brasa</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($detalles as $detalle)
                                    <tr>
                                        <td>{{ $detalle->cliente_id }}</td>
                                        <td>{{ $detalle->cliente->razon_social }}</td>
                                        <td class="cantidadPresa">{{ $detalle->cantidad_presa }}</td>
                                        <td class="cantidadBrasa">{{ $detalle->cantidad_brasa }}</td>
                                        <td>
                                            <form action="" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-xs btn-danger btnEliminar"
                                                    data-cliente-id="{{ $detalle->cliente_id }}"
                                                    data-pedido-id="{{ $pedido->id }}">Eliminar</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                {{-- crear desde jquery --}}
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="2">TOTALES:</th>
                                    <th id="totalPresa"></th>
                                    <th id="totalBrasa"></th>
                                </tr>
                            </tfoot>
                        </table>

                        <div class="form-group">
                            @csrf
                            @method('PUT')
                            <button type="button" id="btnEditar" class="btn btn-success mr-1">Editar Lista</button>
                            <a href="{{ route('pedidos.index') }}" class="btn btn-warning">Lista de pedidos</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .select2-container--default .select2-selection--single {
            height: 40px !important;
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            //Add lista
            $('.btn-info').on('click', function() {
                // Obtener los valores de los campos

                let idCliente = $('#cliente_id option:selected').val();
                let cliente = $('#cliente_id option:selected').text(); // Nombre del cliente
                let cantidadPresa = $('#cantidad_presa').val();
                let cantidadBrasa = $('#cantidad_brasa').val();

                // Verificar que los campos no estén vacíos
                if (cliente && cantidadPresa && cantidadBrasa) {
                    // Crear una nueva fila para la tabla
                    var nuevaFila = `
                        <tr>
                            <td class="cliente_id">${idCliente}</td>
                            <td>${cliente}</td>
                            <td class="cantidadPresa">${cantidadPresa}</td>
                            <td class="cantidadBrasa">${cantidadBrasa}</td>
                            <td><button class="btn btn-danger btn-xs eliminar">Eliminar</button></td>
                        </tr>
                    `;

                    // Agregar la nueva fila a la tabla
                    $('table tbody').append(nuevaFila);

                    // Limpiar los campos del formulario
                    $('#cantidad_presa').val('');
                    $('#cantidad_brasa').val('');
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Por favor, complete todos los campos.",
                    });
                }

                updateTotals();
            });

            // Eliminar fila de la tabla
            $('table').on('click', '.eliminar', function() {
                $(this).closest('tr').remove();
                updateTotals();
            });


            // Función para actualizar los totales
            function updateTotals() {
                let totalPresa = 0;
                let totalBrasa = 0;

                $('#detailsPedido tbody tr').each(function() {
                    totalPresa += parseInt($(this).find('.cantidadPresa').text());
                    totalBrasa += parseInt($(this).find('.cantidadBrasa').text());
                });

                $('#totalPresa').text(totalPresa);
                $('#totalBrasa').text(totalBrasa);
            }


            $('#btnEditar').on('click', function() {
                var detalles = [];
                $('#detailsPedido tbody tr').each(function() {
                    var clienteId = $(this).find('.cliente_id').text();
                    var cantidadPresa = $(this).find('.cantidadPresa').text();
                    var cantidadBrasa = $(this).find('.cantidadBrasa').text();
                    detalles.push({
                        cliente_id: clienteId,
                        cantidad_presa: cantidadPresa,
                        cantidad_brasa: cantidadBrasa,
                    });
                });

                var data = {
                    fecha_pedido: $('#fecha_pedido').val(),
                    total_presa: $('#totalPresa').text(),
                    total_brasa: $('#totalBrasa').text(),
                    total_tipo: 0,
                    detalle: detalles,
                    _token: $('input[name="_token"]').val(),
                };


                // Muestra confirmación antes de enviar el pedido
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¡Vas a actualizar este pedido!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, actualizar',
                    cancelButtonText: 'No, cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {

                        var pedidoId = $('#pedidoId').val();
                        $.ajax({
                            type: 'PUT',
                            url: `/pedidos/${pedidoId}`, // Cambia esto a la URL correcta
                            data: data,
                            success: function(response) {
                                // alert('Pedido editado con éxito');
                                // // Opcional: Redirigir o limpiar el formulario
                                // $('#pedidoForm')[0].reset();
                                // $('#detailsPedido tbody').empty();
                                // updateTotals();
                                Swal.fire(
                                    'Actualizado!',
                                    'El pedido se ha editado con éxito.',
                                    'success'
                                ).then(() => {
                                    window.location.href =
                                        '{{ route('pedidos.index') }}';
                                });
                            },
                            error: function(xhr) {
                                Swal.fire(
                                    'Error!',
                                    'No se pudo actualizar el pedido.',
                                    'error'
                                );
                                console.log(xhr);
                            }
                        });
                    }
                });
            });

            //eliminar
            $('.btnEliminar').on('click', function() {
                const clienteId = $(this).data('cliente-id');
                const pedidoId = $(this).data('pedido-id');
                const row = $(this).closest('tr');

                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¡Esta acción no se puede deshacer!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'No, cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Hacer la solicitud AJAX para eliminar el detalle
                        $.ajax({
                            url: `/detalle-pedidos/${pedidoId}/clientes/${clienteId}`, // Cambia esta URL según tu ruta
                            type: 'DELETE',
                            success: function(response) {
                                // Si la eliminación fue exitosa, eliminar la fila de la tabla
                                row.remove();

                                // Actualizar totales si es necesario
                                updateTotals();
                                Swal.fire(
                                    'Eliminado!',
                                    'El registro ha sido eliminado.',
                                    'success'
                                );
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


            updateTotals();


            $(".select2").select2();
        });
    </script>
@stop
