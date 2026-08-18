<?php
// archivo: /modulos/papeletas/index.php

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
$conn = getConnection();

// Configuración del módulo
$tituloPagina = "Papeletas";
$subtituloPagina = "Gestión completa de papeletas";

$css = [
    "/modulos/papeletas/css/papeletas.css"
];

// Carga automática de todos los JS del módulo
$js = [];
$jsDir = __DIR__ . "/js";
$jsFiles = scandir($jsDir);

foreach ($jsFiles as $file) {
    if (substr($file, -3) === ".js") {
        $js[] = "/modulos/papeletas/js/" . $file;
    }
}

$botonPrincipal = '
<button class="btn btn-primary" id="btnNuevaPapeleta">
    <i class="fa fa-plus"></i> Nueva Papeleta
</button>';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php include __DIR__ . '/componentes/head.php'; ?>
</head>

<body>

<?php include __DIR__ . '/componentes/header.php'; ?>

<div class="container-fluid">

    <?php include __DIR__ . '/componentes/filtros.php'; ?>

    <?php include __DIR__ . '/componentes/tabla.php'; ?>

</div>

<?php include __DIR__ . '/componentes/modales.php'; ?>

<?php include __DIR__ . '/componentes/footer.php'; ?>
<?php include __DIR__ . '/componentes/scripts.php'; ?>

</body>
</html>
