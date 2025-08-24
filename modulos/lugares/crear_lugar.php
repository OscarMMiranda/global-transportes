<?php
// 1) Modo depuración (solo DEV)
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors',     1);
ini_set('error_log',      __DIR__ . '/error_log.txt');

// 2) Cargar configuración y conexión

require_once __DIR__ . '/../../includes/config.php';

$conn = getConnection();


require_once '../../includes/header_lugares.php';

// Verificar acceso solo para administradores
if (!isset($_SESSION['usuario']) || !isset($_SESSION['rol_nombre']) || $_SESSION['rol_nombre'] !== 'admin') {
    die("❌ Acceso denegado.");
}

// Activar depuración de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');
?>

<div class="container mt-4">
    <h2 class="text-center text-primary mb-4">📍 Registrar un nuevo lugar</h2>

    <?php include 'form_lugares.php'; ?>
</div>

<?php 
// require_once '../../includes/footer_lugar.php'; 
?>
