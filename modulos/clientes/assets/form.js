// archivo: /modulos/clientes/assets/form.js
// Gestión corporativa del formulario de Clientes

// ============================
// CARGAR TIPOS DE CLIENTE
// ============================
function cargarTiposCliente(selected = null) {
    $.ajax({
        url: "/modulos/clientes/ajax/listar_tipos_cliente.php",
        type: "GET",
        dataType: "json",
        success: function (resp) {

            const select = $("#cliente_tipo");
            select.empty();

            resp.forEach(item => {
                select.append(`<option value="${item.codigo}">${item.nombre}</option>`);
            });

            if (selected) {
                select.val(selected);
            }
        }
    });
}

// ============================
// NUEVO CLIENTE
// ============================
function abrirModalNuevoCliente() {

    $("#formCliente")[0].reset();
    $("#cliente_id").val("");

    $("#modalClienteLabel").html('<i class="fa fa-plus-circle"></i> Nuevo Cliente');
    $("#modalClienteHeader")
        .removeClass("bg-warning text-dark")
        .addClass("bg-success text-white");

    cargarTiposCliente();

    Ubigeo.cargar(
        "#cliente_departamento",
        "#cliente_provincia",
        "#cliente_distrito"
    );

    $("#modalCliente").modal("show");
}

// ============================
// EDITAR CLIENTE
// ============================
function abrirModalEditarCliente(id) {

    $.ajax({
        url: "/modulos/clientes/ajax/ver_cliente.php",
        type: "GET",
        data: { id },
        dataType: "json",
        success: function (resp) {

            $("#cliente_id").val(resp.id);
            $("#cliente_nombre").val(resp.nombre);
            $("#cliente_ruc").val(resp.ruc);
            $("#cliente_direccion").val(resp.direccion);
            $("#cliente_telefono").val(resp.telefono);
            $("#cliente_correo").val(resp.correo);

            $("#modalClienteLabel").html('<i class="fa fa-edit"></i> Editar Cliente');
            $("#modalClienteHeader")
                .removeClass("bg-success text-white")
                .addClass("bg-warning text-dark");

            cargarTiposCliente(resp.tipo_cliente);

            Ubigeo.cargar(
                "#cliente_departamento",
                "#cliente_provincia",
                "#cliente_distrito",
                {
                    departamento_id: resp.departamento_id,
                    provincia_id: resp.provincia_id,
                    distrito_id: resp.distrito_id
                }
            );

            $("#modalCliente").modal("show");
        }
    });
}

// ============================
// GUARDAR CLIENTE (DELEGACIÓN CORPORATIVA)
// ============================
$("body").on("submit", "#formCliente", function (e) {
    e.preventDefault();

    const formData = $(this).serialize();

    $.ajax({
        url: "/modulos/clientes/ajax/guardar_cliente.php",
        type: "POST",
        data: formData,
        dataType: "json",
        success: function (resp) {

            if (resp.status === "ok") {

                $("#modalCliente").modal("hide");

                Swal.fire({
                    icon: "success",
                    title: "Guardado",
                    text: resp.msg
                });

                if (typeof tablaClientes !== "undefined") {
                    tablaClientes.ajax.reload(null, false);
                }

            } else {

                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: resp.msg
                });
            }
        }
    });
});
