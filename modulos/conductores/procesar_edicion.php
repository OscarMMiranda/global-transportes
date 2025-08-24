<?php
// 1) Modo depuración (solo DEV)
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors',     1);
ini_set('error_log',      __DIR__ . '/error_log.txt');

// 2) Cargar configuración y conexión
require_once __DIR__ . '/../../includes/config.php';
$conn = getConnection();



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recuperar datos
    $id = intval($_POST['id']);
    $nombres = strtoupper(trim($_POST['nombres']));
    $apellidos = strtoupper(trim($_POST['apellidos']));
    $dni = trim($_POST['dni']);
    $licencia = strtoupper(trim($_POST['licencia_conducir']));
    $telefono = trim($_POST['telefono']);
    $correo = trim($_POST['correo']);
    $activo = isset($_POST['activo']) ? 1 : 0;
    $foto_ruta = '';

    // Validar que los campos requeridos no estén vacíos
    if (empty($nombres) || empty($apellidos) || empty($dni) || empty($licencia)) {
        die("❌ Error: Todos los campos requeridos deben estar llenos.");
    }

    // Validar el DNI y correo electrónico
    if (!preg_match('/^\d{8}$/', $dni)) {
        die("❌ Error: El DNI debe contener exactamente 8 dígitos numéricos.");
    }
    if (!empty($correo) && !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        die("❌ Error: El correo electrónico no es válido.");
    }

    // Procesar la imagen si se sube una nueva
    if (!empty($_FILES['foto']['name'])) {
        $directorio = "../../uploads/conductores/";
        $nombre_archivo = "conductor_" . $dni . "_" . time() . "." . pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $foto_ruta = $directorio . $nombre_archivo;

        move_uploaded_file($_FILES['foto']['tmp_name'], $foto_ruta);

        // Actualizar la foto en la base de datos
        $sql_update = "UPDATE conductores SET nombres=?, apellidos=?, dni=?, licencia_conducir=?, telefono=?, correo=?, foto=?, activo=? WHERE id=?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("sssssssii", $nombres, $apellidos, $dni, $licencia, $telefono, $correo, $foto_ruta, $activo, $id);
    } else {
        // Si no hay foto nueva, solo actualizar los demás datos
        $sql_update = "UPDATE conductores SET nombres=?, apellidos=?, dni=?, licencia_conducir=?, telefono=?, correo=?, activo=? WHERE id=?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("ssssssii", $nombres, $apellidos, $dni, $licencia, $telefono, $correo, $activo, $id);
    }

    // Ejecutar la consulta
    if ($stmt_update->execute()) {
        header("Location: conductores.php?mensaje=Conductor actualizado exitosamente");
        exit();
    } else {
        die("❌ Error al actualizar el conductor: " . $stmt_update->error);
    }

    $stmt_update->close();
}
?>
