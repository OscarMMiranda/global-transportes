// archivo: /modulos/conductores/assets/modal.js
// CONTROL DE MODALES

window.Conductores = window.Conductores || {};

(function () {

    // ============================================================
    // FUNCIÓN PARA CARGAR EMPRESAS EN EL SELECT
    // ============================================================
Conductores.cargarEmpresas = function (callback) {

    $.ajax({
        url: '/modulos/conductores/acciones/listar_empresas_simple.php',
        type: 'GET',
        dataType: 'json',

        success: function (res) {

            const sel = $('#empresa_id');
            sel.empty().append('<option value="">Seleccione...</option>');

            if (res.data) {
                res.data.forEach(function (e) {
                    sel.append('<option value="' + e.id + '">' + e.nombre + '</option>');
                });
            }

            // 🔥 EJECUTAR CALLBACK DESPUÉS DE LLENAR EL SELECT
            if (typeof callback === 'function') {
                callback();
            }
        },

        error: function (xhr) {
            console.error("Error cargando empresas:", xhr.responseText);
        }
    });
};



    // ============================================================
    // EVENTO AL ABRIR EL MODAL PRINCIPAL
    // ============================================================
    $('#modalConductor').on('show.bs.modal', function () {

        const modo = $(this).attr('data-modo');

        // Reset general
        $('#formConductor')[0].reset();
        $('#c_id').val('');

        // Reset foto
        $('#preview_foto').hide().attr('src', '');
        $('#c_foto').val('');

        // Cargar empresas SIEMPRE
        Conductores.cargarEmpresas();

        // UBIGEO: Cargar selects cuando es CREAR
        if (modo === 'crear') {
            Ubigeo.cargar('#departamento_id', '#provincia_id', '#distrito_id');
        }
    });

})();
