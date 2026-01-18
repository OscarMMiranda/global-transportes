<?php
// archivo: /modulos/seguridad/permisos/ver_rol.php

require_once __DIR__ . '/../../../includes/config.php';
require_once INCLUDES_PATH . '/permisos.php';

requirePermiso("usuarios", "ver");

if (!isset($_GET['rol_id'])) {
    die("Falta rol_id");
}

$rol_id = (int)$_GET['rol_id'];

$conn = getConnection();

// Obtener nombre del rol
$rol = $conn->query("SELECT nombre FROM roles WHERE id = $rol_id")->fetch_assoc();
if (!$rol) die("Rol no encontrado");

// Obtener permisos
$sql = "
    SELECT m.nombre AS modulo, a.nombre AS accion
    FROM permisos_roles pr
    JOIN modulos m ON m.id = pr.modulo_id
    JOIN acciones a ON a.id = pr.accion_id
    WHERE pr.rol_id = $rol_id
    ORDER BY m.nombre, a.nombre
";
$res = $conn->query($sql);

$permisos = [];
while ($row = $res->fetch_assoc()) {
    $permisos[$row['modulo']][] = $row['accion'];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Permisos del Rol</title>

    <!-- Bootstrap + FontAwesome -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- CSS del módulo -->
    <link rel="stylesheet" href="css/permisos.css">
</head>
<body class="bg-light p-3">

<?php include __DIR__ . '/componentes/topbar.php'; ?>
<?php include __DIR__ . '/componentes/breadcrumb.php'; ?>
<?php include __DIR__ . '/componentes/menu.php'; ?>

<h2 class="mb-4">
    <i class="fa-solid fa-user-shield"></i>
    Permisos del Rol: <?= $rol['nombre'] ?>
</h2>

<?php foreach ($permisos as $modulo => $acciones): ?>
    <div class="card mb-3 p-3 shadow-sm">
        <h4><i class="fa-solid fa-box"></i> <?= $modulo ?></h4>
        <ul class="mt-2">
            <?php foreach ($acciones as $a): ?>
                <li><i class="fa-solid fa-check text-success"></i> <?= $a ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endforeach; ?>

</body>
</html>