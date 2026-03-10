// archivo: /modulos/clientes/js/clientes.filtros.js

//

function inicializarFiltrosClientes() {

    // ================================
    // 1. Filtro por estado (tabs)
    // ================================
    $(document).on("click", ".tab-estado", function (e) {
        e.preventDefault();

        let estado = $(this).data("estado"); // activos / inactivos / todos

        const nuevaUrl = "index.php?action=list&estado=" + estado;
        window.history.pushState({}, "", nuevaUrl);

        $('#tablaClientes').DataTable().ajax.url(
            "index.php?action=api&type=list&estado=" + estado
        ).load();
    });

    // ================================
    // 2. Filtro por texto (buscador)
    // ================================
    $(document).on("keyup", "#filtroTextoClientes", function () {
        let valor = $(this).val();
        $('#tablaClientes').DataTable().search(valor).draw();
    });

    // ================================
    // 3. Filtro por estado (select)
    // ================================
    $(document).on("change", "#filtroEstadoClientes", function () {
        let estado = $(this).val(); // activos / inactivos / todos

        const nuevaUrl = "index.php?action=list&estado=" + estado;
        window.history.pushState({}, "", nuevaUrl);

        $('#tablaClientes').DataTable().ajax.url(
            "index.php?action=api&type=list&estado=" + estado
        ).load();
    });
}
