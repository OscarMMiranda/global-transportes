// ======================================================
//  JS: ot_tabla_actions.js
//  RESPONSABILIDAD: Inicialización y acciones de la tabla OT
//  GLOBAL 2026 — Arquitectura Limpia (Versión 4.7)
// ======================================================

var tablaOT = null;

$(document).ready(function () {

    tablaOT = $('#ot-tabla-listado').DataTable({
        ajax: {
            url: '/modulos/orden_trabajo/api/ot/ot_listado_api.php',
            type: 'POST',

            data: function (d) {

                d.estado_ot = estadoOT;
		
                d.semana = ($('#filtro_semana').val() || '')
                    .toString()
                    .trim();

                console.log('Estado enviado:', d.estado_ot);
                console.log('Semana enviada:', d.semana);
            },

            dataSrc: 'data'
        },

        columns: [

            {
                data: null,
                render: function (data, type, row, meta) {
                    return meta.row + 1;
                }
            },

            {
                data: "numero_ot",
                render: function (data, type, row) {

                    if (type === 'sort') {

                        var limpio = (data || '').replace(/\s+/g, '');
                        var partes = limpio.split('-');
						var correlativo = parseInt(partes[0], 10) || 0;
                        var anio = parseInt(partes[1], 10) || 0;
                        return (anio * 100000) + correlativo;
                    }

                    return data;
                }
            },

            { data: "fecha" },
            { data: "cliente" },
            { data: "oc_cliente" },
            { data: "tipo_ot" },
            { data: "empresa" },
            { data: "numero_viajes" },
            { data: "estado" },

            {
                data: "id",

                render: function (id) {

                    return `
                        <div class="dropdown">

                            <button
                                class="btn btn-sm btn-outline-dark dropdown-toggle"
                                type="button"
                                data-bs-toggle="dropdown">

                                <i class="fa-solid fa-ellipsis-vertical"></i>

                            </button>

                            <ul class="dropdown-menu dropdown-menu-end">

                                <li>
                                    <a class="dropdown-item btn-ot-ver" data-id="${id}">
                                        <i class="fa-solid fa-eye me-2"></i>
                                        Ver OT
                                    </a>
                                </li>

                                <li>
                                    <a class="dropdown-item btn-ot-editar" data-id="${id}">
                                        <i class="fa-solid fa-pen-to-square me-2"></i>
                                        Editar OT
                                    </a>
                                </li>

                                <li>
                                    <a class="dropdown-item btn-ot-viaje" data-id="${id}">
                                        <i class="fa-solid fa-truck-fast me-2"></i>
                                        Registrar Viaje
                                    </a>
                                </li>

                                <li>
    								<a class="dropdown-item btn-ot-ov" data-id="${id}">
        								<i class="fa-solid fa-truck me-2"></i>
        								Ver OV
    								</a>
								</li>


                            </ul>

                        </div>
                    `;
                }
            }
        ],

        language: {
            url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
        },

        pageLength: 25,
        responsive: true,
        order: [[1, "desc"]]
    });

});

// ======================================================
// EVENTOS CORPORATIVOS
// ======================================================

$('#ot-tabla-listado').on('click', '.btn-ot-ver', function () {
    verOT($(this).data('id'));
});

$('#ot-tabla-listado').on('click', '.btn-ot-editar', function () {
    editarOT($(this).data('id'));
});

$('#ot-tabla-listado').on('click', '.btn-ot-viaje', function () {
    abrirRegistrarViaje($(this).data('id'));
});

$('#ot-tabla-listado').on('click', '.btn-ot-ov', function () {
    verOV($(this).data('id'));
});

$(document).on('click', '#btn-ot-nueva', function () {
    abrirNuevaOT();
});