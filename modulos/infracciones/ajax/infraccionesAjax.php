<?php
// archivo: /modulos/infracciones/ajax/infraccionesAjax.php

if (session_status() === PHP_SESSION_NONE) session_start();
header('Content-Type: application/json');

// Validar sesión
if (!isset($_SESSION['usuario'])) {
    echo json_encode([
        'status' => 'error',
        'mensaje' => 'Sesión expirada'
    ]);
    exit;
}

// Configuración global
require_once __DIR__ . '/../../../includes/config.php';

// Controlador
require_once __DIR__ . '/../controllers/InfraccionesController.php';
$controller = new InfraccionesController($GLOBALS['db']);

// Acción solicitada
$accion = isset($_POST['accion']) ? $_POST['accion'] : '';

/* ============================================================
   RESPUESTAS ESTÁNDAR
   ============================================================ */
function respuesta_ok($data = [], $mensaje = 'OK') {
    echo json_encode([
        'status' => 'ok',
        'mensaje' => $mensaje,
        'data' => $data
    ]);
    exit;
}

function respuesta_error($mensaje = 'Error') {
    echo json_encode([
        'status' => 'error',
        'mensaje' => $mensaje
    ]);
    exit;
}

/* ============================================================
   SWITCH PRINCIPAL
   ============================================================ */
switch ($accion) {

    /* ============================================================
       VER (OBTENER POR ID)
       ============================================================ */
    case 'ver':
        if (!isset($_POST['id'])) respuesta_error('ID no recibido');
        $id = intval($_POST['id']);

        $data = $controller->obtener($id);
        if (!$data) respuesta_error('Infracción no encontrada');

        respuesta_ok($data);
        break;

    /* ============================================================
       GUARDAR (CREAR)
       ============================================================ */
    case 'guardar':
        $data = $_POST;

        // Validación de código único
        if ($controller->existeCodigo($data['codigo'])) {
            respuesta_error('El código ya existe');
        }

        $ok = $controller->guardar($data);
        if (!$ok) respuesta_error('No se pudo guardar');

        respuesta_ok([], 'Guardado correctamente');
        break;

    /* ============================================================
       ACTUALIZAR
       ============================================================ */
    case 'actualizar':
        $data = $_POST;

        if (!isset($data['id'])) respuesta_error('ID no recibido');

        // Validación de código único (excluyendo el ID actual)
        if ($controller->existeCodigo($data['codigo'], $data['id'])) {
            respuesta_error('El código ya existe');
        }

        $ok = $controller->actualizar($data);
        if (!$ok) respuesta_error('No se pudo actualizar');

        respuesta_ok([], 'Actualizado correctamente');
        break;

    /* ============================================================
       ELIMINAR (HARD DELETE)
       ============================================================ */
    case 'eliminar':
        if (!isset($_POST['id'])) respuesta_error('ID no recibido');
        $id = intval($_POST['id']);

        $ok = $controller->eliminar($id);
        if (!$ok) respuesta_error('No se pudo eliminar');

        respuesta_ok([], 'Eliminado correctamente');
        break;

    /* ============================================================
       DESACTIVAR (SOFT DELETE)
       ============================================================ */
    case 'desactivar':
        if (!isset($_POST['id'])) respuesta_error('ID no recibido');
        $id = intval($_POST['id']);

        // Validar si tiene papeletas asociadas
        if ($controller->tienePapeletas($id)) {
            respuesta_error('No se puede desactivar: tiene papeletas asociadas');
        }

        $ok = $controller->desactivar($id);
        if (!$ok) respuesta_error('No se pudo desactivar');

        respuesta_ok([], 'Desactivado correctamente');
        break;

    /* ============================================================
       ACCIÓN NO RECONOCIDA
       ============================================================ */
    default:
        respuesta_error('Acción no válida');
}
?>