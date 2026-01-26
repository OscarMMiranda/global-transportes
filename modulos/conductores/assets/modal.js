// archivo: /modulos/conductores/assets/modal.js
// CONTROL DE MODALES

window.Conductores = window.Conductores || {};

(function () {

    $('#modalConductor').on('show.bs.modal', function () {

        const modo = $(this).attr('data-modo');

        // Reset general
        $('#formConductor')[0].reset();
        $('#c_id').val('');

        // Reset foto
        $('#preview_foto').hide().attr('src', '');
        $('#c_foto').val('');

        // UBIGEO: Cargar selects cuando es CREAR
        if (modo === 'crear') {
            Ubigeo.cargar('#departamento_id', '#provincia_id', '#distrito_id');
        }
    });

})();
