$(document).ready(function () {
    $("#btnEnviar").click(function (e) {
        e.preventDefault();
        // Obtener los datos del formulario

        var formData = new FormData();
        formData.append("numero_identidad", $("#numero_identidad").val());
        formData.append("nombre_apellidos", $("#nombre_apellidos").val());
        formData.append("celular", $("#celular").val());
        formData.append("email", $("#email").val());
        formData.append("monto", $("#monto").val());

        if ($("#image")[0].files[0]) {
            formData.append("image", $("#image")[0].files[0]);
        }

        // Realizar la solicitud AJAX
        $.ajax({
            url: "http://localhost:8000/api/guardar-datos/",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                // Manejar la respuesta del servidor aquí
                if (response.status === "error") {
                    // Mostrar mensajes de error en un alert
                    var errorMessage = Object.values(response.message).join("\n");
                    alert(errorMessage);
                } else {
                    // Procesar la respuesta en caso de éxito
                    Swal.fire({
                        title: "¡CONFIRMACIÓN!",
                        text: response.message,
                        icon: "success",
                    });
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                }
            },
            error: function (error) {
                alert( "Hubo un error al procesar su solicitud. Por favor, inténtelo nuevamente.");
                console.error("Error en la solicitud AJAX:", error);
            },
        });
    });
});
