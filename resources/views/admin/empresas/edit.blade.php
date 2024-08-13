@extends('adminlte::page')

@section('title', 'Editar Empresa')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')

    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <div class="card-body">
                    <form id="empresa-form" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT') <!-- Asumiendo que estás editando una empresa existente -->

                        <div class="form-group">
                            <label for="">Empresa</label>
                            <input type="text" name="name" value="{{ $empresa->name }}" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="">Dirección</label>
                            <input type="text" name="address" value="{{ $empresa->address }}" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="">Celular</label>
                            <input type="text" name="phone" value="{{ $empresa->phone }}" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="">Email</label>
                            <input type="text" name="email" value="{{ $empresa->email }}" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="">Website</label>
                            <input type="text" name="website" value="{{ $empresa->website }}" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="">Descripción</label>
                            <textarea name="description" rows="6" class="form-control">
                                {{ $empresa->description }}
                            </textarea>
                        </div>

                        <div class="form-group">
                            <label for="">Logo</label>
                            <input type="file" name="logo" class="form-control">
                        </div>

                        <div class="form-group">
                            <button type="button" id="edit-btn" class="btn btn-info btn-sm">Editar</button>
                            <a href="{{ route('empresas.index') }}" class="btn btn-primary btn-sm">Empresas</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card">
                <div class="card-body">
                    <img src="{{ asset('storage/'.$empresa->logo ) }}" alt="" class="img-fluid">
                </div>
            </div>
        </div>
    </div>


@stop

@section('css')
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
@stop

@section('js')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        $('#edit-btn').on('click', function() {
            var formData = new FormData($('#empresa-form')[0]);

            $.ajax({
                url: "{{ route('empresas.update', $empresa->id) }}", // Cambia la URL al endpoint adecuado
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    Swal.fire({
                        title: 'Éxito!',
                        text: 'La empresa ha sido actualizada correctamente.',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "{{ route('empresas.index') }}";
                        }
                    });
                },
                error: function(xhr) {
                    var errors = xhr.responseJSON.errors;
                    var errorMessage = '';
                    $.each(errors, function(key, value) {
                        errorMessage += value[0] + '\n';
                    });

                    Swal.fire({
                        title: 'Error!',
                        text: errorMessage,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });
    });
    </script>

@stop
