<?php
// archivo: /modulos/conductores/acciones/guardar.php

require_once __DIR__ . '/../../../includes/config.php';
require_once __DIR__ . '/../controllers/conductores_controller.php';

header('Content-Type: application/json; charset=utf-8');

// DEBUG
error_log("DEBUG FILES: " . print_r($_FILES, true));
error_log("DEBUG POST: " . print_r($_POST, true));

$conn = getConnection();
if (!$conn) {
    echo json_encode(array('success' => false, 'error' => '❌ Error de conexión'));
    exit;
}

// -------------------------------------------------------------
// CAPTURA DE DATOS
// -------------------------------------------------------------
$id        = isset($_POST['id']) ? intval($_POST['id']) : 0;
$nombres   = isset($_POST['nombres']) ? trim($_POST['nombres']) : '';
$apellidos = isset($_POST['apellidos']) ? trim($_POST['apellidos']) : '';
$dni       = isset($_POST['dni']) ? trim($_POST['dni']) : '';
$licencia  = isset($_POST['licencia_conducir']) ? trim($_POST['licencia_conducir']) : '';
$telefono  = isset($_POST['telefono']) ? trim($_POST['telefono']) : '';
$correo    = isset($_POST['correo']) ? trim($_POST['correo']) : '';
$direccion = isset($_POST['direccion']) ? trim($_POST['direccion']) : '';

$departamento_id = isset($_POST['departamento_id']) ? intval($_POST['departamento_id']) : 0;
$provincia_id    = isset($_POST['provincia_id']) ? intval($_POST['provincia_id']) : 0;
$distrito_id     = isset($_POST['distrito_id']) ? intval($_POST['distrito_id']) : 0;

$activo = isset($_POST['activo']) ? 1 : 0;

// FOTO ACTUAL
$fotoActualPost = isset($_POST['foto_actual']) ? trim($_POST['foto_actual']) : '';

$usuario = "admin"; // Ajustar según login real

// -------------------------------------------------------------
// OBTENER DATOS ANTERIORES
// -------------------------------------------------------------
$anterior = null;
if ($id > 0) {
    $anterior = obtenerConductorPorId($conn, $id);
}

// -------------------------------------------------------------
// MANEJO DE FOTO
// -------------------------------------------------------------
$fotoNueva        = null;
$rutaFotoAnterior = null;

// RUTAS
$basePath         = rtrim($_SERVER['DOCUMENT_ROOT'], '/\\') . "/uploads/conductores";
$carpetaPrincipal = $basePath . "/";
$carpetaHistorial = $basePath . "/historial/";

// Crear carpetas si no existen
if (!is_dir($carpetaPrincipal)) {
    mkdir($carpetaPrincipal, 0777, true);
}
if (!is_dir($carpetaHistorial)) {
    mkdir($carpetaHistorial, 0777, true);
}

// -------------------------------------------------------------
// SI SE SUBE UNA NUEVA FOTO
// -------------------------------------------------------------
if (
    isset($_FILES['foto']) &&
    $_FILES['foto']['error'] === UPLOAD_ERR_OK &&
    !empty($_FILES['foto']['name']) &&
    $_FILES['foto']['size'] > 0
)
 {

    $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
    if (!$ext) {
        $ext = "png";
    }

    $fotoNueva = "conductor_" . $dni . "_" . time() . "." . $ext;
    $destino   = $carpetaPrincipal . $fotoNueva;

    if (!move_uploaded_file($_FILES['foto']['tmp_name'], $destino)) {
        error_log("ERROR MOVE: No se pudo mover el archivo a: " . $destino);
        error_log("TMP: " . $_FILES['foto']['tmp_name']);
        error_log("PERMISOS DESTINO: " . (is_writable($carpetaPrincipal) ? "SI" : "NO"));
    }

    // Mover foto anterior a historial
    if ($anterior && !empty($anterior['foto']) && $anterior['foto'] !== "0") {


        $rutaAnterior = $carpetaPrincipal . $anterior['foto'];

        if (is_file($rutaAnterior) && is_writable($carpetaHistorial)) {
            $rutaFotoAnterior = $carpetaHistorial . "HIST_" . time() . "_" . $anterior['foto'];
            @rename($rutaAnterior, $rutaFotoAnterior);
        }
    }

// -------------------------------------------------------------
// SI NO SE SUBE FOTO NUEVA → CONSERVAR LA ANTERIOR
// -------------------------------------------------------------
} else {

    if (!empty($fotoActualPost) && $fotoActualPost !== "0") {
        $fotoNueva = basename($fotoActualPost);

    } elseif ($anterior && !empty($anterior['foto']) && $anterior['foto'] !== "0") {
        $fotoNueva = $anterior['foto'];

    } else {
        $fotoNueva = null;
    }
}

