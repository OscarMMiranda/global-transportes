// archivo: /modulos/orden_trabajo/js/crear_ot.js

// ============================================================
// CARGAR CATÁLOGOS PARA EL MODAL CREAR
// ============================================================
function cargarCatalogosCrear() {

    cargarCatalogo(
        "/modulos/orden_trabajo/controllers/ClienteController.php",
        { ajax: 1 },
        "#crear_cliente_id",
        "id",
        "nombre"
    );

    cargarCatalogo(
        "/modulos/orden_trabajo/controllers/EmpresaListarController.php",
        {},
        "#crear_empresa_id",
        "id",
        "nombre"
    );

    cargarCatalogo(
        "/modulos/orden_trabajo/controllers/TipoOTListarController.php",
        {},
        "#crear_tipo_ot",
        "id",
        "nombre"
    );

    cargarCatalogo(
        "/modulos/orden_trabajo/controllers/EstadoListarController.php",
        {},
        "#crear_estado_id",
        "id",
        "nombre"
    );
}

// ============================================================
// OBTENER CORRELATIVO SEGÚN FECHA (YYYY-MM-DD)
// ============================================================
function obtenerCorrelativoOT(fechaISO) {

    $.post("/modulos/orden_trabajo/controllers/GetNextOTController.php",
        { fecha: fechaISO },
        function(r) {

            if (!r || !r.ok) {
                alert("No se pudo obtener el correlativo de OT.");
                return;
            }

            $("#crear_numero_ot").val(r.numero_ot);
        },
    "json");
}

// ============================================================
// CALCULAR SEMANA (FORMATO SXX-YYYY)
// Recibe fecha YYYY-MM-DD
// ============================================================
function calcularSemana(fechaISO) {

    var d = new Date(fechaISO);
    var dia = d.getUTCDay() || 7;
    d.setUTCDate(d.getUTCDate() + 4 - dia);

    var inicio = new Date(Date.UTC(d.getUTCFullYear(), 0, 1));
    var semana = Math.ceil((((d - inicio) / 86400000) + 1) / 7);

    return {
        semana: ("0" + semana).slice(-2),
        year: d.getUTCFullYear()
    };
}

// ============================================================
// ABRIR MODAL Y LIMPIAR FORMULARIO
// ============================================================
function abrirModalCrearOT() {

    $("#formCrearOT")[0].reset();

    $("#crear_campo_importacion").hide();
    $("#crear_campo_exportacion").hide();
    $("#crear_campo_nacional").hide();

    cargarCatalogosCrear();

    // Fecha actual del sistema en formato válido para input date
    var hoy = new Date();
    var dd = ("0" + hoy.getDate()).slice(-2);
    var mm = ("0" + (hoy.getMonth() + 1)).slice(-2);
    var yyyy = hoy.getFullYear();

    var fechaISO = yyyy + "-" + mm + "-" + dd; // YYYY-MM-DD

    $("#crear_fecha").val(fechaISO);

    // Obtener correlativo
    obtenerCorrelativoOT(fechaISO);

    // Calcular semana
    var iso = calcularSemana(fechaISO);

    // Visual
    $("#crear_semana_ot").val("S" + iso.semana + "-" + iso.year);

    // Real para BD
    $("#crear_semana_ot_real").val(parseInt(iso.semana, 10));

    $("#modalCrearOT").modal("show");
}

// ============================================================
// SI CAMBIA LA FECHA → RECALCULAR CORRELATIVO Y SEMANA
// ============================================================
$("#crear_fecha").on("change", function () {

    var fechaISO = $(this).val();
    if (!fechaISO) return;

    obtenerCorrelativoOT(fechaISO);

    var iso = calcularSemana(fechaISO);

    // Visual
    $("#crear_semana_ot").val("S" + iso.semana + "-" + iso.year);

    // Real para BD
    $("#crear_semana_ot_real").val(parseInt(iso.semana, 10));
});

// ============================================================
// CAMPOS DINÁMICOS SEGÚN TIPO OT
// ============================================================
$("#crear_tipo_ot").on("change", function () {

    var tipo = $(this).find("option:selected").text().toUpperCase();

    $("#crear_campo_importacion").toggle(tipo === "IMPORTACION" || tipo === "IMPORTACIÓN");
    $("#crear_campo_exportacion").toggle(tipo === "EXPORTACION" || tipo === "EXPORTACIÓN");
    $("#crear_campo_nacional").toggle(tipo === "NACIONAL");
});

// ============================================================
// GUARDAR NUEVA OT (AJAX)
// ============================================================
$("#formCrearOT").on("submit", function (e) {

    e.preventDefault();

    // YA NO SE CONVIERTE LA FECHA, porque el input date usa YYYY-MM-DD
    // y así debe enviarse al backend.

    $.post("/modulos/orden_trabajo/controllers/CrearController.php",
        $(this).serialize(),
        function (resp) {

            if (resp.ok) {

                $("#modalCrearOT").modal("hide");
                tablaOT.ajax.reload(null, false);

                Swal.fire({
                    icon: "success",
                    title: "Registrado",
                    text: resp.msg,
                    confirmButtonColor: "#0d6efd"
                });

            } else {

                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: resp.msg,
                    confirmButtonColor: "#dc3545"
                });
            }

        },
    "json");
});
