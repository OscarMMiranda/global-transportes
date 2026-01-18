// archivo: /modulos/seguridad/permisos/js/permisos.js

let rolSeleccionado = null;
let moduloSeleccionado = null;

// Ruta base de los endpoints
const BASE = "acciones/";

$(document).ready(function () {

    // Botón regresar
    $("#btnVolver").click(function () {
        window.history.back();
    });

    // Carga inicial
    cargarRoles();
    cargarModulos();
    cargarAcciones();

    // Seleccionar rol
    $(document).on("click", "#listaRoles li", function () {
        $("#listaRoles li").removeClass("activo list-group-item-primary");
        $(this).addClass("activo list-group-item-primary");

        rolSeleccionado = $(this).data("id");
        $("#infoRol").html(`<i class="fa-solid fa-user-shield"></i> Rol: ${$(this).text()}`);

        cargarPermisos();
    });

    // Seleccionar módulo
    $(document).on("click", "#listaModulos li", function () {
        $("#listaModulos li").removeClass("activo list-group-item-primary");
        $(this).addClass("activo list-group-item-primary");

        moduloSeleccionado = $(this).data("id");
        $("#infoModulo").html(`<i class="fa-solid fa-box"></i> Módulo: ${$(this).text()}`);

        cargarPermisos();
    });

    // Buscar rol
    $("#buscarRol").on("keyup", function () {
        let q = $(this).val().toLowerCase();
        $("#listaRoles li").each(function () {
            $(this).toggle($(this).text().toLowerCase().includes(q));
        });
    });

    // Buscar módulo
    $("#buscarModulo").on("keyup", function () {
        let q = $(this).val().toLowerCase();
        $("#listaModulos li").each(function () {
            $(this).toggle($(this).text().toLowerCase().includes(q));
        });
    });

    // Guardar permisos
    $("#formPermisos").on("submit", function (e) {
        e.preventDefault();

        if (!rolSeleccionado || !moduloSeleccionado) {
            notify("Seleccione un rol y un módulo.", "error");
            return;
        }

        if (!confirm("¿Guardar cambios de permisos?")) return;

        let acciones = [];
        $("input[name='accion[]']:checked").each(function () {
            acciones.push($(this).val());
        });

        $.post(BASE + "guardar_permisos.php", {
            rol_id: rolSeleccionado,
            modulo_id: moduloSeleccionado,
            acciones: acciones
        }, function (r) {
            notify(r.msg, r.ok ? "ok" : "error");
        }, "json");
    });

    // Nuevo módulo
    $("#btnNuevoModulo").click(function () {
        let nombre = prompt("Nombre del nuevo módulo:");
        if (!nombre) return;

        $.post(BASE + "agregar_modulo.php", { nombre: nombre }, function (r) {
            notify(r.msg, r.ok ? "ok" : "error");
            if (r.ok) cargarModulos();
        }, "json");
    });

    // Nueva acción
    $("#btnNuevaAccion").click(function () {
        let nombre = prompt("Nombre de la nueva acción:");
        if (!nombre) return;

        $.post(BASE + "agregar_accion.php", { nombre: nombre }, function (r) {
            notify(r.msg, r.ok ? "ok" : "error");
            if (r.ok) cargarAcciones();
        }, "json");
    });

    // Acciones rápidas
    $("#btnMarcarTodo").click(() => $("input[name='accion[]']").prop("checked", true));
    $("#btnDesmarcarTodo").click(() => $("input[name='accion[]']").prop("checked", false));

});


// ----------------------
// FUNCIONES AJAX
// ----------------------

function cargarRoles() {
    $.get(BASE + "listar_roles.php", function (r) {
        if (!r.ok) return notify(r.msg, "error");

        let html = "";
        r.data.forEach(x => html += `<li class="list-group-item" data-id="${x.id}">${x.nombre}</li>`);
        $("#listaRoles").html(html);
    }, "json");
}

function cargarModulos() {
    $.get(BASE + "listar_modulos.php", function (r) {
        if (!r.ok) return notify(r.msg, "error");

        let html = "";
        r.data.forEach(x => html += `<li class="list-group-item" data-id="${x.id}">${x.nombre}</li>`);
        $("#listaModulos").html(html);
    }, "json");
}

function cargarAcciones() {
    $.get(BASE + "listar_acciones.php", function (r) {
        if (!r.ok) return notify(r.msg, "error");

        let html = "";
        r.data.forEach(x => {
            html += `
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="accion[]" value="${x.id}">
                    <label class="form-check-label">${x.nombre}</label>
                </div>`;
        });
        $("#listaAcciones").html(html);
    }, "json");
}

function cargarPermisos() {
    if (!rolSeleccionado || !moduloSeleccionado) return;

    $.get(BASE + "listar_permisos.php", {
        rol_id: rolSeleccionado,
        modulo_id: moduloSeleccionado
    }, function (r) {

        if (!r.ok) return notify(r.msg, "error");

        $("input[name='accion[]']").prop("checked", false);

        r.data.forEach(idAccion => {
            $(`input[value='${idAccion}']`).prop("checked", true);
        });

    }, "json");
}


// ----------------------
// NOTIFICACIONES
// ----------------------

function notify(msg, tipo = "info") {
    let box = $("<div class='notify "+tipo+"'>"+msg+"</div>");
    $("body").append(box);
    setTimeout(() => box.fadeOut(300, () => box.remove()), 2500);
}