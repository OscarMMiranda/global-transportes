<?php
//  archivo: modulos/vehiculos/acciones/ver_fotos.php

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
$conn = getConnection();

$id = intval($_POST['id']);

$sql = "
    SELECT 
        f.id_foto AS id,
        f.ruta_archivo AS ruta,
        f.descripcion,
        f.creado_en AS created_at,
        u.usuario AS usuario_nombre
    FROM vehiculo_fotos f
    LEFT JOIN usuarios u ON u.id = f.creado_por
    WHERE f.id_vehiculo = $id
    ORDER BY f.creado_en DESC
";

$res = $conn->query($sql);

if (!$res) {
    echo json_encode([
        "success" => false,
        "html" => "<div class='text-danger'>ERROR SQL: " . $conn->error . "</div>"
    ]);
    exit;
}

$rows = "";

while ($f = $res->fetch_assoc()) {

    // Normalizar ruta
    $img = $f['ruta'];
    if ($img && $img[0] !== '/') {
        $img = '/' . $img;
    }

    $usuario = $f['usuario_nombre'] ?: "—";
    $descEsc = htmlspecialchars($f['descripcion']);

    $rows .= "
        <tr>
            <td><img src='$img' style='width:70px; height:50px; object-fit:cover; border:1px solid #ccc;'></td>
            <td>$descEsc</td>
            <td>{$f['created_at']}</td>
            <td>{$usuario}</td>
            <td class='text-nowrap'>
                <button class='btn btn-sm btn-primary btn-ver-foto' data-img='$img'>Ver</button>
                <button class='btn btn-sm btn-outline-primary btn-editar-foto' data-id='{$f['id']}' data-desc='$descEsc'>Desc.</button>
                <button class='btn btn-sm btn-outline-danger btn-eliminar-foto' data-id='{$f['id']}'>Eliminar</button>
            </td>
        </tr>
    ";
}

$html = "
<div class='container-fluid'>

    <div class='d-flex justify-content-end mb-2'>
        <button class='btn btn-success btn-sm' id='btnAgregarFoto'>
            <i class='fa fa-upload'></i> Agregar foto
        </button>
    </div>

    <table class='table table-bordered table-striped table-sm'>

        <thead class='table-light'>
            <tr>
                <th>Foto</th>
                <th>Descripción</th>
                <th>Fecha</th>
                <th>Subido por</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            $rows
        </tbody>
    </table>
</div>
";

echo json_encode([
    "success" => true,
    "html" => $html
]);
