<?php
    //	archivo	:	/modulos/mantenimiento/agencia_aduana/ajax/distritos.php
// 🔐 Blindaje extremo y trazabilidad
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
$conn = getConnection();

// 📦 Validar parámetro recibido por POST
$provincia_id = isset($_POST['provincia_id']) ? intval($_POST['provincia_id']) : 0;

if (!is_object($conn)) {
    error_log("❌ distritos.php: conexión inválida");
    echo '<option value="">❌ Error de conexión</option>';
    return;
}

if ($provincia_id <= 0) {
    error_log("⚠️ distritos.php: parámetro inválido recibido: " . json_encode($_POST));
    echo '<option value="">❌ Parámetro inválido</option>';
    return;
}

// 🧠 Consulta segura
$sql = "SELECT id, nombre FROM distritos WHERE provincia_id = $provincia_id ORDER BY nombre ASC";
$res = $conn->query($sql);

if (!$res) {
    error_log("❌ distritos.php: error en consulta SQL para provincia_id=$provincia_id");
    echo '<option value="">❌ Error al consultar distritos</option>';
    return;
}

if ($res->num_rows === 0) {
    error_log("ℹ️ distritos.php: sin resultados para provincia_id=$provincia_id");
    echo '<option value="">⚠️ Sin distritos disponibles</option>';
    return;
}

// ✅ Renderizar opciones
echo '<option value="">-- Seleccionar distrito --</option>';
while ($row = $res->fetch_assoc()) {
    $id = intval($row['id']);
    $nombre = htmlspecialchars($row['nombre']);
    echo '<option value="' . $id . '">' . $nombre . '</option>';
}
?>