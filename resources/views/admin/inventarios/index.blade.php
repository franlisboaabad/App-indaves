@extends('adminlte::page')

@section('title', 'Inventario')
@section('plugins.Datatables', true)
@section('content_header')
    <h1>Inventario</h1>
@stop

@section('content')

    <a href="#" class="btn btn-danger mb-3" data-toggle="modal" data-target="#mermaModal">Registrar Merma</a>

    <div class="card">
        <div class="card-body">
            <table id="inventoriesTable" class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID
                        <th>Presentación</th>
                        <th>Tipo</th>
                        <th>Cantidad Pollos</th>
                        <th>Total Peso</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($inventarios as $inventory)
                        <tr>
                            <td>{{ $inventory->id }}</td>
                            <td>{{ $inventory->presentacion_pollo_descripcion }}</td>
                            <td>{{ $inventory->tipo_pollo_descripcion }}</td>
                            <td>{{ $inventory->total_pollos }}</td>
                            <td>{{ $inventory->total_peso }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="mermaModal" tabindex="-1" role="dialog" aria-labelledby="mermaModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mermaModalLabel">Registrar Merma</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST">
                        <div class="form-group">
                            <label for="">Total Peso Neto : Merma</label>
                            <input type="text" id="totalPeso" class="form-control" readonly>
                        </div>
                        <hr>
                        <label for="">Detalle Mermas</label>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Presentación</th>
                                    <th>Tipo</th>
                                    <th>Total Peso</th>
                                </tr>
                            </thead>
                            <tbody id="mermasBody">
                                <!-- Las filas se agregarán dinámicamente aquí -->
                            </tbody>
                        </table>

                        @csrf

                        <button type="button" class="btn btn-primary" id="registrarMermas">Registrar Mermas</button>

                    </form>
                </div>
            </div>
        </div>
    </div>


@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
@stop

@section('js')
    <script>
        $(function() {
            $("#inventoriesTable").DataTable({
                "responsive": true,
                "autoWidth": false,
                language: {
                    "url": "/js/spanish.json"
                },
            });

            function calcularTotalPeso() {
                let totalPeso = 0;

                $('#inventoriesTable tbody tr').each(function() {
                    const peso = parseFloat($(this).find('td:eq(4)')
                        .text()); // Columna Total Peso (índice 4)
                    if (!isNaN(peso)) {
                        totalPeso += peso;
                    }
                });

                return totalPeso;
            }

            //modal show
            $('#mermaModal').on('show.bs.modal', function() {
                $('#mermasBody').empty(); // Limpiar la tabla del modal

                const totalPeso = calcularTotalPeso();

                // Recorrer los inventarios y agregar filas al modal
                $('#inventoriesTable tbody tr').each(function() {
                    const presentacion = $(this).find('td:eq(1)').text();
                    const tipo = $(this).find('td:eq(2)').text();
                    const peso = $(this).find('td:eq(4)').text();
                    const nuevaFila = `
                            <tr>
                                <td>${presentacion}</td>
                                <td>${tipo}</td>
                                <td>${peso}</td>
                            </tr> `;
                    $('#mermasBody').append(nuevaFila);
                });

                $('#totalPeso').val(calcularTotalPeso().toFixed(2)); // Mostrar con 2 decimales

            });


            $("#registrarMermas").click(function() {

                const totalPeso = parseFloat($('#totalPeso').val());

                // Validar que el totalPeso sea mayor que 0
                if (totalPeso <= 0) {
                    alert(
                        'No se puede registrar la merma porque el total peso es 0. No hay mermas en el día.');
                    return; // Detener la ejecución si la validación falla
                }

                const detalles = [];

                $('#mermasBody tr').each(function() {
                    const presentacion = $(this).find('td:eq(0)').text();
                    const tipo = $(this).find('td:eq(1)').text();
                    const peso = parseFloat($(this).find('td:eq(2)').text());

                    detalles.push({
                        presentacion,
                        tipo,
                        peso
                    });
                });

                $.ajax({
                    url: '{{ route('mermas.store') }}', // Ajusta la URL según tu ruta
                    method: 'POST',
                    data: {
                        total_peso: $('#totalPeso').val(),
                        detalles: detalles,
                        _token: '{{ csrf_token() }}' // Añade el token CSRF para la seguridad
                    },
                    success: function(response) {
                        alert(response.message);
                        $('#mermaModal').modal('hide');
                        // Aquí podrías recargar la tabla o realizar alguna otra acción
                        location.reload();
                    },
                    error: function(xhr) {
                        alert('Error al registrar la merma: ' + xhr.responseJSON.message);
                    }
                });
            });


        });
    </script>
@stop
