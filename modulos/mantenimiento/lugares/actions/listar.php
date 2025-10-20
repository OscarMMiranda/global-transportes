<?php
// listar.php — Lista los lugares asociados a una entidad

// 🔐 Validación básica
if (!isset($_GET['entidad_id']) || !is_numeric($_GET['entidad_id'])) {
    echo "<tr><td colspan='7' class='text-danger'>Entidad no válida.</td></tr>";
    return;
}

$entidad_id = intval($_GET['entidad_id']);

// 🔌 Conexión
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
$conn = getConnection();
if (!($conn instanceof mysqli)) {
    echo "<tr><td colspan='7' class='text-danger'>Error de conexión.</td></tr>";
    return;
}

// 📦 Consulta
$sql = "SELECT l.id, l.nombre, l.direccion, l.estado,
               t.nombre AS tipo,
               d.nombre AS distrito
        FROM lugares l
        LEFT JOIN tipo_lugares t ON l.tipo_id = t.id
        LEFT JOIN distritos d ON l.distrito_id = d.id
        WHERE l.entidad_id = $entidad_id
        ORDER BY l.fecha_creacion DESC";

$res = $conn->query($sql);
if (!$res) {
    echo "<tr><td colspan='7' class='text-danger'>Error SQL: " . htmlspecialchars($conn->error) . "</td></tr>";
    return;
}

if ($res->num_rows === 0) {
    echo "<tr><td colspan='7' class='text-muted'>No hay lugares registrados.</td></tr>";
    return;
}

// 🧩 Renderizado
while ($row = $res->fetch_assoc()) {
    $id        = intval($row['id']);
    $nombre    = htmlspecialchars($row['nombre']);
    $tipo      = htmlspecialchars($row['tipo']);
    $distrito  = htmlspecialchars($row['distrito']);
    $direccion = htmlspecialchars($row['direccion']);
    $estado    = htmlspecialchars($row['estado']);

    echo "<tr>";
    echo "<td>$nombre</td>";
    echo "<td>$tipo</td>";
    echo "<td>$distrito</td>";
    echo "<td>$direccion</td>";
    echo "<td>$estado</td>";

    // 🎛️ Acciones principales
    echo "<td class='text-center'>
            <button class='btn btn-xs btn-warning' onclick='abrirModalLugar($id)' title='Editar'>
              <i class='fa fa-pencil'></i>
            </button>
            <button class='btn btn-xs btn-danger' onclick='eliminarLugar($id)' title='Eliminar'>
              <i class='fa fa-trash'></i>
            </button>
          </td>";

    // 📜 Auditoría
    echo "<td class='text-center'>
            <a href='/modulos/mantenimiento/lugares/auditoria_lugar.php?id=$id' target='_blank' class='btn btn-xs btn-default' title='Ver auditoría'>
              <i class='fa fa-history'></i>
            </a>
          </td>";
    echo "</tr>";
}
?>