<?php
// archivo: /modulos/orden_trabajo/controllers/CatalogosController.php

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/conexion.php';
$conn = getConnection();

// Siempre devolver JSON limpio
header("Content-Type: application/json; charset=UTF-8");

// ===============================
// 🔵 Validar parámetro
// ===============================
$tipo = isset($_GET['tipo']) ? trim($_GET['tipo']) : "";

if ($tipo === "") {
    echo json_encode([]);
    exit;
}

// ===============================
// 🔵 Cargar catálogo: EMPRESA
// ===============================
if ($tipo === "empresa") {

    $sql = "SELECT id, razon_social 
            FROM empresa 
            WHERE id > 0 
            ORDER BY razon_social ASC";

    $res = $conn->query($sql);

    if ($res) {
        echo json_encode($res->fetch_all(MYSQLI_ASSOC));
    } else {
        echo json_encode([]);
    }

    exit;
}

// ===============================
// 🔵 Cargar catálogo: TIPO DE ORDEN
// ===============================
if ($tipo === "tipo_ot") {

    $sql = "SELECT id, nombre 
            FROM tipo_ot 
            WHERE id > 0 
            ORDER BY nombre ASC";

    $res = $conn->query($sql);

    if ($res) {
        echo json_encode($res->fetch_all(MYSQLI_ASSOC));
    } else {
        echo json_encode([]);
    }

    exit;
}

// ===============================
// 🔵 Si no coincide con ningún catálogo
// ===============================
echo json_encode([]);
exit;
