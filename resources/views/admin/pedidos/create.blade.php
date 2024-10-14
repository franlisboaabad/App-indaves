@extends('adminlte::page')

@section('title', 'Registro pedido')

@section('plugins.Select2', true)

@section('content_header')
    <h1>Registro</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h1 class="card-title">Registrar Pedido</h1>
                </div>
                <div class="card-body">
                    <form action="{{ route('pedidos.store') }}" method="POST">

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Seleccionar la fecha de pedido</label>
                                    <input type="date" name="fecha_pedido" id="fecha_pedido" class="form-control">
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
                                {{-- crear desde jquery --}}
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>-</th>
                                    <th>-</th>
                                    <th id="totalPresa">0</th>
                                    <th id="totalBrasa">0</th>
                                </tr>
                            </tfoot>
                        </table>


                        <div class="form-group">
                            @csrf
                            @method('POST')
                            <button class="btn btn-primary mr-2" type="button" id="addPedido">Registrar Pedido</button>
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

            //Agregar a lista
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


            $('#addPedido').on('click', function() {
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

                $.ajax({
                    type: 'POST',
                    url: '{{ route('pedidos.store') }}', // Cambia esto a la URL correcta
                    data: data,
                    success: function(response) {
                        Swal.fire(
                            'Registrado!',
                            'El pedido se ha registrado con éxito.',
                            'success'
                        ).then(() => {
                                window.location.href = '{{ route('pedidos.index') }}';
                            });
                        // Opcional: Redirigir o limpiar el formulario
                        // $('#pedidoForm')[0].reset();
                        // $('#detailsPedido tbody').empty();
                        // updateTotals();
                    },
                    error: function(xhr) {
                        let errorMessage = 'No se pudo registrar el pedido.';

                        // Verifica si hay mensajes de error en la respuesta del servidor
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message; // Mensaje general
                        } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                            // Si hay errores de validación, extrae el primer mensaje
                            const errors = Object.values(xhr.responseJSON.errors).flat();
                            if (errors.length) {
                                errorMessage = errors[0]; // Muestra el primer mensaje de error
                            }
                        }

                        Swal.fire(
                            'Error!',
                            errorMessage,
                            'error'
                        );
                        console.log(xhr);
                    }
                });

            });



            $(".select2").select2();
        });
    </script>
@stop