// -------------------------------------------------------------
// GUARDAR EN TABLA PRINCIPAL
// -------------------------------------------------------------
if ($id > 0) {

    $stmt = $conn->prepare("
        UPDATE conductores SET 
            nombres = ?, 
            apellidos = ?, 
            dni = ?, 
            licencia_conducir = ?, 
            telefono = ?, 
            correo = ?, 
            direccion = ?, 
            departamento_id = ?, 
            provincia_id = ?, 
            distrito_id = ?, 
            activo = ?, 
            foto = ?
        WHERE id = ?
    ");

    if (!$stmt) {
        echo json_encode(array('success' => false, 'error' => $conn->error));
        exit;
    }

    $stmt->bind_param(
        "sssssssiiiisi",
        $nombres,			// 1  - s
        $apellidos,		// 2  - s	
        $dni,					// 3  - s	
        $licencia,				// 4  - s
        $telefono,				// 5  - s
        $correo,				// 6  - s
        $direccion,				// 7  - s		
        $departamento_id,		// 8  - i
        $provincia_id,			// 9  - i
        $distrito_id,			// 10 - i
        $activo,				// 11 - i
        $fotoNueva,				// 12 - s
        $id						// 13 - i
    );

    $accion = "editar";

} else {

    $stmt = $conn->prepare("
        INSERT INTO conductores 
            (nombres, apellidos, dni, licencia_conducir, telefono, correo, direccion, 
             departamento_id, provincia_id, distrito_id, activo, foto)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    if (!$stmt) {
        echo json_encode(array('success' => false, 'error' => $conn->error));
        exit;
    }

    $stmt->bind_param(
        "sssssssiiiis",
        $nombres,			// 1  - s
        $apellidos,		// 2  - s
        $dni,					// 3  - s
        $licencia,				// 4  - s
        $telefono,				// 5  - s
        $correo,				// 6  - s
        $direccion,				// 7  - s
        $departamento_id,		// 8  - i
        $provincia_id,			// 9  - i
        $distrito_id,			// 10 - i
        $activo,				// 11 - i
        $fotoNueva				// 12 - s
    );

    	$accion = "crear";				
}

if (!$stmt->execute()) {
    error_log("ERROR SQL: " . $stmt->error);
    echo json_encode(array('success' => false, 'error' => $stmt->error));
    exit;
}

if ($id === 0) {
    $id = $stmt->insert_id;
}

$stmt->close();

// -------------------------------------------------------------
// REGISTRAR HISTORIAL
// -------------------------------------------------------------
$cambios = array();

if ($anterior) {

    $campos = array(
        'nombres',
        'apellidos',
        'dni',
        'licencia_conducir',
        'correo',
        'telefono',
        'direccion',
        'departamento_id',
        'provincia_id',
        'distrito_id',
        'activo',
        'foto'
    );

    foreach ($campos as $campo) {

        if ($campo === 'licencia_conducir') {
            $nuevoValor    = $licencia;
            $valorAnterior = $anterior['licencia_conducir'];

        } elseif ($campo === 'foto') {
            $nuevoValor    = $fotoNueva;
            $valorAnterior = $anterior['foto'];

        } else {
            $nuevoValor    = isset($$campo) ? $$campo : null;
            $valorAnterior = isset($anterior[$campo]) ? $anterior[$campo] : null;
        }

        if ($valorAnterior != $nuevoValor) {
            $cambios[$campo] = array($valorAnterior, $nuevoValor);
        }
    }
}

$jsonCambios = json_encode($cambios);

$stmtH = $conn->prepare("
    INSERT INTO conductores_historial 
        (id_registro, modulo, nombres, apellidos, dni, licencia_conducir, correo, telefono, direccion, activo, foto, ruta_foto_anterior, accion, cambios_json, usuario)
    VALUES (?, 'conductores', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");

if ($stmtH) {

    $stmtH->bind_param(
        "isssssssisssss",
        $id,			// 1  - i		
        $nombres,	// 2  - s
        $apellidos,			// 3  - s
        $dni,				// 4  - s
        $licencia,			// 5  - s
        $correo,			// 6  - s
        $telefono,			// 7  - s
        $direccion,			// 8  - s
        $activo,			// 9  - i	
        $fotoNueva,			// 10 - s
        $rutaFotoAnterior,	// 11 - s
        $accion,			// 12 - s
        $jsonCambios,		// 13 - s
        $usuario			// 14 - s
    );

    $stmtH->execute();
    $stmtH->close();
}

// -------------------------------------------------------------
// RESPUESTA FINAL
// -------------------------------------------------------------
echo json_encode(array(
    'success' => true,
    'data' => array(
        'id'   => $id,
        'foto' => $fotoNueva ? "/uploads/conductores/" . $fotoNueva : null
    )
));
