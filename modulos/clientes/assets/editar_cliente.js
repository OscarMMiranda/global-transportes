// archivo: /modulos/clientes/assets/editar_cliente.js

console.log("DEBUG EDITAR: editar_cliente.js cargado correctamente");

function abrirModalEditarCliente(id) {

    console.log("DEBUG EDITAR: Abriendo modal EDITAR CLIENTE ID:", id);

    $.ajax({
        url: "/modulos/clientes/ajax/get_cliente.php",
        type: "GET",
        data: { id: id },
        dataType: "json",


        success: function (response) {

            console.log("DEBUG EDITAR: Respuesta recibida:", response);

            if (!response || response.error) {
                Swal.fire("Error", response.error || "No se pudo cargar el cliente.", "error");
                return;
            }

            // Llenar formulario
            $("#cliente_id").val(response.id);
            $("#cliente_nombre").val(response.nombre);
            $("#cliente_tipo").val(response.tipo_cliente);
            $("#cliente_ruc").val(response.ruc);
            $("#cliente_direccion").val(response.direccion);
            $("#cliente_telefono").val(response.telefono);
            $("#cliente_correo").val(response.correo);

            // Abrir modal
            new bootstrap.Modal(document.getElementById("modalCliente")).show();
        },

        error: function (xhr, status, error) {
            console.error("DEBUG EDITAR ERROR AJAX:", error);
            Swal.fire("Error", "No se pudo obtener los datos del cliente.", "error");
        }
    });
}
