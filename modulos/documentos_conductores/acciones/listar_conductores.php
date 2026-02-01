<?php
// archivo: /modulos/documentos_conductores/acciones/listar_conductores.php

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

// SOLO conductores activos
$sql = "
    SELECT 
        c.id,
        c.dni,
        CONCAT(c.nombres, ' ', c.apellidos) AS nombre
    FROM conductores c
    WHERE c.activo = 1
    ORDER BY c.apellidos ASC
";

$res = $conn->query($sql);

$conductores = [];

// Total documentos requeridos para conductor
$sqlReq = "
    SELECT COUNT(*) AS total
    FROM documentos_config
    WHERE entidad_tipo='conductor'
";
$totalReq = $conn->query($sqlReq)->fetch_assoc()['total'];

while ($c = $res->fetch_assoc()) {

    $id = intval($c['id']);

    // Documentos OK (fecha válida y no vencida)
    $sqlOk = "
        SELECT COUNT(*) AS ok
        FROM documentos
        WHERE entidad_tipo='conductor'
          AND entidad_id=$id
          AND eliminado=0
          AND is_current=1
          AND (
                fecha_vencimiento = '0000-00-00'
                OR fecha_vencimiento >= CURDATE()
              )
    ";
    $ok = $conn->query($sqlOk)->fetch_assoc()['ok'];

    // Por vencer (fecha dentro del rango de alerta)
    $sqlVencer = "
        SELECT COUNT(*) AS pv
        FROM documentos
        WHERE entidad_tipo='conductor'
          AND entidad_id=$id
          AND eliminado=0
          AND is_current=1
          AND fecha_vencimiento > CURDATE()
          AND fecha_vencimiento <= DATE_ADD(CURDATE(), INTERVAL alertar_dias_antes DAY)
    ";
    $pv = $conn->query($sqlVencer)->fetch_assoc()['pv'];

    // Estado final del conductor
    $estado = ($ok == $totalReq) ? "Habilitado" : "Inhabilitado";

    $conductores[] = [
        "id" => $id,
        "dni" => $c['dni'],
        "nombre" => $c['nombre'],
        "docs_total" => intval($totalReq),
        "docs_ok" => intval($ok),
        "docs_por_vencer" => intval($pv),
        "estado" => $estado
    ];
}

echo json_encode([
    "ok" => true,
    "conductores" => $conductores
]);
