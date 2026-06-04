// archivo: /modulos/orden_trabajo/js/crear_ot.js

$(document).ready(function () {

    cargarTiposOT();
    cargarEmpresas();

    $("#cliente_nombre").on("keyup", function () {

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

    $(document).on("click", ".cliente-item", function (e) {
        e.preventDefault();

        $("#cliente_id").val($(this).data("id"));
        $("#cliente_nombre").val($(this).data("nombre"));
        $("#listaClientes").html("");
    });

    $("#formCrearOT").on("submit", function (e) {
        e.preventDefault();

        let formData = $(this).serialize();

        let btn = $("button[type='submit']");
        btn.prop("disabled", true).html("Guardando...");

        $.ajax({
            url: "../controllers/CreateController.php",
            type: "POST",
            data: formData,
            dataType: "json",

            success: function (res) {

                if (res.estado === "ok") {

                    Swal.fire({
                        icon: "success",
                        title: "Orden creada",
                        text: res.mensaje,
                        timer: 1500,
                        showConfirmButton: false
                    });

                    setTimeout(function () {
                        window.location.href = "/modulos/orden_trabajo/index.php";
                    }, 1500);

                } else {

                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: res.mensaje
                    });

                    btn.prop("disabled", false).html("💾 Crear Orden de Trabajo");
                }
            },

            error: function (xhr) {
                console.error("Error AJAX:", xhr.responseText);

                Swal.fire({
                    icon: "error",
                    title: "Error inesperado",
                    text: "No se pudo guardar la orden."
                });

                btn.prop("disabled", false).html("💾 Crear Orden de Trabajo");
            }
        });
    });

});
