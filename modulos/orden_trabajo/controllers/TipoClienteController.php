<?php
// archivo: /modulos/orden_trabajo/controllers/TipoClienteController.php

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/conexion.php';
$conn = getConnection();

header('Content-Type: application/json');

$sql = "SELECT id, nombre FROM tipos_cliente WHERE estado = 'Activo' ORDER BY nombre";
$res = $conn->query($sql);

echo json_encode($res->fetch_all(MYSQLI_ASSOC));
