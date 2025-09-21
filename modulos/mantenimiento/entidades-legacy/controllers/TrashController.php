<?php
// archivo  : /modulos/mantenimiento/entidades/controllers/TrashController.php

// 1) Cargar configuración y conexión
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
$conn = getConnection();

// 2) Validar conexión
if (!($conn instanceof mysqli)) {
    error_log("❌ Conexión fallida en TrashController");
    die("Error de conexión");
}

// 3) Obtener entidades inactivas
$entidades = [
    'activos' => [],
    'inactivos' => []
];

$sql = "SELECT e.id, e.nombre, e.ruc, e.direccion, d.nombre AS departamento
        FROM entidades e
        LEFT JOIN departamentos d ON e.departamento_id = d.id
        WHERE e.estado = 'inactivo'
        ORDER BY e.nombre";

$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $entidades['inactivos'][] = $row;
    }
} else {
    error_log("⚠️ No se encontraron entidades inactivas");
}

// 4) Cargar departamentos y tipos para el formulario
$departamentos = [];
$tipos = [];

$resDep = $conn->query("SELECT id, nombre FROM departamentos ORDER BY nombre");
if ($resDep) {
    while ($d = $resDep->fetch_assoc()) {
        $departamentos[] = $d;
    }
}

$resTipo = $conn->query("SELECT id, nombre FROM tipos_lugar ORDER BY nombre");
if ($resTipo) {
    while ($t = $resTipo->fetch_assoc()) {
        $tipos[] = $t;
    }
}

// 5) Cargar vista con solo entidades inactivas
require_once __DIR__ . '/../views/ListView.php';