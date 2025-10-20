<?php
// archivo: EntidadesHelpers.php — helper trazable con ubicación jerárquica y acciones por estado

function renderListadoEntidades($conn, $estado) {
    if (!is_object($conn) || get_class($conn) !== 'mysqli') {
        echo "<div class='alert alert-danger'><i class='fa fa-exclamation-triangle'></i> Conexión no disponible.</div>";
        return;
    }

    if (!in_array($estado, ['activo', 'inactivo'])) {
        echo "<div class='alert alert-danger'><i class='fa fa-ban'></i> Estado inválido: <strong>" . htmlspecialchars($estado) . "</strong></div>";
        return;
    }

    $estadoSQL = $conn->real_escape_string($estado);

    // JOIN jerárquico: entidades → distritos → provincias → departamentos
    $sql = "SELECT e.id, e.nombre, e.ruc, e.direccion,
                   dpto.nombre AS departamento,
                   prov.nombre AS provincia,
                   dist.nombre AS distrito
            FROM entidades e
            LEFT JOIN distritos dist ON e.distrito_id = dist.id
            LEFT JOIN provincias prov ON dist.provincia_id = prov.id
            LEFT JOIN departamentos dpto ON prov.departamento_id = dpto.id
            WHERE e.estado = '$estadoSQL'
            ORDER BY e.fecha_creacion DESC";

    $res = $conn->query($sql);

    if (!$res) {
        echo "<div class='alert alert-danger'><strong>Error SQL:</strong> " . htmlspecialchars($conn->error) . "</div>";
        echo "<pre>Consulta ejecutada:\n" . $sql . "</pre>";
        return;
    }

    if ($res->num_rows === 0) {
        echo "<div class='alert alert-info'><i class='fa fa-info-circle'></i> No hay entidades " . htmlspecialchars($estado) . " registradas.</div>";
        return;
    }

    echo '<div class="table-responsive">';
    echo '<table class="table table-bordered table-hover table-striped">';
    echo '<thead style="background-color: #337ab7;">';
    echo '<tr>
            <th style="width:60px; color:#fff;">ID</th>
            <th style="color:#fff;">Nombre</th>
            <th style="width:120px; color:#fff;">RUC</th>
            <th style="color:#fff;">Dirección</th>
            <th style="color:#fff;">Ubicación</th>
            <th style="width:200px; color:#fff;">Acciones</th>
          </tr>';
    echo '</thead><tbody>';

    while ($row = $res->fetch_assoc()) {
        $id = intval($row['id']);
        $nombre = htmlspecialchars($row['nombre']);
        $ruc = htmlspecialchars($row['ruc']);
        $direccion = htmlspecialchars($row['direccion']);
        $ubicacion = htmlspecialchars(trim($row['departamento']) . ' - ' . trim($row['provincia']) . ' - ' . trim($row['distrito']));

        echo '<tr>';
        echo "<td>$id</td>";
        echo "<td>$nombre</td>";
        echo "<td>$ruc</td>";
        echo "<td>$direccion</td>";
        echo "<td>$ubicacion</td>";

        echo '<td style="text-align:center;">';

        // 🔹 Botón Ver ficha completa (vista_entidad.php)
        echo '<a href="/modulos/mantenimiento/entidades/index.php?action=view&entidad_id=' . $id . '" class="btn btn-info btn-xs" title="Ver ficha completa">
                <i class="fa fa-folder-open"></i>
              </a> ';

        // 🔹 Botón Ver (modal AJAX)
        echo '<button class="btn btn-default btn-ver-entidad btn-xs" data-id="' . $id . '" title="Ver resumen">
                <i class="fa fa-eye"></i>
              </button> ';

        if ($estado === 'activo') {
            // 🔹 Botón Editar (modal AJAX)
            echo '<button class="btn btn-warning btn-editar-entidad btn-xs" data-id="' . $id . '" title="Editar entidad">
                    <i class="fa fa-pencil"></i>
                  </button> ';

            // 🔹 Botón Marcar como inactivo
            echo '<a href="/modulos/mantenimiento/entidades/actions/borrar.php?id=' . $id . '" class="btn btn-danger btn-xs" title="Marcar como inactivo" onclick="return confirm(\'¿Marcar como inactivo?\')">
                    <i class="fa fa-trash"></i>
                  </a>';
        } else {
            // 🔹 Botón Restaurar
            echo '<a href="/modulos/mantenimiento/entidades/actions/restaurar.php?id=' . $id . '" class="btn btn-success btn-xs" title="Restaurar entidad" onclick="return confirm(\'¿Restaurar esta entidad?\')">
                    <i class="fa fa-recycle"></i>
                  </a> ';

            // 🔹 Botón Eliminar permanentemente
            echo '<a href="/modulos/mantenimiento/entidades/actions/eliminar.php?id=' . $id . '" class="btn btn-danger btn-xs" title="Eliminar permanentemente" onclick="return confirm(\'¿Eliminar permanentemente esta entidad?\')">
                    <i class="fa fa-times-circle"></i>
                  </a>';
        }

        echo '</td>';
        echo '</tr>';
    }

    echo '</tbody></table></div>';
}
?>