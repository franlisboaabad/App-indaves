@extends('adminlte::page')

@section('title', 'Nuevo Cliente')
{{-- @section('plugins.Sweetalert2', true) --}}
@section('content_header')
    <h1>Registro de cliente</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-6 col-xs-4">
            <div class="card">

                <div class="card-body">

                    {{-- @include('validators.forms') --}}

                    <div id="map"></div>


                    <form action="" method="POST" id="form_cliente">
                        <div class="form group pb-3">
                            <label for="">Nombre</label>
                            <input type="text" placeholder="Ingrese Nombre" name="nombre" id="nombre"
                                class="form-control" required value="{{ old('nombre') }}">
                        </div>
                        <div class="form group pb-3">
                            <label for="">Apellidos</label>
                            <input type="text" placeholder="Ingrese Apellidos" name="apellidos" id="apellidos"
                                class="form-control" required value="{{ old('apellidos') }}">
                        </div>
                        <div class="form group pb-3">
                            <div class="row">
                                <div class="col">
                                    <label for="">DNI</label>
                                    <input type="text" placeholder="DNI" name="dni" id="dni"
                                        class="form-control" required value="{{ old('dni') }}" maxlength="8">
                                </div>
                                <div class="col">
                                    <label for="">Celular</label>
                                    <input type="text" placeholder="Celular" name="celular" id="celular"
                                        class="form-control" required value="{{ old('celular') }}" maxlength="9">
                                </div>
                            </div>
                        </div>
                        <div class="form group pb-3">
                            <label for="">E-mail</label>
                            <input type="email" placeholder="E-mail" name="email" id="email" class="form-control"
                                required value="{{ old('email') }}">
                        </div>
                        <div class="form group pb-3">
                            <label for="">Tienda</label>
                            <input type="text" placeholder="Ingrese nombre de Tienda" name="nombre_tienda" id="nombre"
                                class="form-control" required value="{{ old('nombre_tienda') }}">
                        </div>

                        <div class="form group pb-3">
                            <label for="">Dirección</label>
                            <input type="text" placeholder="Ingrese dirección" name="direccion" id="direccion"
                                class="form-control" required value="{{ old('direccion') }}">
                        </div>

                        <div class="form group mb-3">
                            <p>Buscar ubicación en tiempo real</p>
                            <button class="btn btn-success btn-xs" id="btnUbicacion">Buscar Ubicación</button>
                        </div>

                        <div class="form group pb-3">
                            <label for="">Latitud</label>
                            <input type="text" placeholder="Latitud" name="latitud" id="latitude" class="form-control"
                                value="{{ old('latitud') }}">
                        </div>

                        <div class="form group pb-3">
                            <label for="">Longitud</label>
                            <input type="text" placeholder="Longitud" name="longitud" id="longitude" class="form-control"
                                value="{{ old('longitud') }}">
                        </div>



                        <div class="form group pb-3">
                            <label for="">Ubicacion google maps</label>
                            <input type="text" placeholder="Ingrese dirección" name="geolocalizacion"
                                id="geolocalizacion" class="form-control" value="{{ old('geolocalizacion') }}">
                        </div>

                        {{-- <iframe
  width="600"
  height="450"
  frameborder="0"
  style="border:0"
  src="https://www.google.com/maps?q=-5.2090647,-80.6437798&output=embed"
  allowfullscreen
></iframe> --}}

                        <div class="form group mt-3">
                            @csrf
                            <button type="button" id="btn_Register" class="btn btn-success btn-xs"
                                data-url="{{ route('clientes.store') }}">Registrar cliente</button>
                            <a href="{{ route('clientes.index') }}" class="btn btn-warning btn-xs">Lista de clientes</a>
                        </div>
                    </form>

                    <p id="mapUrl"></p>


                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
@stop

