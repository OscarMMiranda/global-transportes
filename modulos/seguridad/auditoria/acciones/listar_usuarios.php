<?php
// archivo: /modulos/seguridad/auditoria/acciones/listar_usuarios.php

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';

$db = getConnection();

if (!$db) {
    echo json_encode(array("ok" => false, "msg" => "No hay conexión a la base de datos"));
    exit;
}

$sql = "SELECT id, nombre FROM usuarios ORDER BY nombre ASC";
$res = $db->query($sql);

if (!$res) {
    echo json_encode(array("ok" => false, "msg" => $db->error));
    exit;
}

$usuarios = array();

while ($row = $res->fetch_assoc()) {
    $usuarios[] = array(
        "id" => $row["id"],
        "nombre" => $row["nombre"]
    );
}

echo json_encode(array("ok" => true, "data" => $usuarios));