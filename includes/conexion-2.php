<?php
/**
 * Devuelve una instancia mysqli única (singleton).
 *
 * @return mysqli
 * @throws Exception si falla la conexión
 */
function getConexion(): mysqli {
    static $conn = null;
    if ($conn === null) {
        // Parámetros de conexión
        $host = 'localhost';
        $user = 'wi010232_ommz';
        $pass = 'Samantha2304';
        $db   = 'wi010232_sistema';

        $conn = new mysqli($host, $user, $pass, $db);
        if ($conn->connect_errno) {
            // Registra el error y lanza excepción
            error_log("MySQL connect error ({$conn->connect_errno}): {$conn->connect_error}");
            throw new Exception("No fue posible conectar con la base de datos");
        }

        // Asegura UTF-8
        if (! $conn->set_charset("utf8mb4")) {
            error_log("Error charset utf8mb4: " . $conn->error);
        }
    }
    return $conn;
}

