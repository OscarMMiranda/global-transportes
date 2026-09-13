// archivo: /modulos/orden_trabajo/js/crear_ot.js

// =======================================
// CARGAR CATÁLOGOS PARA EL MODAL CREAR
// =======================================
function cargarCatalogosCrear() {

    // CLIENTES
    cargarCatalogo(
        "/modulos/orden_trabajo/controllers/ClienteController.php",
        { ajax: 1 },
        "#crear_cliente_id",
        "id",
        "nombre"
    );

    // EMPRESAS
    cargarCatalogo(
        "/modulos/orden_trabajo/controllers/EmpresaListarController.php",
        {},
        "#crear_empresa_id",
        "id",
        "nombre"
    );

    // TIPOS OT
    cargarCatalogo(
        "/modulos/orden_trabajo/controllers/TipoOTListarController.php",
        {},
        "#crear_tipo_ot",
        "id",
        "nombre"
    );

    // ESTADOS
    cargarCatalogo(
        "/modulos/orden_trabajo/controllers/EstadoListarController.php",
        {},
        "#crear_estado_id",
        "id",
        "nombre"
    );
}

// =======================================
// OBTENER CORRELATIVO SEGÚN FECHA
// =======================================
function obtenerCorrelativoOT(fecha) {

    $.post("/modulos/orden_trabajo/controllers/GetNextOTController.php",
        { fecha: fecha },
        function(r) {

            if (!r || !r.ok) {
                alert("No se pudo obtener el correlativo de OT.");
                return;
            }

            $("#crear_numero_ot").val(r.numero_ot);
        },
    "json");
}

// =======================================
// CALCULAR SEMANA ISO
// =======================================
function calcularSemana(fecha) {
    var d = new Date(fecha);
    var dia = d.getUTCDay() || 7;
    d.setUTCDate(d.getUTCDate() + 4 - dia);
    var inicio = new Date(Date.UTC(d.getUTCFullYear(), 0, 1));
    var semana = Math.ceil((((d - inicio) / 86400000) + 1) / 7);
    return semana;
}

// =======================================
// ABRIR MODAL Y LIMPIAR FORMULARIO
// =======================================
function abrirModalCrearOT() {

    $("#formCrearOT")[0].reset();

    $("#crear_campo_importacion").hide();
    $("#crear_campo_exportacion").hide();
    $("#crear_campo_nacional").hide();

    cargarCatalogosCrear();

    // Fecha actual del sistema
    var hoy = new Date();
    var fechaActual = hoy.toISOString().split("T")[0];

    $("#crear_fecha").val(fechaActual);

    // Obtener correlativo del año actual
    obtenerCorrelativoOT(fechaActual);

    // Calcular semana inicial
    var semana = calcularSemana(fechaActual);
	var year = fechaActual.split("-")[0];
	$("#crear_semana_ot").val("S" + semana + "-" + year);



    $("#modalCrearOT").modal("show");
}

// =======================================
// SI CAMBIA LA FECHA → RECALCULAR CORRELATIVO Y SEMANA
// =======================================
	$("#crear_fecha").on("change", function () {

    var fecha = $(this).val();
    if (!fecha) return;

    // Recalcular correlativo según el año de la fecha seleccionada
    obtenerCorrelativoOT(fecha);

   // Calcular semana ISO
	var semana = calcularSemana(fecha);

	// Obtener año desde la fecha
	var year = fecha.split("-")[0];

	// Mostrar formato S00-YYYY
	$("#crear_semana_ot").val("S" + semana + "-" + year);

});

// =======================================
// CAMPOS DINÁMICOS SEGÚN TIPO OT
// =======================================
$("#crear_tipo_ot").on("change", function () {

    var tipo = $(this).find("option:selected").text().toUpperCase();

    $("#crear_campo_importacion").toggle(tipo === "IMPORTACION" || tipo === "IMPORTACIÓN");
    $("#crear_campo_exportacion").toggle(tipo === "EXPORTACION" || tipo === "EXPORTACIÓN");
    $("#crear_campo_nacional").toggle(tipo === "NACIONAL");
});

// =======================================
// GUARDAR NUEVA OT (AJAX)
// =======================================
$("#formCrearOT").on("submit", function (e) {

    e.preventDefault();

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
