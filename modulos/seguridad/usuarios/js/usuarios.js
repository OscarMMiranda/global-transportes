// archivo: /modulos/seguridad/usuarios/js/usuarios.js
// Lógica principal del módulo Usuarios (versión ERP con rutas absolutas)

$(document).ready(function () {

    cargarRoles();
    cargarUsuarios();

    // Guardar / Actualizar usuario
    $("#form-usuario").on("submit", function (e) {
        e.preventDefault();
        guardarUsuario();
    });

    // Cancelar edición
    $("#btn-cancelar").on("click", function () {
        limpiarFormulario();
    });

    // Click en fila para editar
    $(document).on("click", "#lista-usuarios table tbody tr", function () {
        var id = $(this).data("id");
        seleccionarFila(this);
        cargarUsuario(id);
    });

    // Click en botón eliminar
    $(document).on("click", ".btn-eliminar", function (e) {
        e.stopPropagation();
        var id = $(this).data("id");

        confirmarAccion(
            "¿Está seguro de eliminar este usuario?",
            function () {
                eliminarUsuario(id);
            }
        );
    });

});

// ---------------------------------------------------------
// CARGAR LISTA DE USUARIOS
// ---------------------------------------------------------

function cargarUsuarios() {

    $("#lista-usuarios").html(`
        <div class="text-center text-muted py-4">
            <i class="fa-solid fa-spinner fa-spin"></i> Cargando usuarios...
        </div>
    `);

    $.ajax({
        url: "/modulos/seguridad/usuarios/acciones/listar_usuarios.php",
        type: "GET",
        dataType: "json",
        success: function (resp) {

            if (!resp.ok || !Array.isArray(resp.data)) {
                notificar("No se pudo cargar la lista de usuarios", "error");
                return;
            }

            var html = `
                <table class="table table-sm table-hover tabla-erp">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Usuario</th>
                            <th>Rol</th>
                            <th style="width:40px;"></th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            resp.data.forEach(function (u) {
                html += `
                    <tr data-id="${u.id}">
                        <td>${u.nombre}</td>
                        <td>${u.usuario}</td>
                        <td>${u.rol || "-"}</td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-danger btn-eliminar" data-id="${u.id}">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });

            html += `
                    </tbody>
                </table>
            `;

            $("#lista-usuarios").html(html);
        },
        error: function () {
            notificar("Error de comunicación con el servidor", "error");
        }
    });
}

// ---------------------------------------------------------
// CARGAR ROLES EN EL SELECT
// ---------------------------------------------------------

function cargarRoles() {
    $.ajax({
        url: "/modulos/seguridad/roles/acciones/listar_roles_json.php",
        type: "GET",
        dataType: "json",
        success: function (resp) {
            if (!resp.ok) return;

            var html = '<option value="">Seleccione...</option>';

            resp.data.forEach(function (r) {
                html += `<option value="${r.id}">${r.nombre}</option>`;
            });

            $("#rol_id").html(html);
        }
    });
}

// ---------------------------------------------------------
// GUARDAR O ACTUALIZAR USUARIO
// ---------------------------------------------------------

function guardarUsuario() {

    var id = $("#usuario_id").val();
    var url = (id === "") 
        ? "/modulos/seguridad/usuarios/acciones/agregar_usuario.php"
        : "/modulos/seguridad/usuarios/acciones/editar_usuario.php";

    $.ajax({
        url: url,
        type: "POST",
        data: $("#form-usuario").serialize(),
        dataType: "json",
        success: function (resp) {

            if (!resp.ok) {
                notificar(resp.msg, "error");
                return;
            }

            notificar(resp.msg, "success");

            limpiarFormulario();
            cargarUsuarios();
        },
        error: function () {
            notificar("Error de comunicación con el servidor", "error");
        }
    });
}

// ---------------------------------------------------------
// CARGAR DATOS DE UN USUARIO PARA EDITAR
// ---------------------------------------------------------

function cargarUsuario(id) {

    $.ajax({
        url: "/modulos/seguridad/usuarios/acciones/obtener_usuario.php",
        type: "GET",
        data: { id: id },
        dataType: "json",
        success: function (resp) {

            if (!resp.ok) {
                notificar(resp.msg, "error");
                return;
            }

            var u = resp.data;

            $("#usuario_id").val(u.id);
            $("#nombre").val(u.nombre);
            $("#usuario").val(u.usuario);
            $("#rol_id").val(u.rol);

            $("#btn-cancelar").removeClass("d-none");
            $("#btn-guardar").text("Actualizar");
        },
        error: function () {
            notificar("Error de comunicación con el servidor", "error");
        }
    });
}

// ---------------------------------------------------------
// ELIMINAR USUARIO
// ---------------------------------------------------------

function eliminarUsuario(id) {

    $.ajax({
        url: "/modulos/seguridad/usuarios/acciones/eliminar_usuario.php",
        type: "POST",
        data: { id: id },
        dataType: "json",
        success: function (resp) {

            if (!resp.ok) {
                notificar(resp.msg, "error");
                return;
            }

            notificar(resp.msg, "success");
            cargarUsuarios();
            limpiarFormulario();
        },
        error: function () {
            notificar("Error de comunicación con el servidor", "error");
        }
    });
}

// ---------------------------------------------------------
// LIMPIAR FORMULARIO
// ---------------------------------------------------------

function limpiarFormulario() {
    $("#form-usuario")[0].reset();
    $("#usuario_id").val("");
    $("#btn-cancelar").addClass("d-none");
    $("#btn-guardar").text("Guardar");
    $(".fila-seleccionada").removeClass("fila-seleccionada");
}

// ---------------------------------------------------------
// RESALTAR FILA SELECCIONADA
// ---------------------------------------------------------

function seleccionarFila(fila) {
    $(".fila-seleccionada").removeClass("fila-seleccionada");
    $(fila).addClass("fila-seleccionada");
}