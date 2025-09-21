<?php

ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');
error_reporting(E_ALL);
session_start();

require_once __DIR__ . '/../layout/header_vehiculos.php';

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
// require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../modelo.php';

$conn = getConnection();

require_once __DIR__ . '/../includes/funciones.php';
validarSesionAdmin();

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$vehiculo = getVehiculoPorId($conn, $id);

if (! $vehiculo || $vehiculo['activo'] == 0) {
    echo "<div class='alert alert-danger'>Vehículo no encontrado o ya eliminado.</div>";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Eliminar vehículo</title>
    <link rel="stylesheet" href="/assets/bootstrap.min.css">
</head>
<body class="container mt-5">
    <h3>¿Está seguro que desea eliminar el vehículo?</h3>
    <p><strong>Placa:</strong> <?= htmlspecialchars($vehiculo['placa']) ?></p>
    <p><strong>Modelo:</strong> <?= htmlspecialchars($vehiculo['modelo']) ?></p>
    <p><strong>Estado actual:</strong> <?= htmlspecialchars($vehiculo['estado_nombre']) ?></p>

	<form action="../controlador.php?action=delete" method="POST">
	    <input type="hidden" name="id" value="<?= $vehiculo['id'] ?>">
        <button type="submit" class="btn btn-danger">Sí, eliminar</button>
        <a href="../vehiculos.php" class="btn btn-secondary">Cancelar</a>
    </form>
</body>
</html>