<?php
// archivo: /modulos/seguridad/permisos/ver_modulo.php

require_once __DIR__ . '/../../../includes/config.php';
require_once INCLUDES_PATH . '/permisos.php';

requirePermiso("usuarios", "ver");

if (!isset($_GET['modulo_id'])) {
    die("Falta modulo_id");
}

$modulo_id = (int)$_GET['modulo_id'];

$conn = getConnection();

// Obtener nombre del módulo
$mod = $conn->query("SELECT nombre FROM modulos WHERE id = $modulo_id")->fetch_assoc();
if (!$mod) die("Módulo no encontrado");

// Obtener roles y acciones
$sql = "
    SELECT r.nombre AS rol, a.nombre AS accion
    FROM permisos_roles pr
    JOIN roles r ON r.id = pr.rol_id
    JOIN acciones a ON a.id = pr.accion_id
    WHERE pr.modulo_id = $modulo_id
    ORDER BY r.nombre, a.nombre
";
$res = $conn->query($sql);

$roles = [];
while ($row = $res->fetch_assoc()) {
    $roles[$row['rol']][] = $row['accion'];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Roles con acceso al módulo</title>

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
    <i class="fa-solid fa-box"></i>
    Roles con acceso al módulo: <?= $mod['nombre'] ?>
</h2>

<?php foreach ($roles as $rol => $acciones): ?>
    <div class="card mb-3 p-3 shadow-sm">
        <h4><i class="fa-solid fa-user-shield"></i> <?= $rol ?></h4>
        <ul class="mt-2">
            <?php foreach ($acciones as $a): ?>
                <li><i class="fa-solid fa-check text-success"></i> <?= $a ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endforeach; ?>

</body>
</html>