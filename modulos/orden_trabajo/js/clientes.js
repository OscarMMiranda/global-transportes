// archivo: /modulos/orden_trabajo/js/clientes.js

// ===============================
// AUTOCOMPLETADO DE CLIENTES
// ===============================

$(document).on("keyup", "#cliente_nombre", function () {

    let buscar = $(this).val();

    if (buscar.length < 2) {
        $("#listaClientes").html("");
        return;
    }

    $.ajax({
        url: "/modulos/orden_trabajo/controllers/ClienteBuscarController.php",
        type: "GET",
        data: { buscar: buscar },
        dataType: "json",
        success: function (data) {

            let html = "";

            data.forEach(function (item) {
                html += `
                    <a href="#" class="list-group-item list-group-item-action cliente-item"
                       data-id="${item.id}" data-nombre="${item.nombre}">
                        ${item.nombre}
                    </a>
                `;
            });

            $("#listaClientes").html(html);
        }
    });

});

// ===============================
// SELECCIONAR CLIENTE
// ===============================
$(document).on("click", ".cliente-item", function (e) {
    e.preventDefault();

    $("#cliente_id").val($(this).data("id"));
    $("#cliente_nombre").val($(this).data("nombre"));
    $("#listaClientes").html("");
});

// ===============================
// CARGAR TIPOS DE CLIENTE
// ===============================
function cargarTiposCliente() {
    $.getJSON("/modulos/orden_trabajo/controllers/TipoClienteController.php", function (res) {
        let html = '<option value="">Seleccione...</option>';
        res.forEach(t => html += `<option value="${t.id}">${t.nombre}</option>`);
        $("#tipo_cliente").html(html);
    });
}

// ===============================
// CARGAR UBIGEO
// ===============================
function cargarDepartamentos() {
    $.getJSON("/modulos/orden_trabajo/controllers/UbigeoController.php?departamentos=1", function (res) {
        let html = '<option value="">Seleccione...</option>';
        res.forEach(d => html += `<option value="${d.id}">${d.nombre}</option>`);
        $("#departamento_id").html(html);
    });
}

$(document).on("change", "#departamento_id", function () {
    let id = $(this).val();
    $.getJSON("/modulos/orden_trabajo/controllers/UbigeoController.php?provincias=" + id, function (res) {
        let html = '<option value="">Seleccione...</option>';
        res.forEach(p => html += `<option value="${p.id}">${p.nombre}</option>`);
        $("#provincia_id").html(html);
        $("#distrito_id").html('<option value="">Seleccione...</option>');
    });
});

$(document).on("change", "#provincia_id", function () {
    let id = $(this).val();
    $.getJSON("/modulos/orden_trabajo/controllers/UbigeoController.php?distritos=" + id, function (res) {
        let html = '<option value="">Seleccione...</option>';
        res.forEach(d => html += `<option value="${d.id}">${d.nombre}</option>`);
        $("#distrito_id").html(html);
    });
});

// ===============================
// ABRIR MODAL NUEVO CLIENTE
// ===============================
$(document).on("click", "#btnNuevoCliente", function () {

    cargarTiposCliente();
    cargarDepartamentos();

    $("#modalNuevoCliente").modal("show");
});

// ===============================
// GUARDAR CLIENTE NUEVO
// ===============================
$(document).on("click", "#btnGuardarCliente", function () {

    let datos = $("#formNuevoCliente").serialize();

    $.ajax({
        url: "/modulos/orden_trabajo/controllers/ClienteGuardarController.php",
        type: "POST",
        data: datos,
        dataType: "json",

        success: function (res) {

            if (res.estado === "ok") {

                $("#cliente_id").val(res.id);
                $("#cliente_nombre").val(res.nombre);

                $("#modalNuevoCliente").modal("hide");

                Swal.fire({
                    icon: "success",
                    title: "Cliente registrado",
                    text: "El cliente fue agregado correctamente",
                    timer: 1500,
                    showConfirmButton: false
                });

            } else {
                Swal.fire("Error", res.mensaje, "error");
            }
        }
    });

});
