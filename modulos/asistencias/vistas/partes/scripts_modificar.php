<?php
// archivo: /modulos/asistencias/vistas/partes/scripts_modificar.php
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script src="/modulos/asistencias/js/modificar_asistencia.js"></script>
<script src="/modulos/asistencias/js/guardar_asistencia.js"></script>

<script>
$(document).ready(function() {

    $("#f_periodo").on("change", function() {
        let activar = ($(this).val() === "rango");
        $("#f_desde, #f_hasta").prop("disabled", !activar);
    });

    $("#btnBuscar").on("click", function() {

        let filtros = {
            conductor: $("#f_conductor").val(),
            periodo: $("#f_periodo").val(),
            desde: $("#f_desde").val(),
            hasta: $("#f_hasta").val(),
            tipo: $("#f_tipo").val()
        };

        $("#tablaResultados").html("<p>Cargando...</p>");

        $.post('/modulos/asistencias/acciones/buscar_asistencias/buscar_asistencias.php', filtros, function(r) {

            if (!r.ok) {
                $("#tablaResultados").html("<p class='text-danger'>" + r.error + "</p>");
                return;
            }

            let html = `
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Tipo</th>
                            <th>Entrada</th>
                            <th>Salida</th>
                            <th>Obs</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            r.data.forEach(row => {
                html += `
                    <tr>
                        <td>${row.fecha}</td>
                        <td>${row.tipo}</td>
                        <td>${row.hora_entrada}</td>
                        <td>${row.hora_salida}</td>
                        <td>${row.observacion || ''}</td>
                        <td>
                            <button class="btn btn-warning btn-sm btnEditarAsistencia"
                                    data-id="${row.id}">
                                Editar
                            </button>
                        </td>
                    </tr>
                `;
            });

            html += "</tbody></table>";

            $("#tablaResultados").html(html);

        }, 'json');

    });

});
</script>

