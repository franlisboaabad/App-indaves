@extends('adminlte::page')

@section('title', 'Ver Caja')

@section('content_header')
    <h1>Ver Caja</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Detalles de la Caja</h3>
        </div>
        <div class="card-body">
            <!-- Mostrar los detalles de la caja -->
            <div class="row">
                <div class="col-md-6">
                    <dl class="row">
                        <dt class="col-sm-4">ID:</dt>
                        <dd class="col-sm-8">{{ $caja->id }}</dd>

                        <dt class="col-sm-4">Monto de Apertura:</dt>
                        <dd class="col-sm-8">{{ number_format($caja->monto_apertura, 2) }} {{ $caja->moneda }}</dd>

                        <dt class="col-sm-4">Fecha de Apertura:</dt>
                        <dd class="col-sm-8">{{ $caja->fecha_apertura->format('d/m/Y H:i') }}</dd>

                        <dt class="col-sm-4">Estado:</dt>
                        <dd class="col-sm-8">
                            @if ($caja->estado_caja)
                                <span class="badge badge-success">Abierto</span>
                            @else
                                <span class="badge badge-danger">Cerrado</span>
                            @endif
                        </dd>

                        <dt class="col-sm-4">Fecha de Cierre:</dt>
                        <dd class="col-sm-8">{{ $caja->fecha_cierre ? $caja->fecha_cierre->format('d/m/Y H:i') : 'No cerrada aún' }}</dd>
                    </dl>
                </div>
            </div>

            <!-- Mostrar los pagos asociados -->
            <h3 class="mt-4">Pagos Asociados</h3>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>CLIENTE</th>
                    <td>Serie de venta</td>
                    <th>Monto</th>
                    <th>Método de Pago</th>
                    <th>Fecha</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @forelse($caja->pagos as $pago)
                    <tr>
                        <td>{{ $pago->id }}</td>
                        <td>{{ $pago->venta?->cliente_razon_social }}</td>
                        <td>{{ $pago->venta->serie_venta }}</td>
                        <td>{{ number_format($pago->monto, 2) }}</td>
                        <td>{{ $pago->metodo_pago->descripcion }}</td>
                        <td>{{ $pago->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            @if($pago->imagen)
                                <a href="{{ \Illuminate\Support\Facades\Storage::url($pago->imagen) }}">Ver</a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">No hay pagos registrados para esta caja.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            <!-- Botón para cerrar la caja -->
            @if($caja->estado_caja)
                <button class="btn btn-danger" id="closeCajaBtn" data-id="{{ $caja->id }}">Cerrar Caja</button>
            @endif
            <a href="{{ route('cajas.index') }}" class="btn btn-secondary">Volver</a>
        </div>
    </div>
@stop

@section('css')
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"> --}}
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function () {
            $('#closeCajaBtn').on('click', function () {
                var cajaId = $(this).data('id');

                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¡Una vez cerrada, la caja no podrá ser editada!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, cerrar caja',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ url('cajas') }}/' + cajaId,
                            type: 'PATCH',
                            data: {
                                _token: '{{ csrf_token() }}',
                                fecha_cierre: new Date().toISOString()
                            },
                            success: function (response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: '¡Éxito!',
                                    text: 'Caja cerrada exitosamente.',
                                    timer: 3000,
                                    showConfirmButton: false
                                }).then(function () {
                                    window.location.reload(); // Recargar la página después de la notificación
                                });
                            },
                            error: function (xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Hubo un error al cerrar la caja.',
                                    timer: 3000,
                                    showConfirmButton: false
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@stop
