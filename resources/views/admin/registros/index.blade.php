@extends('adminlte::page')
@php
    use Carbon\Carbon;
@endphp
@section('title', 'Registro de sorteos | EL TRIKI')
@section('plugins.Datatables', true)
@section('content_header')
    <h1>Lista de registros</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <a href="{{ route('registros.create') }}" class="btn btn-primary btn-xs">Nuevo registro</a>
            <hr>

            <select name="" id="filtroEstado" style="height: 40px;  width: 200px; border-radius:5px; padding:5px">
                <option value="">Todos</option>
                @foreach ($sorteos as $sorteo)
                    <option value="{{ $sorteo->nombre_sorteo }}">{{ $sorteo->nombre_sorteo }}</option>
                @endforeach
            </select>

            <table class="table" id="table-registros" width="100%" data-page-length='50'>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Identificador (sorteo)</th>
                        <th>F.Compra</th>
                        <th>Nombre y Apellidos</th>
                        <th>Numero identidad</th>
                        <th>Celular</th>
                        <th>E-mail</th>
                        <th>Monto</th>
                        <th>Comprobante</th>
                        <th>Estado de registro</th>
                        <th>Tickets</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($registros as $registro)
                        <tr>
                            <td>{{ $registro->id }}</td>
                            <td>{{ $registro->sorteo->nombre_sorteo }}</td>
                            <td>{{ Carbon::parse($registro->created_at)->format('d-m-Y | h:i:s A') }}</td>
                            <td>{{ $registro->nombre_apellidos }}</td>
                            <td>{{ $registro->numero_identidad }}</td>
                            <td>{{ $registro->celular }}</td>
                            <td>{{ $registro->email }}</td>
                            <td>{{ $registro->monto }}</td>
                            <td> <a href="{{ $registro->getImagen }}" target="_Blank">Ver comprobante</a> </td>

                            <td>
                                @if ($registro->estado_registro === 1)
                                    <span class="badge badge-success">Aprobado</span>
                                @elseif($registro->estado_registro === 0)
                                    <span class="badge badge-warning">En proceso</span>
                                @else
                                    <span class="badge badge-danger">Rechazado</span>
                                @endif
                            </td>
                            <td>
                                @foreach ($registro->tickets as $ticket)
                                    @foreach ($ticket->detalles as $detalle)
                                        {{ $detalle->correlativo_ticket }} ,
                                    @endforeach
                                @endforeach
                            </td>
                            <td>
                                @if ($registro->estado_registro == 0)
                                    <a href="{{ route('registros.edit', $registro) }}"
                                        class="btn btn-primary btn-xs">Gestionar</a>
                                @elseif ($registro->estado_registro == 1)
                                    <a href="https://web.whatsapp.com/send?phone=+51{{ $registro->celular }}&text=Hola%20te%20saludamos%20desde%20EL%20TRIKI,%20tenemos%20algo%20que%20decirte."
                                        target="_blank" class="btn btn-success btn-xs">WhatsApp</a>
                                    <a href="{{ route('registros.show', $registro) }}"
                                        class="btn btn-warning btn-xs">Ver</a>
                                    <a href="{{ asset('storage/' . $registro->tickets[0]->ticketpdf) }}">Ticket PDF</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>

        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.css">
@stop

@section('js')
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.dataTables.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.print.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#table-registros').DataTable({
                layout: {
                    topStart: {
                        buttons: ['excel']
                    }
                }
            });


              // Evento de cambio en el selector de sorteo
              $('#filtroEstado').on('change', function() {
                var sorteo = $(this).val();

                console.log(sorteo);

                // Aplica el filtro a la tabla
                $('#table-registros').DataTable().column(1).search(sorteo).draw();
            });


        });
    </script>
@stop
