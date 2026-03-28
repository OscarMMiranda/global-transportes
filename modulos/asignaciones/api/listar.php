<?php
// archivo: modulos/asignaciones/api/listar.php


require_once __DIR__ . '/../../../includes/config.php';
require_once __DIR__ . '/../model/asignaciones.php';

$conn = getConnection();

$data = obtenerAsignaciones($conn);

echo json_encode($data);
