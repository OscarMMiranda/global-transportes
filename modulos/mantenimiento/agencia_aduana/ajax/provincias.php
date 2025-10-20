<?php
    //  archivo :   /modulos/mantenimiento/agencia_aduana/ajax/provincias.php

// 🔐 Blindaje extremo y trazabilidad
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
$conn = getConnection();

// 📦 Validar parámetro recibido por POST
$departamento_id = isset($_POST['departamento_id']) ? intval($_POST['departamento_id']) : 0;

if (!is_object($conn)) {
    error_log("❌ provincias.php: conexión inválida");
    echo '<option value="">❌ Error de conexión</option>';
    return;
}

if ($departamento_id <= 0) {
    error_log("⚠️ provincias.php: parámetro inválido recibido: " . json_encode($_POST));
    echo '<option value="">❌ Parámetro inválido</option>';
    return;
}

// 🧠 Consulta segura
$sql = "SELECT id, nombre FROM provincias WHERE departamento_id = $departamento_id ORDER BY nombre ASC";
$res = $conn->query($sql);

if (!$res) {
    error_log("❌ provincias.php: error en consulta SQL para departamento_id=$departamento_id");
    echo '<option value="">❌ Error al consultar provincias</option>';
    return;
}

if ($res->num_rows === 0) {
    error_log("ℹ️ provincias.php: sin resultados para departamento_id=$departamento_id");
    echo '<option value="">⚠️ Sin provincias disponibles</option>';
    return;
}

// ✅ Renderizar opciones
echo '<option value="">-- Seleccionar provincia --</option>';
while ($row = $res->fetch_assoc()) {
    $id = intval($row['id']);
    $nombre = htmlspecialchars($row['nombre']);
    echo '<option value="' . $id . '">' . $nombre . '</option>';
}
?>