<?php
// archivo	:	/includes/funciones.php//
// Funciones comunes para todo el sistema
// --------------------------------------------------------------/
// INCLUYENDO ARCHIVOS NECESARIOS
// --------------------------------------------------------------   


require_once 'conexion.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Registrar acción en historial de base de datos.
 */
function registrarEnHistorial($conn, $usuario, $accion, $modulo, $ip) {
    if (!$conn || !$usuario || !$accion || !$modulo || !$ip) {
        error_log("❌ registrarEnHistorial: parámetros incompletos");
        return false;
    }

    $sql = "INSERT INTO historial_bd (usuario, accion, tabla_afectada, ip_usuario) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log("❌ Error al preparar historial: " . $conn->error);
        return false;
    }

    $stmt->bind_param("ssss", $usuario, $accion, $modulo, $ip);
    $ok = $stmt->execute();
    $stmt->close();
    return $ok;
}

/**
 * Obtener lista de vehículos activos/inactivos.
 */
function obtenerVehiculos($conn, $activo = 1) {
    $activo = (int) $activo;
    $sql = "
        SELECT 
            v.id, v.placa, v.modelo, v.anio, v.observaciones,
            m.nombre AS marca,
            t.nombre AS tipo,
            e.razon_social AS empresa,
            ev.nombre AS estado_operativo,
            v.activo
        FROM vehiculos v
        JOIN marca_vehiculo m ON v.marca_id = m.id
        JOIN tipo_vehiculo t ON v.tipo_id = t.id
        JOIN empresa e ON v.empresa_id = e.id
        JOIN estado_vehiculo ev ON v.estado_id = ev.id
        WHERE v.activo = $activo
        ORDER BY v.placa
    ";

    $result = $conn->query($sql);
    if (!$result) {
        error_log("❌ Error en obtenerVehiculos: " . $conn->error);
        return false;
    }
    return $result;
}

/**
 * Obtener detalles de un vehículo por ID.
 */
function obtenerVehiculoPorId($conn, $id) {
    $id = (int) $id;
    if ($id <= 0) return null;

    $sql = "SELECT id, placa, marca_id, modelo, tipo_id, anio, empresa_id, activo FROM vehiculos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return ($result->num_rows > 0) ? $result->fetch_assoc() : null;
}

/**
 * Registrar nuevo vehículo.
 */
function registrarVehiculo($conn, $placa, $marca_id, $modelo, $tipo_id, $anio, $empresa_id) {
    $sql = "INSERT INTO vehiculos (placa, marca_id, modelo, tipo_id, anio, empresa_id) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sisiii", $placa, $marca_id, $modelo, $tipo_id, $anio, $empresa_id);
    $ok = $stmt->execute();
    $stmt->close();

    if ($ok) {
        registrarEnHistorial($conn, $_SESSION['usuario'], "Registró vehículo: $placa", "vehiculos", obtenerIP());
    }
    return $ok;
}

/**
 * Editar vehículo existente.
 */
function editarVehiculo($conn, $id, $placa, $marca_id, $modelo, $tipo_id, $anio, $empresa_id) {
    $sql = "
        UPDATE vehiculos SET 
            placa = ?, marca_id = ?, modelo = ?, tipo_id = ?, anio = ?, empresa_id = ?
        WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sisiiii", $placa, $marca_id, $modelo, $tipo_id, $anio, $empresa_id, $id);
    $ok = $stmt->execute();
    $stmt->close();

    if ($ok) {
        registrarEnHistorial($conn, $_SESSION['usuario'], "Editó vehículo ID: $id", "vehiculos", obtenerIP());
    }
    return $ok;
}

/**
 * Desactivar vehículo (eliminación lógica).
 */
function eliminarVehiculo($conn, $id) {
    $sql = "UPDATE vehiculos SET activo = 0 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $ok = $stmt->execute();
    $stmt->close();

    if ($ok) {
        registrarEnHistorial($conn, $_SESSION['usuario'], "Desactivó vehículo ID: $id", "vehiculos", obtenerIP());
    }
    return $ok;
}

/**
 * Restaurar vehículo desactivado.
 */
function restaurarVehiculo($conn, $id) {
    $sql = "UPDATE vehiculos SET activo = 1 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $ok = $stmt->execute();
    $stmt->close();

    if ($ok) {
        registrarEnHistorial($conn, $_SESSION['usuario'], "Restauró vehículo ID: $id", "vehiculos", obtenerIP());
    }
    return $ok;
}

/**
 * Obtener nombre de marca por ID.
 */
function obtenerMarcaNombre($conn, $marca_id) {
    $sql = "SELECT nombre FROM marca_vehiculo WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $marca_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return ($row = $result->fetch_assoc()) ? $row['nombre'] : "Desconocido";
}

/**
 * Obtener nombre de tipo por ID.
 */
function obtenerTipoNombre($conn, $tipo_id) {
    $sql = "SELECT nombre FROM tipo_vehiculo WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $tipo_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return ($row = $result->fetch_assoc()) ? $row['nombre'] : "Desconocido";
}

/**
 * Obtener nombre de empresa por ID.
 */
function obtenerEmpresaNombre($conn, $empresa_id) {
    $sql = "SELECT razon_social FROM empresa WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $empresa_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return ($row = $result->fetch_assoc()) ? $row['razon_social'] : "Desconocida";
}

/**
 * Validar acceso de administrador.
 */
function verificarAdmin() {
    session_start();
    if (!isset($_SESSION['usuario']) || $_SESSION['rol_nombre'] !== 'admin') {
        error_log("❌ Acceso denegado desde IP: " . $_SERVER['REMOTE_ADDR']);
        header("Location: login.php");
        exit();
    }
}

/**
 * Obtener IP del cliente.
 */
function obtenerIP() {
    return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
}

