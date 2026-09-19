// ============================================================
// ARCHIVO: logistica_selects.js
// RESPONSABILIDAD: Cargar selects corporativos para logística OT (modalRegistrarViaje)
// ============================================================

console.log("LOGISTICA SELECTS JS CARGADO");

// ============================================================
// FUNCIÓN BASE CORPORATIVA
// ============================================================
function cargarSelect(url, $select, campoId, campoNombre, placeholder) {

    $.post(url, function (res) {

        let html = "<option value=''>" + placeholder + "</option>";

        // Si viene como { ok: true, data: [...] }
        if (res && res.data && Array.isArray(res.data)) {
            res = res.data;
        }

        // Si viene como array directo
        if (Array.isArray(res)) {
            $.each(res, function (i, v) {
                html += "<option value='" + v[campoId] + "'>" + v[campoNombre] + "</option>";
            });
        }

        $select.html(html);

    }, "json")
    .fail(function () {
        console.error("Error cargando select desde:", url);
        Swal.fire({
            icon: "error",
            title: "Error",
            text: "No se pudo cargar información del servidor."
        });
    });
}

// ============================================================
// VEHÍCULOS
// ============================================================
function cargarVehiculosSelect(selector) {

    cargarSelect(
        "/modulos/orden_trabajo/controllers/GetVehiculosController.php",
        $(selector),
        "id",
        "placa",
        "-- Seleccione vehículo --"
    );
}

// ============================================================
// ORÍGENES
// ============================================================
function cargarOrigenesSelect(selector) {

    cargarSelect(
        "/modulos/orden_trabajo/controllers/GetOrigenesController.php",
        $(selector),
        "id",
        "nombre",
        "-- Seleccione origen --"
    );
}

// ============================================================
// DESTINOS
// ============================================================
function cargarDestinosSelect(selector) {

    cargarSelect(
        "/modulos/orden_trabajo/controllers/GetDestinosController.php",
        $(selector),
        "id",
        "nombre",
        "-- Seleccione destino --"
    );
}
