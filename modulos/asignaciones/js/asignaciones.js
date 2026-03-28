// archivo: /modulos/asignaciones/js/asignaciones.js

let apiUrl = null; // disponible globalmente

$(function () {

    apiUrl  = window.ASIGNACIONES_API_URL;
    const tableId = '#tablaAsignaciones';
    let tabla;

    // ================================
    // PROTECCIÓN ANTI-REINICIALIZACIÓN
    // ================================
    if ($.fn.DataTable.isDataTable(tableId)) {
        $(tableId).DataTable().clear().destroy();
    }

    // ================================
    // TABLA PRINCIPAL
    // ================================
    tabla = $(tableId).DataTable({
        ajax: {
            url: `${apiUrl}?method=listar`,
            dataSrc: ''
        },
        columns: [
            { data: 'conductor' },
            { data: 'tracto' },
            { data: 'carreta' },
            {
                data: 'inicio',
                render: fecha => fecha ? new Date(fecha).toLocaleDateString() : ''
            },
            {
                data: 'fin',
                render: fecha => fecha ? new Date(fecha).toLocaleDateString() : ''
            },
            { data: 'estado' },
            {
                data: 'id',
                orderable: false,
                render: (id, _, row) => {
                    if (row.estado === 'activo') {
                        return `
                            <button class="btn btn-warning btn-finalizar" data-id="${id}">
                                Finalizar
                            </button>`;
                    }
                    return '<span class="text-muted">—</span>';
                }
            }
        ]
    });

    // ================================
    // CARGAR FILTROS
    // ================================
    function cargarFiltros() {

        // Conductor
        $.getJSON(`${apiUrl}?method=conductores`, function(data) {
            const $f = $('#filtroConductor');
            $f.empty().append('<option value="">Todos</option>');
            data.forEach(item => {
                $f.append(`<option value="${item.nombre}">${item.nombre}</option>`);
            });
        });

        // Tracto
        $.getJSON(`${apiUrl}?method=tractos`, function(data) {
            const $f = $('#filtroTracto');
            $f.empty().append('<option value="">Todos</option>');
            data.forEach(item => {
                $f.append(`<option value="${item.placa}">${item.placa}</option>`);
            });
        });

        // Carreta
        $.getJSON(`${apiUrl}?method=carretas`, function(data) {
            const $f = $('#filtroCarreta');
            $f.empty().append('<option value="">Todos</option>');
            data.forEach(item => {
                $f.append(`<option value="${item.placa}">${item.placa}</option>`);
            });
        });
    }

    cargarFiltros(); // <-- ahora sí funciona

    // ================================
    // FILTROS SOBRE LA TABLA
    // ================================
    $('#filtroConductor').on('change', function () {
        tabla.column(0).search(this.value).draw();
    });

    $('#filtroTracto').on('change', function () {
        tabla.column(1).search(this.value).draw();
    });

    $('#filtroCarreta').on('change', function () {
        tabla.column(2).search(this.value).draw();
    });

    $('#filtroEstado').on('change', function () {
        tabla.column(5).search(this.value).draw();
    });

    // ================================
    // MODAL ASIGNAR
    // ================================
    $('#modalAsignar').on('shown.bs.modal', function () {
        // cargarSelect(`${apiUrl}?method=conductores`, '[data-role="conductor"]', 'Conductor');
        cargarSelect(`${apiUrl}?method=conductores_disponibles`, '[data-role="conductor"]', 'Conductor');

        // cargarSelect(`${apiUrl}?method=tractos`,     '[data-role="tracto"]',    'Tracto');
        // cargarSelect(`${apiUrl}?method=carretas`,    '[data-role="carreta"]',   'Carreta');
		
		cargarSelect(`${apiUrl}?method=tractos_disponibles`, '[data-role="tracto"]', 'Tracto');
		cargarSelect(`${apiUrl}?method=carretas_disponibles`, '[data-role="carreta"]', 'Carreta');


	});

    function cargarSelect(url, selector, label) {
        const $select = $(selector);

        $select.prop('disabled', true)
               .empty()
               .append(`<option value="">Cargando ${label}...</option>`);

        $.getJSON(url)
            .done(function (data) {
                $select.empty()
                       .append(`<option value="">Seleccione ${label}</option>`);

                $.each(data, function (_, item) {
                    $select.append(
                        $('<option>', {
                            value: item.id,
                            text: item.nombre || item.placa
                        })
                    );
                });
            })
            .fail(function (jqXHR) {
                console.error(`Error al cargar ${label}:`, jqXHR.responseText);
                $select.empty()
                       .append(`<option value="">Error al cargar ${label}</option>`);
            })
            .always(function () {
                $select.prop('disabled', false);
            });
    }

    // ================================
    // GUARDAR ASIGNACIÓN
    // ================================
    $('#formAsignacion').on('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(this);

        fetch(`${apiUrl}?method=guardar`, {
            method: 'POST',
            body: formData
        })
            .then(r => r.json())
            .then(resp => {
                if (resp.ok) {
                    $('#modalAsignar').modal('hide');
                    tabla.ajax.reload();
                } else {
                    alert('Error al guardar: ' + resp.error);
                }
            })
            .catch(err => alert('Fallo en la petición: ' + err));
    });

    // ================================
    // FINALIZAR ASIGNACIÓN
    // ================================
    $(tableId).on('click', '.btn-finalizar', function () {
        const id = $(this).data('id');
        $('#finalizar_id').val(id);
        $('#modalFinalizar').modal('show');
    });

    $('#formFinalizar').on('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(this);

        fetch(`${apiUrl}?method=finalizar`, {
            method: 'POST',
            body: formData
        })
            .then(r => r.json())
            .then(resp => {
                if (resp.ok) {
                    $('#modalFinalizar').modal('hide');
                    tabla.ajax.reload();
                } else {
                    alert('Error al finalizar: ' + resp.error);
                }
            })
            .catch(err => alert('Fallo en la petición: ' + err));
    });

});
