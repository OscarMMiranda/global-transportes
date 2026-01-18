<?php
// archivo: /modulos/seguridad/roles/acciones/listar_roles.php
require_once __DIR__ . '/../../../../includes/config.php';

$conn = getConnection();

$sql = "SELECT id, nombre FROM roles ORDER BY nombre ASC";
$res = $conn->query($sql);

$html = "<table class='table table-hover table-sm'>
            <thead>
                <tr>
                    <th>Rol</th>
                    <th style='width: 120px;'>Acciones</th>
                </tr>
            </thead>
            <tbody>";

while ($row = $res->fetch_assoc()) {

    $id = (int)$row['id'];
    $nombre = htmlspecialchars($row['nombre']);

    $html .= "
        <tr id='fila_rol_$id' class='fila-rol'>
            <td>$nombre</td>
            <td>
                <button class='btn btn-warning btn-sm btn-editar-rol'
                        data-id='$id'
                        data-nombre=\"$nombre\">
                    <i class='fa-solid fa-pen-to-square'></i>
                </button>

                <button class='btn btn-danger btn-sm btn-eliminar-rol'
                        data-id='$id'
                        data-nombre=\"$nombre\">
                    <i class='fa-solid fa-trash'></i>
                </button>
            </td>
        </tr>
    ";
}

$html .= "</tbody></table>";

echo json_encode([
    "ok" => true,
    "html" => $html
]);