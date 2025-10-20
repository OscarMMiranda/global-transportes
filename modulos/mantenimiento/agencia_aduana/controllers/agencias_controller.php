<?php
// archivo: /modulos/mantenimiento/agencia_aduana/controllers/agencias_controller.php

require_once __DIR__ . '/../../../../includes/conexion.php';
require_once __DIR__ . '/../../../../includes/funciones.php';
require_once __DIR__ . '/../../../../includes/ubicacion_modelo.php';

require_once __DIR__ . '/../modelo/validaciones_agencia.php';
require_once __DIR__ . '/../modelo/agencia_estado.php';
require_once __DIR__ . '/../modelo/agencia_insercion.php';

if (!isset($_SESSION)) {
    session_start();
}

// 🔒 Ejecutar acciones solo si vienen por GET
if (isset($_GET['accion']) && isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $accion = $_GET['accion'];

    if ($accion === 'eliminar') {
        eliminarAgencia($id);
    } elseif ($accion === 'reactivar') {
        reactivarAgencia($id);
    }
}

/**
 * Plantilla vacía de agencia.
 */
function agenciaVacia() {
    return array(
        'id'              => 0,
        'nombre'          => '',
        'ruc'             => '',
        'direccion'       => '',
        'departamento_id' => 0,
        'provincia_id'    => 0,
        'distrito_id'     => 0,
        'telefono'        => '',
        'correo_general'  => '',
        'contacto'        => ''
    );
}

/**
 * Listar todas las agencias con datos de Ubigeo.
 */
function listarAgencias() {
    global $conn;
    if (!$conn) return [];

    $sql = "
        SELECT 
            a.id, a.nombre, a.ruc, a.direccion,
            d.nombre AS departamento_nombre,
            p.nombre AS provincia_nombre,
            di.nombre AS distrito_nombre,
            a.estado, a.fecha_creacion
        FROM agencias_aduanas a
        LEFT JOIN departamentos d ON a.departamento_id = d.id
        LEFT JOIN provincias p ON a.provincia_id = p.id
        LEFT JOIN distritos di ON a.distrito_id = di.id
        ORDER BY a.nombre
    ";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $res = [];
    while ($row = $resultado->fetch_assoc()) {
        $res[] = $row;
    }
    $stmt->close();
    return $res;
}

/**
 * Obtener un registro por ID.
 */
