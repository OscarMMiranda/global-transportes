<?php
// ======================================================
//  ARCHIVO: /modulos/orden_trabajo/views/list.php
//  VISTA: list.php
//  RESPONSABILIDAD: Mostrar listado de órdenes de trabajo
// ======================================================

// $data viene desde ListController.php
// $data["activas"], $data["anuladas"], $data["eliminadas"]
// $data["semanas"], $data["semana_sel"]
?>

<div class="contenedor-ordenes">

    <!-- =============================== -->
    <!-- 🔵 Filtros corporativos -->
    <!-- =============================== -->
    <div class="filtros">
        <label for="filtroSemana">Semana:</label>

        <select id="filtroSemana" class="form-control" onchange="cambiarSemana()">
            <option value="">-- Todas --</option>

            <?php while ($row = mysqli_fetch_assoc($data["semanas"])) { 
                $sem = $row["semana"];
                $sel = ($sem == $data["semana_sel"]) ? "selected" : "";
            ?>
                <option value="<?php echo $sem; ?>" <?php echo $sel; ?>>
                    <?php echo $sem; ?>
                </option>
            <?php } ?>
        </select>
    </div>

    <!-- =============================== -->
    <!-- 🔵 Tabs corporativos -->
    <!-- =============================== -->
    <ul class="nav nav-tabs" id="tabsOrdenes">
        <li class="active">
            <a href="#tabActivas" data-toggle="tab">Activas</a>
        </li>
        <li>
            <a href="#tabAnuladas" data-toggle="tab">Anuladas</a>
        </li>
        <li>
            <a href="#tabEliminadas" data-toggle="tab">Eliminadas</a>
        </li>
    </ul>

    <!-- =============================== -->
    <!-- 🔵 Contenido de tabs -->
    <!-- =============================== -->
    <div class="tab-content">

        <!-- =============================== -->
        <!-- 🔵 TAB ACTIVAS -->
        <!-- =============================== -->
        <div class="tab-pane fade in active" id="tabActivas">
            <table class="tabla-orden">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Descripción</th>
                        <th>Semana</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody id="tbodyActivas">
                    <?php while ($row = mysqli_fetch_assoc($data["activas"])) { ?>
                        <tr>
                            <td><?php echo $row["codigo"]; ?></td>
                            <td><?php echo $row["descripcion"]; ?></td>
                            <td><?php echo $row["semana"]; ?></td>
                            <td><?php echo $row["fecha"]; ?></td>
                            <td>
                                <button onclick="editar(<?php echo $row['id']; ?>)">Editar</button>
                                <button onclick="anular(<?php echo $row['id']; ?>)">Anular</button>
                                <button onclick="eliminar(<?php echo $row['id']; ?>)">Eliminar</button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <!-- =============================== -->
        <!-- 🔵 TAB ANULADAS -->
        <!-- =============================== -->
        <div class="tab-pane fade" id="tabAnuladas">
            <table class="tabla-orden">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Descripción</th>
                        <th>Semana</th>
                        <th>Fecha</th>
                    </tr>
                </thead>

                <tbody id="tbodyAnuladas">
                    <?php while ($row = mysqli_fetch_assoc($data["anuladas"])) { ?>
                        <tr>
                            <td><?php echo $row["codigo"]; ?></td>
                            <td><?php echo $row["descripcion"]; ?></td>
                            <td><?php echo $row["semana"]; ?></td>
                            <td><?php echo $row["fecha"]; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <!-- =============================== -->
        <!-- 🔵 TAB ELIMINADAS -->
        <!-- =============================== -->
        <div class="tab-pane fade" id="tabEliminadas">
            <table class="tabla-orden">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Descripción</th>
                        <th>Semana</th>
                        <th>Fecha</th>
                    </tr>
                </thead>

                <tbody id="tbodyEliminadas">
                    <?php while ($row = mysqli_fetch_assoc($data["eliminadas"])) { ?>
                        <tr>
                            <td><?php echo $row["codigo"]; ?></td>
                            <td><?php echo $row["descripcion"]; ?></td>
                            <td><?php echo $row["semana"]; ?></td>
                            <td><?php echo $row["fecha"]; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

    </div> <!-- /tab-content -->

</div> <!-- /contenedor-ordenes -->

<script>
function cambiarSemana() {
    var semana = document.getElementById("filtroSemana").value;

    $.post(
        "controllers/ListController.php",
        { ajax: 1, semana: semana, estado: "ACTIVA" },
        function(resp) {
            renderTabla(resp.data, "#tbodyActivas");
        },
        "json"
    );

    $.post(
        "controllers/ListController.php",
        { ajax: 1, semana: semana, estado: "ANULADA" },
        function(resp) {
            renderTabla(resp.data, "#tbodyAnuladas");
        },
        "json"
    );

    $.post(
        "controllers/ListController.php",
        { ajax: 1, semana: semana, estado: "ELIMINADA" },
        function(resp) {
            renderTabla(resp.data, "#tbodyEliminadas");
        },
        "json"
    );
}

function renderTabla(data, target) {
    var html = "";

    for (var i in data) {
        html += "<tr>" +
            "<td>" + data[i].codigo + "</td>" +
            "<td>" + data[i].descripcion + "</td>" +
            "<td>" + data[i].semana + "</td>" +
            "<td>" + data[i].fecha + "</td>";

        if (target === "#tbodyActivas") {
            html += "<td>" +
                "<button onclick='editar(" + data[i].id + ")'>Editar</button>" +
                "<button onclick='anular(" + data[i].id + ")'>Anular</button>" +
                "<button onclick='eliminar(" + data[i].id + ")'>Eliminar</button>" +
                "</td>";
        }

        html += "</tr>";
    }

    $(target).html(html);
}
</script>
