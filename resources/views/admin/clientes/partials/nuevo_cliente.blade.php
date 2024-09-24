<div class="modal fade" id="createClientModal" tabindex="-1" role="dialog" aria-labelledby="createClientModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createClientModalLabel">Crear Nuevo Cliente</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="createClientForm">
                    @csrf

                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="tipo_documento">Tipo de Documento</label>
                                <select id="tipo_documento" name="tipo_documento" class="form-control" required>
                                    <option value="0">SIN DOCUMENTO</option>
                                    <option value="1">DNI</option>
                                    <option value="2">RUC</option>
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="documento">Documento</label>
                                <div class="input-group">
                                    <input type="text" id="documento" name="documento" class="form-control" required>
                                    <div class="input-group-append">
                                        <button type="button" id="searchDocumentBtn" class="btn btn-outline-secondary">
                                            <i class="fa fa-search"></i> Buscar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">

                            <div class="form-group">
                                <label for="nombre_comercial">Nombre Comercial</label>
                                <input type="text" id="nombre_comercial" name="nombre_comercial" class="form-control"
                                    required>
                            </div>
                        </div>

                        <div class="col">

                            <div class="form-group">
                                <label for="razon_social">Razón Social</label>
                                <input type="text" id="razon_social" name="razon_social" class="form-control"
                                    required>
                            </div>
                        </div>
                    </div>


                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="checkDatosAvanzados">
                        <label class="form-check-label" for="flexCheckDefault"> Datos Avanzados </label>
                    </div>


                    <div id="datos_avanzados">

                        <div class="form-group">
                            <label for="direccion">Dirección</label>
                            <input type="text" id="direccion" name="direccion" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="departamento">Departamento</label>
                            <input type="text" id="departamento" name="departamento" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="provincia">Provincia</label>
                            <input type="text" id="provincia" name="provincia" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="distrito">Distrito</label>
                            <input type="text" id="distrito" name="distrito" class="form-control" required>
                        </div>


                        <div class="row">

                            <div class="col">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" id="email" name="email" class="form-control" required>
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-group">
                                    <label for="celular">Celular</label>
                                    <input type="text" id="celular" name="celular" class="form-control"
                                        required>
                                </div>

                            </div>
                        </div>

                    </div>



                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" id="saveClientBtn" class="btn btn-success">Guardar</button>
            </div>
        </div>
    </div>
</div>



<script>
    document.addEventListener('DOMContentLoaded', function() {

        //buscar documento dni , ruc
        $('#searchDocumentBtn').on('click', function() {
            var documento = $('#documento').val();
            var tipoDocumento = $('#tipo_documento').val();

            $.ajax({
                url: "{{ route('clientes.search') }}",
                type: 'POST',
                data: {
                    documento: documento,
                    tipo_documento: tipoDocumento,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response.success) {
                        $('#nombre_comercial').val(response.data.razon_social);
                        $('#razon_social').val(response.data.razon_social);
                        $('#direccion').val(response.data.direccion);
                        $('#departamento').val(response.data.departamento);
                        $('#provincia').val(response.data.provincia);
                        $('#distrito').val(response.data.distrito);
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: response.message,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'No se pudo realizar la búsqueda.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });
        //end search


        //guardar cliente
        $('#saveClientBtn').on('click', function() {
            const formData = new FormData($('#createClientForm')[0]);

            $.ajax({
                url: "{{ route('clientes.store') }}", // Cambia la URL al endpoint adecuado
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.success) {
                        $('#createClientModal').modal('hide');
                        $('#createClientForm')[0].reset();

                        // Añadir el nuevo cliente al select
                        addClientToSelect(response.cliente);
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 400) {
                        // Manejar el caso de cliente ya existente
                        Swal.fire({
                            title: 'Error!',
                            text: xhr.responseJSON.message ||
                                'Cliente con el mismo documento ya existe.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    } else {
                        // Manejar otros errores de validación
                        var errors = xhr.responseJSON.errors;
                        var errorMessage = '';
                        if (errors) {
                            $.each(errors, function(key, value) {
                                errorMessage += value[0] + '\n';
                            });
                        } else {
                            errorMessage = 'Ha ocurrido un error inesperado.';
                        }

                        Swal.fire({
                            title: 'Error!',
                            text: errorMessage,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                }
            });
        });


        // Función para añadir el cliente al select
        function addClientToSelect(cliente) {
            var $select = $('#cliente_id');
            var newOption = new Option(cliente.nombre_comercial, cliente.id, true, true);
            $select.append(newOption).trigger('change');
        }


        //Check datos avanzandos
        $('#datos_avanzados').hide();

        $('#checkDatosAvanzados').change(function(e) {
            e.preventDefault();
            if (this.checked) {
                $('#datos_avanzados').show();
            } else {
                $('#datos_avanzados').hide();
            }
        });


        //function modal

        $('#createClientModal').on('shown.bs.modal', function () {
            $('#documento').focus();
        });

    });
</script>
