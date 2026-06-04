//  archivo: modulos/vehiculos/js/tabs/tab_ficha_tecnica.js

console.log("📘 tab_ficha_tecnica.js (EDICIÓN) cargado correctamente");

function cargarTabFichaEditar() {

    if (TAB_CARGADO.ficha) return;

    $("#tab-ficha-editar").html(`
        <div class="text-center text-muted py-5">
            <div class="spinner-border text-primary mb-3"></div>
            <div>Cargando ficha técnica...</div>
        </div>
    `);

    $.get('/modulos/vehiculos/acciones/editar_ficha.php', { id: ID_VEHICULO_EDITAR })
        .done(function (resp) {
            $("#tab-ficha-editar").html(resp);
            TAB_CARGADO.ficha = true;
        })
        .fail(function () {
            $("#tab-ficha-editar").html('<div class="text-danger">Error al cargar ficha técnica</div>');
        });
}

$(document).on("click", "#btnGuardarFichaTecnica", function () {

    let formData = $("#formFichaTecnica").serialize();

    $.post('/modulos/vehiculos/acciones/guardar_ficha_tecnica.php', formData, function (resp) {

        if (resp.ok) {
            Swal.fire({
                icon: 'success',
                title: 'Guardado',
                text: resp.msg,
                timer: 1500,
                showConfirmButton: false
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: resp.msg
            });
        }

    }, 'json')
    .fail(function () {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudo guardar la ficha técnica'
        });
    });
});
