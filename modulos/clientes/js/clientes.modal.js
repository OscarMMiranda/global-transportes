//  archivo :   modulos/clientes/js/clientes.modal.js

function abrirModalCliente(id = null) {

    let url = "index.php?action=form";
    if (id) url += "&id=" + id;

    $("#modalFormClienteTitulo").text(id ? "Editar Cliente" : "Registrar Cliente");

    $("#modalFormClienteBody").html("<div class='text-center p-4'>Cargando...</div>");

    $("#modalFormCliente").modal("show");

    $.get(url, function (html) {
        $("#modalFormClienteBody").html(html);
    });
}

$(document).on("submit", "#formCliente", function (e) {
    e.preventDefault();

    $.post("index.php?action=form", $(this).serialize(), function (resp) {
        $("#modalFormCliente").modal("hide");
        $("#tablaClientes").DataTable().ajax.reload(null, false);
    });
});