function obtenerRegistro($id) {
    global $conn;
    $id = (int)$id;
    if ($id <= 0 || !$conn) return agenciaVacia();

    $stmt = $conn->prepare(
        "SELECT 
      	a.*, 
      	d.nombre AS distrito_nombre, 
      	p.nombre AS provincia_nombre, 
      	dep.nombre AS departamento_nombre
    FROM agencias_aduanas a
    LEFT JOIN distritos d ON a.distrito_id = d.id
    LEFT JOIN provincias p ON a.provincia_id = p.id
    LEFT JOIN departamentos dep ON a.departamento_id = dep.id
    WHERE a.id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $fila = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    return is_array($fila) ? array_merge(agenciaVacia(), $fila) : agenciaVacia();
}

/**
 * Procesar alta, edición o reactivación.
 */
function procesarFormulario($post) {
    global $conn;
    if (!$conn) return '❌ Conexión no disponible.';

    // Sanitizar y castear
    $id              = isset($post['id'])              ? (int) $post['id']              : 0;
    $nombre          = isset($post['nombre'])          ? trim($post['nombre'])          : '';
    $ruc             = isset($post['ruc'])             ? trim($post['ruc'])             : '';
    $direccion       = isset($post['direccion'])       ? trim($post['direccion'])       : '';
    $departamento_id = isset($post['departamento_id']) ? (int) $post['departamento_id'] : 0;
    $provincia_id    = isset($post['provincia_id'])    ? (int) $post['provincia_id']    : 0;
    $distrito_id     = isset($post['distrito_id'])     ? (int) $post['distrito_id']     : 0;
    $telefono        = isset($post['telefono'])        ? trim($post['telefono'])        : '';
    $correo          = isset($post['correo_general'])  ? trim($post['correo_general'])  : '';
    $contacto        = isset($post['contacto'])        ? trim($post['contacto'])        : '';

	 // 🛡️ Blindaje de dirección
    error_log("📦 Dirección recibida en controlador: '" . $direccion . "'");
    if ($direccion === '0' || $direccion === '') {
        error_log("⚠️ Dirección inválida. Se reemplaza por '[SIN DIRECCIÓN]'");
        $direccion = '[SIN DIRECCIÓN]';
    }

    // Validaciones
    $err = validarCamposObligatoriosAgencia($post);
    if ($err !== '') return $err;

    if ($correo && !validarCorreoAgencia($correo)) {
        return '❌ El correo no tiene un formato válido.';
    }

   	if (!validarDistritoProvinciaAgencia($distrito_id, $provincia_id)) {
    	error_log("⚠️ Distrito $distrito_id no pertenece a provincia $provincia_id. Registro guardado igual.");
	}

    // 1) Edición
    if ($id > 0) {
        $chk = $conn->prepare("SELECT id FROM agencias_aduanas WHERE ruc = ? AND id <> ? AND estado = 1");
        $chk->bind_param("si", $ruc, $id);
        $chk->execute();
        if ($chk->get_result()->num_rows > 0) {
            $chk->close();
            return '❌ Ese RUC ya está registrado en otra agencia activa.';
        }
        $chk->close();

        $stmt = $conn->prepare("
            UPDATE agencias_aduanas SET
                nombre = ?, 	ruc = ?, 	direccion = ?,
                departamento_id = ?, 	provincia_id = ?, distrito_id = ?,
                telefono = ?, 	correo_general = ?, 	contacto = ?
            WHERE id = ?
        ");
        $stmt->bind_param("sssiiisssi", $nombre, $ruc, $direccion, $departamento_id, $provincia_id, $distrito_id, $telefono, $correo, $contacto, $id);
        if (!$stmt->execute()) {
            $err = $stmt->error;
            $stmt->close();
            return "❌ Error al actualizar: $err";
        }
        $stmt->close();
        registrarEnHistorial($conn, $_SESSION['usuario'], "Editó agencia aduana (ID: $id)", 'agencias_aduanas', $_SERVER['REMOTE_ADDR']);
        return '';
    }

    // 2) Reactivar si existe en estado=0
    $chk = $conn->prepare("SELECT id FROM agencias_aduanas WHERE ruc = ? AND estado = 0");
    $chk->bind_param("s", $ruc);
    $chk->execute();
    $res = $chk->get_result();
    $chk->close();
    if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $aid = (int)$row['id'];

        $stmt = $conn->prepare("
            UPDATE agencias_aduanas SET
                estado = 1, nombre = ?, direccion = ?,
                departamento_id = ?, provincia_id = ?, distrito_id = ?,
                telefono = ?, correo_general = ?, contacto = ?
            WHERE id = ?
        ");
        $stmt->bind_param("ssiiisssi", $nombre, $direccion, $departamento_id, $provincia_id, $distrito_id, $telefono, $correo, $contacto, $aid);
        if (!$stmt->execute()) {
            $err = $stmt->error;
            $stmt->close();
            return "❌ Error al reactivar: $err";
        }
        $stmt->close();
        registrarEnHistorial($conn, $_SESSION['usuario'], "Reactivó agencia aduana (ID: $aid)", 'agencias_aduanas', $_SERVER['REMOTE_ADDR']);
        return '';
    }

    // 3) Evitar duplicado activo
    $chkActivo = $conn->prepare("SELECT id FROM agencias_aduanas WHERE ruc = ? AND estado = 1");
    $chkActivo->bind_param("s", $ruc);
    $chkActivo->execute();
    if ($chkActivo->get_result()->num_rows > 0) {
        $chkActivo->close();
        return '❌ Ese RUC ya está registrado en otra agencia activa.';
    }
    $chkActivo->close();

    // 4) Insertar nuevo
    $datos = compact(
        'nombre', 'ruc', 'direccion',
        'departamento_id', 'provincia_id', 'distrito_id',
        'telefono', 'correo', 'contacto'
    );
    $datos['correo_general'] = $correo;

    $err = insertarAgenciaNueva($datos);
    return $err;
}

