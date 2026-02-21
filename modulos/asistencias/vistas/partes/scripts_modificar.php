<!-- archivo: modulos/asistencias/vistas/partes/scripts_modificar.php -->


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<link rel="stylesheet" href="/modulos/asistencias/assets/css/toasts.css">

<script src="/modulos/asistencias/js/modificar_asistencia.js"></script>

<script src="/modulos/asistencias/js/modificar_asistencia.js"></script>
<script src="/modulos/asistencias/js/guardar_asistencia.js"></script>

<script>
$(document).ready(function() {

    // ============================================================
    // 1. CARGAR TIPOS DE ASISTENCIA DESDE LA BD
    // ============================================================
    $.get("/modulos/asistencias/ajax/get_tipos.php", function(r) {

        $("#f_tipo").html('<option value="">Todos</option>');

        r.forEach(t => {
            $("#f_tipo").append(
                `<option value="${t.codigo}">${t.descripcion}</option>`
            );
        });

    }, "json");


    // ============================================================
    // 2. BLOQUEAR CONDUCTOR HASTA QUE SE ELIJA EMPRESA
    // ============================================================
    $("#f_conductor").prop("disabled", true);


    // ============================================================
    // 3. CUANDO CAMBIA EMPRESA → CARGAR CONDUCTORES
    // ============================================================
    $("#f_empresa").on("change", function() {

        let empresa_id = $(this).val();

        if (empresa_id === "") {
            $("#f_conductor").html('<option value="">Seleccione empresa primero...</option>');
            $("#f_conductor").prop("disabled", true);
            return;
        }

        $("#f_conductor").prop("disabled", false);
        $("#f_conductor").html('<option value="">Cargando...</option>');

        $.get("/modulos/asistencias/ajax/get_conductores.php",
            { empresa_id: empresa_id },
            function(r) {

                $("#f_conductor").html('<option value="">Seleccione...</option>');

                r.forEach(item => {
                    $("#f_conductor").append(
                        `<option value="${item.id}">${item.nombre}</option>`
                    );
                });

            },
            "json"
        );
    });


    // ============================================================
    // 4. ACTIVAR / DESACTIVAR RANGO DE FECHAS
    // ============================================================
    $("#f_periodo").on("change", function() {
        let activar = ($(this).val() === "rango");
        $("#f_desde, #f_hasta").prop("disabled", !activar);
    });


    // ============================================================
    // 5. BOTÓN BUSCAR
    // ============================================================
    $("#btnBuscar").on("click", function() {

        let empresa = $("#f_empresa").val();
        let conductor = $("#f_conductor").val();

        // Validaciones profesionales
        if (empresa === "") {
            alert("Debe seleccionar una empresa");
            return;
        }

        if (conductor === "") {
            alert("Debe seleccionar un conductor");
            return;
        }

        let filtros = {
            conductor: conductor,
            periodo: $("#f_periodo").val(),
            desde: $("#f_desde").val(),
            hasta: $("#f_hasta").val(),
            tipo: $("#f_tipo").val()
        };

        $("#tablaResultados").html("<p>Cargando...</p>");

        $.post('/modulos/asistencias/acciones/buscar_asistencias/buscar_asistencias.php',
            filtros,
            function(r) {

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
                                <button 
                                    class="btn btn-warning btn-sm btnEditarAsistencia"
                                    data-id="${row.id}">
                                    Editar
                                </button>
                               <button 
    								class="btn btn-danger btn-sm btnEliminarAsistencia" 
    								data-id="${row.id}">
    								Eliminar
                                </button>
								<button 
    								class="btn btn-info btn-sm btnHistorialAsistencia" 
    								data-id="${row.id}">
    								Historial
								</button>

                            </td>
                        </tr>
                    `;
                });

                html += "</tbody></table>";

                $("#tablaResultados").html(html);

            },
            'json'
        );
    });

});


</script>

