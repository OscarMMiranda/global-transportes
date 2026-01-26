// /modulos/ubigeo/assets/ubigeo.js

window.Ubigeo = window.Ubigeo || {};

(function () {

    Ubigeo.cargar = function (depSel, provSel, distSel, valores) {

        var $dep  = $(depSel);
        var $prov = $(provSel);
        var $dist = $(distSel);

        valores = valores || {};
        var depVal  = valores.departamento_id || '';
        var provVal = valores.provincia_id || '';
        var distVal = valores.distrito_id || '';

        // Cargar departamentos
        $.getJSON('/modulos/ubigeo/acciones/listar_departamentos.php', function (resp) {

            $dep.empty().append('<option value="">Seleccione...</option>');
            $prov.empty().append('<option value="">Seleccione...</option>').prop('disabled', true);
            $dist.empty().append('<option value="">Seleccione...</option>').prop('disabled', true);

            if (!resp || resp.success !== true) return;

            $.each(resp.data, function (i, item) {
                var opt = $('<option>').val(item.id).text(item.nombre);
                if (depVal && depVal == item.id) opt.prop('selected', true);
                $dep.append(opt);
            });

            if (depVal) {
                cargarProvincias(depVal, true);
            }
        });

        $dep.on('change', function () {
            var idDep = $(this).val();
            $prov.empty().append('<option value="">Seleccione...</option>');
            $dist.empty().append('<option value="">Seleccione...</option>').prop('disabled', true);

            if (!idDep) {
                $prov.prop('disabled', true);
                return;
            }

            cargarProvincias(idDep, false);
        });

        $prov.on('change', function () {
            var idProv = $(this).val();
            $dist.empty().append('<option value="">Seleccione...</option>');

            if (!idProv) {
                $dist.prop('disabled', true);
                return;
            }

            cargarDistritos(idProv, false);
        });

        function cargarProvincias(idDep, inicial) {
            $.getJSON('/modulos/ubigeo/acciones/listar_provincias.php', { departamento_id: idDep }, function (resp) {

                $prov.empty().append('<option value="">Seleccione...</option>');
                $dist.empty().append('<option value="">Seleccione...</option>').prop('disabled', true);

                if (!resp || resp.success !== true) {
                    $prov.prop('disabled', true);
                    return;
                }

                $prov.prop('disabled', false);

                $.each(resp.data, function (i, item) {
                    var opt = $('<option>').val(item.id).text(item.nombre);
                    if (inicial && provVal && provVal == item.id) opt.prop('selected', true);
                    $prov.append(opt);
                });

                if (inicial && provVal) {
                    cargarDistritos(provVal, true);
                }
            });
        }

        function cargarDistritos(idProv, inicial) {
            $.getJSON('/modulos/ubigeo/acciones/listar_distritos.php', { provincia_id: idProv }, function (resp) {

                $dist.empty().append('<option value="">Seleccione...</option>');

                if (!resp || resp.success !== true) {
                    $dist.prop('disabled', true);
                    return;
                }

                $dist.prop('disabled', false);

                $.each(resp.data, function (i, item) {
                    var opt = $('<option>').val(item.id).text(item.nombre);
                    if (inicial && distVal && distVal == item.id) opt.prop('selected', true);
                    $dist.append(opt);
                });
            });
        }
    };

})();
