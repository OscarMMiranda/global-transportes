<?php
// archivo: /modulos/infracciones/ajax/eliminar.php

require_once __DIR__ . '/../controllers/InfraccionesController.php';
require_once __DIR__ . '/../../../includes/config.php';

header('Content-Type: application/json');

$controller = new InfraccionesController($GLOBALS['db']);

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

if ($id <= 0) {
    echo json_encode(["ok" => false, "msg" => "ID inválido"]);
    exit;
}

// Validar si tiene papeletas asociadas
if ($controller->tienePapeletas($id)) {
    echo json_encode([
        "ok" => false,
        "msg" => "No se puede eliminar. La infracción está asociada a papeletas."
    ]);
    exit;
}

// Soft delete
$ok = $controller->desactivar($id);

echo json_encode([
    "ok" => $ok,
    "msg" => $ok ? "Infracción desactivada" : "No se pudo desactivar"
]);
exit;
