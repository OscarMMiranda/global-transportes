<?php
/**
 * helpers.php
 *
 * Funciones reutilizables para Global Transportes.
 * Compatible con PHP 5.6.
 */

// Definición de constantes de acciones para el historial
define('ACCION_ACCESO_PANEL',   'Accedió al panel de administración');
define('ACCION_CREAR_USUARIO',  'Creó usuario');
define('ACCION_EDITAR_USUARIO', 'Modificó usuario');
define('ACCION_ELIMINAR_USUARIO','Eliminó usuario');
define('ACCION_EXPORTAR_CSV',    'Exportó datos en CSV');
define('ACCION_LOGIN',          'Inició sesión');
define('ACCION_LOGOUT',         'Cerró sesión');

/**
 * Escapa una cadena para salida segura en HTML.
 *
 * @param string $value
 * @return string
 */
function esc($value) {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

/**
 * Registra una acción de usuario en la tabla historial_bd.
 *
 * @param mysqli $conn      Conexión activa a la base de datos
 * @param string $usuario   Nombre del usuario que ejecuta la acción
 * @param string $accion    Descripción de la acción realizada
 * @return bool             True si se insertó correctamente, False en caso contrario
 */
function registrarActividad($conn, $usuario, $accion) {
    $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';

    $usuario = mysqli_real_escape_string($conn, $usuario);
    $accion  = mysqli_real_escape_string($conn, $accion);
    $ip      = mysqli_real_escape_string($conn, $ip);

    $sql = "INSERT INTO historial_bd (usuario, accion, ip_usuario) 
            VALUES ('$usuario', '$accion', '$ip')";
    return mysqli_query($conn, $sql);
}




/**
 * Valida sesión y rol requerido.
 *
 * @param string      $rolRequerido
 * @param mysqli|null $conn
 */
function requireRole($rolRequerido, $conn = null) {
    // Solo iniciar si no hay sesión activa
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (! isset($_SESSION['usuario']) || $_SESSION['rol_nombre'] !== $rolRequerido) {
        if ($conn) {
            $usr = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : 'anónimo';
            registrarActividad(
                $conn,
                $usr,
                "Intento de acceso sin permisos: requería rol '$rolRequerido'"
            );
        }
        header('Location: login.php');
        exit();
    }
}


/**
 * Maneja la exportación de CSV si se solicita vía GET['exportar'].
 * Registra la acción en el historial.
 *
 * @param mysqli $conn   Conexión activa a la base de datos
 */

 if (! function_exists('handleExportCSV')) {
 function handleExportCSV($conn) {
    if (isset($_GET['exportar']) && $_GET['exportar'] === 'csv') {
        if (isset($_SESSION['usuario'])) {
            registrarActividad($conn, $_SESSION['usuario'], ACCION_EXPORTAR_CSV);
        }
        // Aquí iría la lógica de exportación real (headers, fopen, etc.)
    }
}
 }