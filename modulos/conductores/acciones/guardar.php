<?php
// archivo: /modulos/conductores/acciones/guardar.php
require_once __DIR__ . '/../../../includes/config.php';

header('Content-Type: application/json; charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = getConnection();
if (!$conn) {
    echo json_encode(['success' => false, 'error' => 'Conexión fallida']);
    exit;
}

// Captura y sanitización
$id                = isset($_POST['id']) ? intval($_POST['id']) : 0;
$nombres           = isset($_POST['nombres']) ? trim($_POST['nombres']) : '';
$apellidos         = isset($_POST['apellidos']) ? trim($_POST['apellidos']) : '';
$dni               = isset($_POST['dni']) ? trim($_POST['dni']) : '';
$licencia_conducir = isset($_POST['licencia_conducir']) ? trim($_POST['licencia_conducir']) : '';
$correo            = isset($_POST['correo']) ? trim($_POST['correo']) : '';
$telefono          = isset($_POST['telefono']) ? trim($_POST['telefono']) : '';
$direccion         = isset($_POST['direccion']) ? trim($_POST['direccion']) : '';
$activo            = isset($_POST['activo']) ? 1 : 0;

// Manejo de foto
$foto = '';
if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $nombreArchivo = time() . '_' . basename($_FILES['foto']['name']);
    $destino = $_SERVER['DOCUMENT_ROOT'] . '/uploads/conductores/' . $nombreArchivo;
    if (move_uploaded_file($_FILES['foto']['tmp_name'], $destino)) {
        $foto = '/uploads/conductores/' . $nombreArchivo;
    }
}

// Validaciones mínimas
if ($nombres === '' || $apellidos === '' || $dni === '' || $licencia_conducir === '') {
    echo json_encode(['success' => false, 'error' => 'Faltan campos obligatorios']);
    exit;
}

// Validar duplicado de DNI
$stmt = $conn->prepare("SELECT id FROM conductores WHERE dni = ? AND id != ?");
if (!$stmt) {
    echo json_encode(['success' => false, 'error' => 'Error preparando consulta de duplicado: ' . $conn->error]);
    exit;
}
$stmt->bind_param("si", $dni, $id);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    echo json_encode(['success' => false, 'error' => "Ya existe un conductor con el DNI $dni"]);
    $stmt->close();
    exit;
}
$stmt->close();

// Alta o edición
if ($id > 0) {
    // ============================
    // Guardar historial antes de editar
    // ============================
    $hist = $conn->prepare("INSERT INTO conductores_historial 
        (id_conductor, nombres, apellidos, dni, licencia_conducir, correo, telefono, direccion, activo, foto, fecha_cambio) 
        SELECT id, nombres, apellidos, dni, licencia_conducir, correo, telefono, direccion, activo, foto, NOW()
        FROM conductores WHERE id = ?");
    if ($hist) {
        $hist->bind_param("i", $id);
        $hist->execute();
        $hist->close();
    }

    // ============================
    // EDICIÓN
    // ============================
    if ($foto === '') {
        // Conservar foto existente
        $stmt = $conn->prepare("
            UPDATE conductores SET 
                nombres = ?, apellidos = ?, dni = ?, licencia_conducir = ?, 
                correo = ?, telefono = ?, direccion = ?, activo = ?
            WHERE id = ?
        ");
        if (!$stmt) {
            echo json_encode(['success' => false, 'error' => 'Error preparando UPDATE: ' . $conn->error]);
            exit;
        }
        $stmt->bind_param(
            "ssssssssi",
            $nombres, $apellidos, $dni, $licencia_conducir,
            $correo, $telefono, $direccion, $activo, $id
        );
    } else {
        // Actualizar también la foto
        $stmt = $conn->prepare("
            UPDATE conductores SET 
                nombres = ?, apellidos = ?, dni = ?, licencia_conducir = ?, 
                correo = ?, telefono = ?, direccion = ?, activo = ?, foto = ?
            WHERE id = ?
        ");
        if (!$stmt) {
            echo json_encode(['success' => false, 'error' => 'Error preparando UPDATE: ' . $conn->error]);
            exit;
        }
        $stmt->bind_param(
            "sssssssisi",
            $nombres, $apellidos, $dni, $licencia_conducir,
            $correo, $telefono, $direccion, $activo, $foto, $id
        );
    }
} else {
    // ============================
    // ALTA
    // ============================
    $stmt = $conn->prepare("
        INSERT INTO conductores (
            nombres, apellidos, dni, licencia_conducir, correo, telefono, direccion, activo, foto
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    if (!$stmt) {
        echo json_encode(['success' => false, 'error' => 'Error preparando INSERT: ' . $conn->error]);
        exit;
    }
    $stmt->bind_param(
        "sssssssis",
        $nombres, $apellidos, $dni, $licencia_conducir,
        $correo, $telefono, $direccion, $activo, $foto
    );
}

// Ejecución
if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $stmt->error]);
}
$stmt->close();