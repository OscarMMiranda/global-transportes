// archivo: /modulos/empleados/assets/ubigeo.js

console.log("ubigeo.js de empleados cargado correctamente");

// ============================================================
// UBIGEO PARA EMPLEADOS
// ============================================================

const Ubigeo = {

    url_departamentos: '/modulos/ubigeo/acciones/listar_departamentos.php',
    url_provincias: '/modulos/ubigeo/acciones/listar_provincias.php',
    url_distritos: '/modulos/ubigeo/acciones/listar_distritos.php',


    // ============================================================
    // Cargar departamentos
    // ============================================================
    cargarDepartamentos: function (selDepartamento, callback) {

        $.ajax({
            url: Ubigeo.url_departamentos,
            type: 'GET',
            dataType: 'json',

            success: function (res) {

                const sel = $(selDepartamento);
                sel.empty().append('<option value="">Seleccione...</option>');

                if (res.success && res.data) {
                    res.data.forEach(function (d) {
                        sel.append(`<option value="${d.id}">${d.nombre}</option>`);
                    });
                }

                if (typeof callback === 'function') callback();
            },

            error: function (xhr) {
                console.error("Error cargando departamentos:", xhr.responseText);
            }
        });
    },


    // ============================================================
    // Cargar provincias
    // ============================================================
    cargarProvincias: function (selProvincia, departamentoId, callback) {

        $.ajax({
            url: Ubigeo.url_provincias,
            type: 'GET',
            data: { departamento_id: departamentoId },
            dataType: 'json',

            success: function (res) {

                const sel = $(selProvincia);
                sel.empty().append('<option value="">Seleccione...</option>');

                if (res.success && res.data) {
                    res.data.forEach(function (p) {
                        sel.append(`<option value="${p.id}">${p.nombre}</option>`);
                    });
                }

                if (typeof callback === 'function') callback();
            },

            error: function (xhr) {
                console.error("Error cargando provincias:", xhr.responseText);
            }
        });
    },


    // ============================================================
    // Cargar distritos
    // ============================================================
    cargarDistritos: function (selDistrito, provinciaId, callback) {

        $.ajax({
            url: Ubigeo.url_distritos,
            type: 'GET',
            data: { provincia_id: provinciaId },
            dataType: 'json',

            success: function (res) {

                const sel = $(selDistrito);
                sel.empty().append('<option value="">Seleccione...</option>');

                if (res.success && res.data) {
                    res.data.forEach(function (d) {
                        sel.append(`<option value="${d.id}">${d.nombre}</option>`);
                    });
                }

                if (typeof callback === 'function') callback();
            },

            error: function (xhr) {
                console.error("Error cargando distritos:", xhr.responseText);
            }
        });
    },


    // ============================================================
    // Cargar todo el Ubigeo (Departamento → Provincia → Distrito)
    // ============================================================
    cargar: function (selDepartamento, selProvincia, selDistrito,
                      departamentoId = null, provinciaId = null, distritoId = null) {

        const $dep = $(selDepartamento);
        const $prov = $(selProvincia);
        const $dist = $(selDistrito);

        // 1. Cargar departamentos
        Ubigeo.cargarDepartamentos(selDepartamento, function () {

            if (departamentoId) $dep.val(departamentoId);

            // 2. Cargar provincias
            if (departamentoId) {
                Ubigeo.cargarProvincias(selProvincia, departamentoId, function () {

                    if (provinciaId) $prov.val(provinciaId);

                    // 3. Cargar distritos
                    if (provinciaId) {
                        Ubigeo.cargarDistritos(selDistrito, provinciaId, function () {

                            if (distritoId) $dist.val(distritoId);
                        });
                    }
                });
            }
        });


        // EVENTOS EN CASCADA
        $dep.on('change', function () {
            const depId = $(this).val();
            $prov.empty().append('<option value="">Seleccione...</option>');
            $dist.empty().append('<option value="">Seleccione...</option>');

            if (depId) {
                Ubigeo.cargarProvincias(selProvincia, depId);
            }
        });

        $prov.on('change', function () {
            const provId = $(this).val();
            $dist.empty().append('<option value="">Seleccione...</option>');

            if (provId) {
                Ubigeo.cargarDistritos(selDistrito, provId);
            }
        });
    }
};
