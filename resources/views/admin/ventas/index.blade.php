@extends('adminlte::page')

@section('title', 'Lista de Ventas')
@section('plugins.Datatables', true)
@section('content_header')
    <h1>Listado de Ventas</h1>
@stop

@section('content')

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered" id="table-ventas">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Serie Venta</th>
                        <th>Fecha Venta</th>
                        <th>Peso Neto</th>
                        <th>Forma de Pago</th>
                        <th>Monto Total</th>
                        <th>Monto Recibido</th>
                        <th>Saldo</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ventas as $venta)
                        <tr>
                            <td>{{ $venta->id }}</td>
                            <td>{{ $venta->serie_venta }}</td>
                            {{-- <td>{{ \Carbon\Carbon::parse($venta->fecha_venta)->format('d/m/Y') }}</td> --}}
                            <td>{{ $venta->fecha_venta }}</td>
                            <td>{{ $venta->peso_neto }}</td>
                            <td>{{ $venta->forma_de_pago ? 'A credito' : 'Contado' }}</td>
                            <td>{{ number_format($venta->monto_total, 2) }}</td>
                            <td>{{ number_format($venta->monto_recibido, 2) }}</td>
                            <td>{{ number_format($venta->saldo, 2) }}</td>
                            <td>
                                @if ($venta->saldo <= 0)
                                    <span class="badge badge-success">Pagada</span>
                                @else
                                    <span class="badge badge-danger">Pendiente de pago</span>
                                @endif
                            </td>
                            <td>
                                @if (!$venta->saldo <= 0)
                                    <a href="#" class="btn btn-sm btn-primary" data-toggle="modal"
                                        data-target="#agregarPagoModal" data-venta-monto="{{ $venta->saldo }}"
                                        data-venta-id="{{ $venta->id }}">
                                        Agregar Pago
                                    </a>
                                @endif
                                <a href="{{ route('ventas.show', $venta->id) }}" class="btn btn-info btn-sm">Ver</a>
                                {{-- <a href="{{ route('ventas.edit', $venta->id) }}" class="btn btn-warning btn-sm">Editar</a> --}}
                                <a href="{{ route('ventas.print', ['id' => $venta->id,'format'=>'a4']) }}" target="_blank" class="btn btn-danger btn-sm">A4</a>
                                <a href="{{ route('ventas.print', ['id' => $venta->id,'format'=>'ticket']) }}" target="_blank" class="btn btn-primary btn-sm">TICKET</a>
                                <form action="{{ route('ventas.destroy', $venta->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="agregarPagoModal" tabindex="-1" role="dialog" aria-labelledby="agregarPagoModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="agregarPagoModalLabel">Agregar Pago</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="agregarPagoForm" action="{{ route('pagos.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="venta_id" id="venta_id">

                        <div class="form-group">
                            <label for="">Monto a pagar</label>
                            <input type="text" class="form-control" name="monto_total" id="monto_total" readonly>
                        </div>

                        <div class="form-group">
                            <label for="metodo_pago">Método de Pago</label>
                            <select class="form-control" name="metodo_pago_id" id="metodo_pago_id" required>
                                <!-- Aquí deberías cargar dinámicamente los métodos de pago disponibles -->
                                @foreach ($metodos as $metodo)
                                    <option value="{{ $metodo->id }}">{{ $metodo->descripcion }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="checkPagoCompleto" class="checkbox-label">
                                Desea hacer el pago completo?
                                <input type="checkbox" id="checkPagoCompleto">
                            </label>
                        </div>

                        <div class="form-group">
                            <label for="monto">Ingresar Pago</label>
                            <input type="number" step="0.01" class="form-control" name="monto" id="monto"
                                required>
                        </div>

                        <button type="submit" class="btn btn-primary">Registrar Pago</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


@stop

@section('css')
    <style>
        .checkbox-label {
            display: flex;
            align-items: center;
            font-size: 16px;
            /* Tamaño del texto */
            cursor: pointer;
            /* Cambia el cursor al pasar sobre el texto */
        }

        .checkbox-label input[type="checkbox"] {
            width: 20px;
            /* Tamaño del checkbox */
            height: 20px;
            /* Tamaño del checkbox */
            margin-right: 10px;
            /* Espacio entre el checkbox y el texto */
            cursor: pointer;
            /* Cambia el cursor al pasar sobre el checkbox */
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $('#table-ventas').DataTable({
                language: {
                    "url": "/js/spanish.json"
                },
            });


            $('#agregarPagoModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Botón que abrió el modal
                var ventaId = button.data('venta-id'); // Extrae el ID de la venta
                var montoTotal = button.data('venta-monto')

                var modal = $(this);
                modal.find('#venta_id').val(ventaId);
                modal.find('#monto_total').val(montoTotal);
            });



            // Manejo del envío del formulario
            $(document).ready(function() {
                $('#agregarPagoForm').on('submit', function(event) {
                    event.preventDefault(); // Evita el envío normal del formulario

                    var form = $(this);
                    var url = form.attr('action'); // Obtiene la URL de acción del formulario
                    var data = form.serialize(); // Obtiene los datos del formulario serializados
                    var monto = parseFloat($('#monto').val());
                    var saldo = parseFloat($('#monto_total')
                        .val()); // Asegúrate de tener el saldo en el formulario

                    // Validar que el monto no exceda el saldo
                    if (monto > saldo) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Monto excede el saldo',
                            text: 'El monto ingresado no puede ser mayor que el saldo pendiente.'
                        });
                        return;
                    }

                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: data,
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Éxito',
                                text: 'Pago agregado correctamente',
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                location
                                    .reload(); // Recarga la página después de mostrar la alerta
                            });
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'No se pudo agregar el pago. Intenta nuevamente.'
                            });
                        }
                    });
                });
            });


            //check
            $('#checkPagoCompleto').change(function() {
                if ($(this).is(':checked')) {
                    // Obtener el monto total a pagar
                    var montoTotal = parseFloat($('#monto_total').val()) || 0;
                    // Establecer el monto recibido igual al monto total a pagar
                    $('#monto').val(montoTotal);
                } else {
                    // Si no está marcado, limpiar los campos
                    $('#monto').val('0.00');
                }
            });




        });
    </script>
@stop
