<?php
require_once __DIR__ . '/../../../../includes/conexion.php';

/**
 * Helper para preparar y verificar statements
 */
function prep($sql) {
    global $conn;
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error en prepare(): ({$conn->errno}) {$conn->error}\nSQL: $sql");
    }
    return $stmt;
}

/**
 * Trae zonas padre activas
 */
function listarZonasPadre() {
    global $conn;
    $sql = "SELECT id, nombre FROM zona WHERE estado = 1 ORDER BY nombre";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error en prepare() de listarZonasPadre: (" . $conn->errno . ") " . $conn->error);
    }
    if (!$stmt->execute()) {
        die("Error en execute() de listarZonasPadre: (" . $stmt->errno . ") " . $stmt->error);
    }
    $result = $stmt->get_result();
    if (!$result) {
        die("Error en get_result() de listarZonasPadre: (" . $stmt->errno . ") " . $stmt->error);
    }
    $rows = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $rows;
}




/**
 * Trae todos los departamentos
 */
function listarDepartamentos() {
    global $conn;
    $sql = "SELECT id, nombre FROM departamentos ORDER BY nombre";
    $stmt = prep($sql);
    if (!$stmt->execute()) {
        die("Error en listarDepartamentos: ({$stmt->errno}) {$stmt->error}");
    }
    $res = $stmt->get_result();
    return $res->fetch_all(MYSQLI_ASSOC);
}

/**
 * Trae todas las provincias con su departamento asociado
 */
function listarProvincias() {
    global $conn;
    $sql = "SELECT id, nombre, departamento_id FROM provincias ORDER BY nombre";
    $stmt = prep($sql);
    if (!$stmt->execute()) {
        die("Error en listarProvincias: ({$stmt->errno}) {$stmt->error}");
    }
    $res = $stmt->get_result();
    return $res->fetch_all(MYSQLI_ASSOC);
}

function listarRutas() {
    $sql = "
        SELECT 
            z.id,
            zp.nombre AS zona_padre,
            do.nombre AS distrito_origen,
            po.nombre AS provincia_origen,
            deo.nombre AS departamento_origen,
            dd.nombre AS distrito_destino,
            pd.nombre AS provincia_destino,
            ded.nombre AS departamento_destino,
            z.kilometros,
            z.estado
        FROM zonas z
        JOIN zona zp ON z.zona_id = zp.id
        JOIN distritos do ON z.origen_id = do.id
        JOIN provincias po ON do.provincia_id = po.id
        JOIN departamentos deo ON po.departamento_id = deo.id
        JOIN distritos dd ON z.destino_id = dd.id
        JOIN provincias pd ON dd.provincia_id = pd.id
        JOIN departamentos ded ON pd.departamento_id = ded.id
        ORDER BY zp.nombre, do.nombre
    ";
    $stmt = prep($sql);
    if (!$stmt->execute()) {
        error_log("❌ Error en listarRutas: ({$stmt->errno}) {$stmt->error}");
        return [];
    }
    $res = $stmt->get_result();
    if (!$res) {
        error_log("❌ Error en get_result() de listarRutas");
        return [];
    }
    $data = $res->fetch_all(MYSQLI_ASSOC);
    error_log("✅ listarRutas cargó " . count($data) . " rutas.");
    $stmt->close();
    return $data;
}

function obtenerRutaExtendida($id) {
    global $conn;
    $sql = "
        SELECT 
            z.id,
            z.zona_id,
            z.origen_id,
            z.destino_id,
            z.kilometros
        FROM zonas z
        WHERE z.id = ?
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function procesarRuta($post) {
    global $conn;
    $id         = (int) $post['id'];
    $zona_id    = (int) $post['zona_id'];
    $origen_id  = (int) $post['origen_id'];
    $destino_id = (int) $post['destino_id'];
    $km         = isset($post['kilometros']) ? (float) $post['kilometros'] : null;

    if (!$zona_id)    return '❌ Debes seleccionar una zona.';
    if (!$origen_id)  return '❌ Debes seleccionar el distrito de origen.';
    if (!$destino_id) return '❌ Debes seleccionar el distrito de destino.';
    if ($origen_id === $destino_id) return '❌ El origen y destino no pueden ser iguales.';

    // Validar duplicados
    $sqlChk = $id > 0
        ? "SELECT id FROM zonas WHERE zona_id = ? AND origen_id = ? AND destino_id = ? AND id <> ?"
        : "SELECT id FROM zonas WHERE zona_id = ? AND origen_id = ? AND destino_id = ?";
    $stmt = $conn->prepare($sqlChk);
    if ($id > 0) $stmt->bind_param("iiii", $zona_id, $origen_id, $destino_id, $id);
    else         $stmt->bind_param("iii",  $zona_id, $origen_id, $destino_id);
    $stmt->execute();
    if ($stmt->get_result()->num_rows) {
        $stmt->close();
        return '❌ Esa ruta ya está registrada en la zona seleccionada.';
    }
    $stmt->close();

    // Insertar o actualizar
    if ($id > 0) {
        $sql = "UPDATE zonas SET zona_id = ?, origen_id = ?, destino_id = ?, kilometros = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiidi", $zona_id, $origen_id, $destino_id, $km, $id);
    } else {
        $sql = "INSERT INTO zonas (zona_id, origen_id, destino_id, kilometros) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiid", $zona_id, $origen_id, $destino_id, $km);
    }

    if (!$stmt->execute()) {
        $err = $stmt->error;
        $stmt->close();
        return "❌ Error al guardar: $err";
    }

    $stmt->close();
    return '';
}

function listarDistritosDisponibles() {
    global $conn;
    $sql = "
        SELECT 
            d.id,
            d.nombre,
            d.provincia_id,
            p.nombre AS provincia,
            dp.nombre AS departamento
        FROM distritos d
        JOIN provincias p ON d.provincia_id = p.id
        JOIN departamentos dp ON p.departamento_id = dp.id
        ORDER BY dp.nombre, p.nombre, d.nombre
    ";
    $stmt = prep($sql);
    if (!$stmt->execute()) {
        die("Error en listarDistritosDisponibles: ({$stmt->errno}) {$stmt->error}");
    }
    $res = $stmt->get_result();
    return $res->fetch_all(MYSQLI_ASSOC);
}


function listarRutas($incluirInactivas = false) {
    $sql = "
        SELECT 
            z.id,
            zp.nombre AS zona_padre,
            do.nombre AS distrito_origen,
            po.nombre AS provincia_origen,
            deo.nombre AS departamento_origen,
            dd.nombre AS distrito_destino,
            pd.nombre AS provincia_destino,
            ded.nombre AS departamento_destino,
            z.kilometros,
            z.estado
        FROM zonas z
        JOIN zona zp ON z.zona_id = zp.id
        JOIN distritos do ON z.origen_id = do.id
        JOIN provincias po ON do.provincia_id = po.id
        JOIN departamentos deo ON po.departamento_id = deo.id
        JOIN distritos dd ON z.destino_id = dd.id
        JOIN provincias pd ON dd.provincia_id = pd.id
        JOIN departamentos ded ON pd.departamento_id = ded.id
        " . ($incluirInactivas ? "" : "WHERE z.estado = 1") . "
        ORDER BY zp.nombre, do.nombre
    ";
    ...
}