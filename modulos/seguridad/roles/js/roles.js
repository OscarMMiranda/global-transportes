// archivo: /modulos/seguridad/roles/js/roles.js

$(document).ready(function () {

    // ============================
    // CARGAR LISTA DE ROLES
    // ============================
    function cargarRoles(idResaltar) {
        $.ajax({
            url: "acciones/listar_roles.php",
            type: "POST",
            dataType: "json",
            success: function (resp) {
                if (resp.ok) {
                    $("#listaRoles").html(resp.html);

                    if (idResaltar) {
                        var fila = $("#fila_rol_" + idResaltar);
                        if (fila.length) {
                            fila.addClass("resaltado");
                            setTimeout(function () {
                                fila.removeClass("resaltado");
                            }, 2000);
                        }
                    }
                } else {
                    notificar(resp.mensaje || "Error al cargar roles.", "error");
                }
            },
            error: function () {
                notificar("Error de comunicación con el servidor al cargar roles.", "error");
            }
        });
    }

    cargarRoles();


    // ============================
    // GUARDAR ROL (crear o editar)
    // ============================
    $("#formRol").on("submit", function (e) {
        e.preventDefault();

        var nombre = $("#rol_nombre").val().trim();
        if (nombre === "") {
            notificar("El nombre del rol no puede estar vacío.", "warning");
            return;
        }

        var modo = $("#modo").val();
        var url = (modo === "editar")
            ? "acciones/editar_rol.php"
            : "acciones/agregar_rol.php";

        var $btn = $(this).find("button[type=submit]");
        $btn.prop("disabled", true);

        $.ajax({
            url: url,
            type: "POST",
            data: $(this).serialize(),
            dataType: "json",
            success: function (resp) {
                var tipo = resp.tipo || (resp.ok ? "success" : "error");
                notificar(resp.mensaje || resp.msg || "Operación realizada.", tipo);

                if (resp.ok) {
                    var idResaltar = resp.id || null;

                    $("#formRol")[0].reset();
                    $("#modo").val("crear");
                    $("#rol_id").val("");
                    $("#btnCancelarEdicion").addClass("d-none");

                    cargarRoles(idResaltar);
                }
            },
            error: function () {
                notificar("Error de comunicación con el servidor al guardar el rol.", "error");
            },
            complete: function () {
                $btn.prop("disabled", false);
            }
        });
    });


    // ============================
    // CANCELAR EDICIÓN
    // ============================
    $("#btnCancelarEdicion").on("click", function () {
        $("#formRol")[0].reset();
        $("#modo").val("crear");
        $("#rol_id").val("");
        $(this).addClass("d-none");
        $(".fila-rol").removeClass("editando");
    });


    // ============================
    // ELIMINAR ROL (con modal)
    // ============================
    $(document).on("click", ".btn-eliminar-rol", function () {
        var id = $(this).data("id");
        var nombre = $(this).data("nombre");

        confirmarAccion(
            "Eliminar rol",
            "¿Está seguro de eliminar el rol \"" + nombre + "\"?",
            function () {
                $.ajax({
                    url: "acciones/eliminar_rol.php",
                    type: "POST",
                    data: { id: id },
                    dataType: "json",
                    success: function (resp) {
                        var tipo = resp.tipo || (resp.ok ? "success" : "error");
                        notificar(resp.mensaje || resp.msg || "Operación realizada.", tipo);
                        if (resp.ok) cargarRoles();
                    },
                    error: function () {
                        notificar("Error de comunicación con el servidor al eliminar el rol.", "error");
                    }
                });
            }
        );
    });


    // ============================
    // EDITAR ROL
    // ============================
    $(document).on("click", ".btn-editar-rol", function () {
        var id = $(this).data("id");
        var nombre = $(this).data("nombre");

        $("#rol_id").val(id);
        $("#rol_nombre").val(nombre);
        $("#modo").val("editar");
        $("#btnCancelarEdicion").removeClass("d-none");

        $(".fila-rol").removeClass("editando");
        $("#fila_rol_" + id).addClass("editando");
    });

});