@section('js')
    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/cliente.js') }}"></script>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {

            // Inicializar el mapa
            var map = L.map('map').setView([0, 0], 13);

            // Agregar la capa de Mapbox
            L.tileLayer(
                'https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoiZnJhbmtsaXNib2FhYmFkIiwiYSI6ImNsd2M3ZjFvODB3bnkya2toZTBhemoxZG8ifQ.NjU_GctRoAsTRb2Bmi59sg', {
                    maxZoom: 18,
                    id: 'mapbox/streets-v11',
                    tileSize: 512,
                    zoomOffset: -1,
                    accessToken: 'pk.eyJ1IjoiZnJhbmtsaXNib2FhYmFkIiwiYSI6ImNsd2M3ZjFvODB3bnkya2toZTBhemoxZG8ifQ.NjU_GctRoAsTRb2Bmi59sg'
                }).addTo(map);

            // Función para obtener y mostrar la ubicación en tiempo real
            function onLocationFound(e) {
                var radius = e.accuracy / 2;

                L.marker(e.latlng).addTo(map)
                    .bindPopup("You are within " + radius + " meters from this point").openPopup();

                L.circle(e.latlng, radius).addTo(map);

                // Colocar la latitud y longitud en los campos de texto
                $('#latitude').val(e.latlng.lat);
                $('#longitude').val(e.latlng.lng);
            }

            function onLocationError(e) {
                alert(e.message);
            }

            map.on('locationfound', onLocationFound);
            map.on('locationerror', onLocationError);

            // Solicitar la ubicación en tiempo real
            map.locate({
                setView: true,
                maxZoom: 16,
                watch: true
            });

            $(document).ready(function() {
                // Botón para generar la URL de Google Maps
                $('#generateMap').click(function() {
                    var latitude = $('#latitude').val();
                    var longitude = $('#longitude').val();
                    if (latitude && longitude) {
                        var url = 'https://www.google.com/maps?q=' + latitude + ',' + longitude;
                        $('#mapUrl').html('<a href="' + url + '" target="_blank">' + url + '</a>');
                    } else {
                        alert('Por favor obtenga primero la ubicación actual.');
                    }
                });
            });


            // $('#btnUbicacion').click(function(e) {
            //     e.preventDefault();

            //     // Solicitar permiso para acceder a la ubicación del usuario
            //     // navigator.geolocation.getCurrentPosition(function(position) {
            //     //     const latitud = position.coords.latitude;
            //     //     const longitud = position.coords.longitude;

            //     //     // Ahora puedes enviar estas coordenadas al servidor o guardarlas en tu base de datos.
            //     //     // Por ejemplo:
            //     //     // - Enviar a una API de backend.
            //     //     // - Guardar en una tabla de ubicaciones.

            //     //     console.log("Ubicación actual:");
            //     //     console.log("Latitud:", latitud);
            //     //     console.log("Longitud:", longitud);

            //     //     // Construye la URL de Google Maps
            //     //     const geo = `https://www.google.com/maps?q=${latitud},${longitud}`;

            //     //     console.log(geo);

            //     //     $('#latitud').val(latitud);
            //     //     $('#longitud').val(longitud);
            //     //     $('#geolocalizacion').val(geo);

            //     // });

            //     if (navigator.geolocation) {
            //         navigator.geolocation.getCurrentPosition(showPosition, showError);
            //     } else {
            //         alert("La geolocalización no es soportada por este navegador.");
            //     }

            // });


            // function showPosition(position) {
            //     var latitude = position.coords.latitude;
            //     var longitude = position.coords.longitude;
            //     $("#latitud").val(latitude);
            //     $("#longitud").val(longitude);
            //     const geo = `https://www.google.com/maps?q=${latitude},${longitude}`;
            //     $('#geolocalizacion').val(geo);
            // }

            // function showError(error) {
            //     switch (error.code) {
            //         case error.PERMISSION_DENIED:
            //             alert("El usuario negó la solicitud de geolocalización.");
            //             break;
            //         case error.POSITION_UNAVAILABLE:
            //             alert("La información de ubicación no está disponible.");
            //             break;
            //         case error.TIMEOUT:
            //             alert("La solicitud para obtener la ubicación ha caducado.");
            //             break;
            //         case error.UNKNOWN_ERROR:
            //             alert("Se ha producido un error desconocido.");
            //             break;
            //     }
            // }



        });
    </script>

@stop
