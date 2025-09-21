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
            <th style="width:160px; color:#fff;">Acciones</th>
          </tr>';
    echo '</thead><tbody>';

    while ($row = $res->fetch_assoc()) {
        $ubicacion = trim($row['departamento']) . ' - ' . trim($row['provincia']) . ' - ' . trim($row['distrito']);
        echo '<tr>';
        echo '<td>' . $row['id'] . '</td>';
        echo '<td>' . htmlspecialchars($row['nombre']) . '</td>';
        echo '<td>' . htmlspecialchars($row['ruc']) . '</td>';
        echo '<td>' . htmlspecialchars($row['direccion']) . '</td>';
        echo '<td>' . htmlspecialchars($ubicacion) . '</td>';

        // Acciones según estado
        echo '<td style="text-align:center;">';
        echo '<button class="btn btn-info btn-xs" title="Ver entidad" onclick="verEntidad(' . $row['id'] . ')">
                <i class="fa fa-eye"></i>
              </button> ';

        if ($estado === 'activo') {
            echo '<a href="editar.php?id=' . $row['id'] . '" class="btn btn-warning btn-xs" title="Editar entidad">
                    <i class="fa fa-pencil"></i>
                  </a> ';
            echo '<a href="../actions/borrar.php?id=' . $row['id'] . '" class="btn btn-danger btn-xs" title="Marcar como inactivo" onclick="return confirm(\'¿Marcar como inactivo?\')">
                    <i class="fa fa-trash"></i>
                  </a>';
        } else {
            echo '<a href="../actions/restaurar.php?id=' . $row['id'] . '" class="btn btn-success btn-xs" title="Restaurar entidad" onclick="return confirm(\'¿Restaurar esta entidad?\')">
                    <i class="fa fa-recycle"></i>
                  </a> ';
            echo '<a href="../actions/eliminar.php?id=' . $row['id'] . '" class="btn btn-danger btn-xs" title="Eliminar permanentemente" onclick="return confirm(\'¿Eliminar permanentemente esta entidad?\')">
                    <i class="fa fa-times-circle"></i>
                  </a>';
        }

        echo '</td>';
        echo '</tr>';
    }

    echo '</tbody></table></div>';
}
?>