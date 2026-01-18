<?php
// archivo: /modulos/seguridad/auditoria/acciones/listar_modulos.php

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';

$db = getConnection();

if (!$db) {
    echo json_encode(array("ok" => false, "msg" => "No hay conexión a la base de datos"));
    exit;
}

$sql = "SELECT DISTINCT modulo FROM auditoria ORDER BY modulo ASC";
$res = $db->query($sql);

if (!$res) {
    echo json_encode(array("ok" => false, "msg" => $db->error));
    exit;
}

$modulos = array();

while ($row = $res->fetch_assoc()) {
    if (trim($row['modulo']) !== '') {
        $modulos[] = $row['modulo'];
    }
}

echo json_encode(array("ok" => true, "data" => $modulos));