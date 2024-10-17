@extends('adminlte::page') @section('plugins.Select2', true) @section('title', 'Orden de ingreso') @section('content_header')
<h1>Editar</h1>
@stop @section('content')

<div class="card">
    <div class="card-body">
        <form method="POST">
            @csrf
            <input type="hidden" name="type" value="ingreso" />
            <div class="row">
                <!-- Guía de ingreso -->
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="">Número de guía</label>
                        <input type="hidden" id="orden_id" value="{{ $orden->id }}" />
                        <input type="text" id="numero_guia" name="numero_guia" class="form-control" required
                            value="{{ $orden->numero_guia }}" readonly />
                    </div>
                </div>

                <!-- Fecha de Ingreso -->
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="fecha_despacho">Fecha de Ingreso</label>
                        <input type="date" id="fecha_despacho" name="fecha_despacho" class="form-control" required
                            value="{{ $orden->fecha_ingreso }}" readonly />
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label for="peso_bruto">Peso Bruto</label>
                        <input type="text" id="peso_bruto" name="peso_bruto" class="form-control" required
                            value="{{ $orden->peso_bruto }}" />
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label for="">Peso Tara</label>
                        <input type="text" id="peso_tara" name="peso_tara" class="form-control" required
                            value="{{ $orden->peso_tara }}" />
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label for="peso_neto">Peso Neto</label>
                        <input type="text" id="peso_neto" name="peso_neto" class="form-control" required
                            value="{{ $orden->peso_neto }}" />
                    </div>
                </div>
            </div>
        </form>

        <hr />
        <!-- Tabla de Detalles -->
        <div class="mt-4">
            <h2>Detalle de Orden</h2>
            <table class="table table-bordered" id="detailsTable">
                <thead class="table-dark">
                    <tr>
                        <th>Id</th>
                        <th>Presentación</th>
                        <th>Tipo</th>
                        <th>Número de Jabas</th>
                        <th>Número de Aves</th>
                        <th>Peso Neto</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orden->detalle as $detalle)
                        <tr>
                            <td class="detalle_id">{{ $detalle->id }}</td>
                            <td>{{ $detalle->presentacion_pollo->descripcion }}</td>
                            <td>{{ $detalle->tipo_pollo->descripcion }}</td>
                            <td>
                                <input type="text" class="cantidad_jabas" value="{{ $detalle->cantidad_jabas }}" />
                            </td>
                            <td>
                                <input type="text" class="cantidad_pollos"
                                    value="{{ $detalle->cantidad_pollos }}" />
                            </td>
                            <td>
                                <input type="text" class="peso_neto" value="{{ $detalle->peso_neto }}" />
                            </td>
                            {{-- <td>
                                <a href="" class="btn btn-info btn-sm btnActualizar">Actualizar</a>
                            </td> --}}
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="form-group">
            <a class="btn btn-success mr-2" id="btnEditarOrden"> Editar Orden </a>
            <a href="{{ route('ordenes-ingreso.index') }}" class="btn btn-warning">Lista de Ingreso</a>
        </div>
    </div>
</div>

@stop

@section('css')
<style>
    .select2-selection {
        height: 40px !important;
    }
</style>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {


        $('#btnEditarOrden').click(function(e) {
            e.preventDefault();

            const ordenId = $('#orden_id').val();
            const pesoBruto = $('#peso_bruto').val();
            const pesoTara = $('#peso_tara').val();
            const pesoNeto = $('#peso_neto').val();
            var Arraydetalle = [];

            // Recoger los detalles de la tabla
            $('#detailsTable tbody tr').each(function() {
                const detalle = {
                    id: $(this).find('.detalle_id').text(),
                    cantidad_jabas: $(this).find('.cantidad_jabas').val(),
                    cantidad_pollos: $(this).find('.cantidad_pollos').val(),
                    peso_neto: $(this).find('.peso_neto').val()
                };
                Arraydetalle.push(detalle);
            });

            const data = {
                orden_id: ordenId,
                peso_bruto: pesoBruto,
                peso_tara: pesoTara,
                peso_neto: pesoNeto,
                detalle: Arraydetalle,
                _token: $('meta[name="csrf-token"]').attr("content"), // Agregar el token CSRF para seguridad
            };

             // Confirmar acción con SweetAlert
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¿Quieres actualizar la orden de ingreso?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, actualizar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Realizar la solicitud AJAX
                    $.ajax({
                        type: "PUT",
                        url: "{{ route('ordenes-ingreso.update', ':id') }}".replace(":id", ordenId),
                        data: data,
                        dataType: "json",
                        success: function(response) {
                            // Mostrar mensaje con SweetAlert
                            Swal.fire({
                                icon: 'success',
                                title: '¡Actualización exitosa!',
                                text: response.message, // Mensaje de éxito del controlador
                            });

                            location.reload();
                        },
                        error: function(xhr) {
                            // Manejar errores
                            var errors = xhr.responseJSON.message || 'Ocurrió un error al actualizar la orden.';
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: errors,
                            });
                        }
                    });
                }
            });

        });


    });
</script>
@endsection
