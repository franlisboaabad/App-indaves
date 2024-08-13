@extends('adminlte::page')

@section('title', 'Detalle de Tickets')

@section('content_header')
    <h1>Informacion, Registro, Ticket y Detalle Ticket</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <p>Participante: {{ $registro->nombre_apellidos }}</p>
            <p>DNI y/o C.E: {{ $registro->numero_identidad }}</p>
            <p>Celular: {{ $registro->celular }}</p>
            <p>Email: {{ $registro->email }} </p>
            <p>Monto s/: {{ $registro->monto }} soles</p>

            {{-- <p><a href="{{ $registro->tickets[0]->ticketpdf }}" target="_Blank">Enlace de ticket PDF </a> </p> --}}

            <hr>


            @foreach ($registro->tickets as $ticket)
                <div>
                    <p>Ticket ID: {{ $ticket->id }}</p>
                    <p>Cantidad Tickets: {{ $ticket->cantidad_tickets }} </p>
                    <!-- Mostrar detalles del ticket si es necesario -->
                    @if ($ticket->ticketpdf)
                        <a href="{{ asset('storage/' . $ticket->ticketpdf) }}" target="_blank">Ver PDF del Ticket</a>
                    @else
                        No hay PDF disponible para este ticket.
                    @endif
                </div>
            @endforeach

            <hr>

            <p>Lista de Tickets:</p>
            <ul>
                @foreach ($registro->tickets[0]->detalles as $detalle)
                    <li>
                        {{ $detalle->correlativo_ticket }}
                    </li>
                @endforeach
            </ul>




        </div>
    </div>
@stop

@section('css')
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    {{-- <script> console.log('Hi!'); </script> --}}
@stop
