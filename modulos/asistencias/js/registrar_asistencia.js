/*
    archivo: /modulos/asistencias/js/registrar_asistencia.js
    módulo: asistencias
*/

console.log("JS REGISTRAR ASISTENCIA CARGADO REALMENTE");

// ------------------------------------------------------------
// FECHA Y HORAS POR DEFECTO AL ABRIR EL MODAL
// (ESTE BLOQUE DEBE ESTAR FUERA DEL DOMContentLoaded)
// ------------------------------------------------------------
$("#modalRegistrarAsistencia").on("show.bs.modal", function () {

    console.log("APLICANDO FECHA Y HORAS POR DEFECTO");

    // Fecha actual en formato YYYY-MM-DD
    let hoy = new Date().toISOString().split('T')[0];
    $("#fecha").val(hoy);

    // Horas por defecto (estándar operativo)
    $("#hora_entrada").val("08:00");
    $("#hora_salida").val("18:00");

    // Reset de selects
    $("#empresa_id").val("");
    $("#conductor_id").html('<option value="">Seleccione...</option>');
    $("#codigo_tipo").val("");

    // Otros campos
    $("#observacion").val("");
    $("#alertaAsistencia").html("");
});

document.addEventListener("DOMContentLoaded", function () {

    console.log("registrar_asistencia.js CARGADO");

    // ALERTA VISUAL (Bootstrap)
    // ------------------------------------------------------------
    function mostrarAlerta(tipo, mensaje) {
        let html = `
            <div class="alert alert-${tipo} alert-dismissible fade show" role="alert">
                ${mensaje}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        document.getElementById('alertaAsistencia').innerHTML = html;

        setTimeout(() => {
            let alerta = document.querySelector('.alert');
            if (alerta) alerta.remove();
        }, 4000);
    }

    // ------------------------------------------------------------
    // MARCAR CAMPO INVÁLIDO
    // ------------------------------------------------------------
    function marcarCampo(selector) {
        let el = document.querySelector(selector);
        if (!el) return;

        el.classList.add('is-invalid');
        setTimeout(() => el.classList.remove('is-invalid'), 2000);
    }

    // ------------------------------------------------------------
    // LIMPIAR FORMULARIO AL CERRAR MODAL
    // ------------------------------------------------------------
    document.getElementById('modalRegistrarAsistencia')
        .addEventListener('hidden.bs.modal', function () {

            this.querySelectorAll('input, select, textarea').forEach(el => el.value = "");
            document.getElementById('alertaAsistencia').innerHTML = "";
        });

    // ------------------------------------------------------------
    // CARGAR CONDUCTORES SEGÚN EMPRESA
    // ------------------------------------------------------------
    $(document).on('change', '#empresa_id', function () {

        console.log("CAMBIO DE EMPRESA:", $('#empresa_id').val());

        let empresa_id = $('#empresa_id').val();
        $('#conductor_id').html('<option value="">Cargando...</option>');

        $.get('/modulos/asistencias/ajax/get_conductores.php', {
            empresa_id: empresa_id
        }, function (r) {

            console.log("CONDUCTORES RECIBIDOS:", r);

            $('#conductor_id').html('<option value="">Seleccione...</option>');

            r.forEach(function (item) {
                $('#conductor_id').append(
                    '<option value="' + item.id + '">' + item.nombre + '</option>'
                );
            });

        }, 'json');
    });

    // ------------------------------------------------------------
    // REGISTRAR ASISTENCIA
    // ------------------------------------------------------------
    $(document).on('click', '#btnRegistrarAsistencia', function () {

        console.log("CLICK EN GUARDAR ASISTENCIA");

        // VALIDACIÓN
        if ($('#empresa_id').val() === "") {
            mostrarAlerta("warning", "Seleccione una empresa");
            marcarCampo('#empresa_id');
            return;
        }

        if ($('#conductor_id').val() === "") {
            mostrarAlerta("warning", "Seleccione un conductor");
            marcarCampo('#conductor_id');
            return;
        }

        if ($('#codigo_tipo').val() === "") {
            mostrarAlerta("warning", "Seleccione un tipo de asistencia");
            marcarCampo('#codigo_tipo');
            return;
        }

        if ($('#fecha').val() === "") {
            mostrarAlerta("warning", "Seleccione una fecha");
            marcarCampo('#fecha');
            return;
        }

        // SPINNER
        let btn = $('#btnRegistrarAsistencia');
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Guardando...');

        // AJAX → ENVÍO REAL
        $.post('/modulos/asistencias/acciones/registrar.php', {
            tipo_registro: 'asistencia',
            empresa_id: $('#empresa_id').val(),
            conductor_id: $('#conductor_id').val(),
            fecha: $('#fecha').val(),
            codigo_tipo: $('#codigo_tipo').val(),
            hora_entrada: $('#hora_entrada').val(),
            hora_salida: $('#hora_salida').val(),
            observacion: $('#observacion').val()
        }, function (r) {

            console.log("RESPUESTA DEL BACKEND:", r);

            btn.prop('disabled', false).html('Guardar asistencia');

            if (r.ok) {
                mostrarAlerta("success", "Asistencia registrada correctamente");

                setTimeout(() => {
                    let modalEl = document.getElementById('modalRegistrarAsistencia');
                    let modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                    modal.hide();
                }, 800);

            } else {
                mostrarAlerta("danger", r.error || "Error al registrar asistencia");
            }

        }, 'json')
        .fail(function (xhr) {
            console.log("ERROR AJAX:", xhr.responseText);
            btn.prop('disabled', false).html('Guardar asistencia');
            mostrarAlerta("danger", "Error de comunicación con el servidor");
        });

    });

});

// ============================================================
// LISTENER UNIVERSAL (SIDEBAR + TARJETAS)
// ============================================================

document.addEventListener("click", function(e) {

    if (e.target.closest("#btnAbrirRegistrarAsistencia") ||
        e.target.closest("#btnSidebarRegistrarAsistencia")) {

        console.log("ABRIENDO MODAL REGISTRO (universal)");

        $("#modalRegistrarAsistencia").modal("show");
    }

});


// Cargar tipos en el modal de registrar
function cargarTiposRegistrar() {
    $.get("/modulos/asistencias/ajax/get_tipos.php", function(r) {

        $("#codigo_tipo").html('<option value="">Seleccione...</option>');

        r.forEach(t => {
            $("#codigo_tipo").append(
                `<option value="${t.codigo}">${t.descripcion}</option>`
            );
        });

    }, "json");
}

$("#modalRegistrarAsistencia").on("show.bs.modal", function() {
    cargarTiposRegistrar();
});


// ============================================================
// FIN JS PARA REGISTRAR ASISTENCIA
// ============================================================
