<?php
// archivo: /modulos/usuarios/acciones/roles.php
// ------------------------------------------------------
// Devuelve lista de roles en JSON para el modal EDITAR
// ------------------------------------------------------

header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/modulos/usuarios/controllers/usuarios_controller.php';

$conn = getConnection();
if (!$conn) {
    echo json_encode([]);
    exit;
}

$roles = obtenerRoles($conn);

echo json_encode($roles);