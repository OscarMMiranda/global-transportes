// ============================================================
// archivo : /modulos/asistencias/reporte_mensual/js/filtros/rm_filtro_conductor.js
// ============================================================
// Carga dinámica de conductores según empresa seleccionada
// ============================================================

$(document).ready(function () {

    // Cuando cambia la empresa → cargar conductores
    $("#filtro_empresa").on("change", function () {
        let empresa_id = $(this).val();
        rm_cargar_conductores(empresa_id);
    });

    // Cargar todos los conductores al inicio
    rm_cargar_conductores("");
});

// ============================================================
// FUNCIÓN PRINCIPAL: CARGAR CONDUCTORES
// ============================================================
function rm_cargar_conductores(empresa_id) {

    // Reset visual inmediato
    $("#filtro_conductor").html(`
        <option value="">Cargando...</option>
    `);

    $.ajax({
        url: "acciones/obtener_conductores.php",
        type: "POST",
        data: {
            empresa_id: empresa_id // "" = todas las empresas
        },
        dataType: "json",
        success: function (resp) {

            if (!resp.ok) {
                $("#filtro_conductor").html(`
                    <option value="">Todos</option>
                `);
                return;
            }

            let html = `<option value="">Todos</option>`;

            resp.data.forEach(c => {
                html += `
                    <option value="${c.id}">
                        ${c.nombres} ${c.apellidos}
                    </option>
                `;
            });

            $("#filtro_conductor").html(html);
        },
        error: function (xhr) {
            console.log("Error cargando conductores:", xhr.responseText);
            $("#filtro_conductor").html(`
                <option value="">Todos</option>
            `);
        }
    });
}
