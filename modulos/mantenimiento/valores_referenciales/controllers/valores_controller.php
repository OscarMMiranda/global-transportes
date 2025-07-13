<?php
    require_once __DIR__ . '/../../../../includes/conexion.php';

	/**
	 * Helper para preparar statements con chequeo de errores
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
	 * Lista todos los valores referenciales vigentes (activo = 1)
	 */
	function listarValoresReferenciales() {
    	$sql = "
      		SELECT 
        		vr.id,
        		z.nombre                 AS zona,
        		tm.nombre                AS tipo_mercaderia,
        		vr.anio,
        		vr.monto
      		FROM valor_referencial vr
      		JOIN zona z   ON vr.zona_id              = z.id
      		JOIN tipos_mercaderia tm ON vr.tipo_mercaderia_id = tm.id
      		WHERE vr.activo = 1
      		ORDER BY z.km_inicio, tm.nombre
    		";
    	$stmt = prep($sql);
    		if (!$stmt->execute()) {
    			die("Error en execute(): ({$stmt->errno}) {$stmt->error}");
    			}
    		$res = $stmt->get_result();
    		return $res->fetch_all(MYSQLI_ASSOC);
		}

	/**
	 * Lista todas las zonas padre con estado = 1
	 */
	function listarZonasPadre() {
    	$sql = "SELECT id, nombre FROM zona WHERE estado = 1 ORDER BY km_inicio";
    	$stmt = prep($sql);
    	$stmt->execute();
    	return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
		}

/**
 * Lista todos los tipos de mercadería activos
 */
function listarTiposMercaderia() {
    $sql = "SELECT id, nombre FROM tipos_mercaderia WHERE estado = 1 ORDER BY nombre";
    $stmt = prep($sql);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

/**
 * Obtiene un valor referencial por su ID (para cargar el modal si quisieras editar)
 */
function obtenerValorReferencial($id) {
    $sql = "SELECT id, zona_id, tipo_mercaderia_id, anio, monto FROM valor_referencial WHERE id = ?";
    $stmt = prep($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    return $row ?: ['id'=>0,'zona_id'=>0,'tipo_mercaderia_id'=>0,'anio'=>date('Y'),'monto'=>0.00];
}

/**
 * Inserta un nuevo valor referencial (versionando):
 * - desactiva el anterior vigente para esa zona+tipo
 * - inserta el nuevo con activo = 1
 * Devuelve cadena vacía o mensaje de error.
 */
function procesarValorReferencial($post) {
    global $conn;
    $zona_id            = (int)$post['zona_id'];
    $tipo_id            = (int)$post['tipo_mercaderia_id'];
    $anio               = (int)$post['anio'];
    $monto              = (float)$post['monto'];

    // Validaciones básicas
    if ($zona_id <= 0)      return '❌ Debes seleccionar una zona.';
    if ($tipo_id <= 0)      return '❌ Debes seleccionar un tipo de mercadería.';
    if ($anio < 2000)       return '❌ Año inválido.';
    if ($monto <= 0)        return '❌ Monto debe ser mayor a cero.';

    // 1) Desactiva el registro anterior
    $sql = "
      UPDATE valor_referencial
      SET activo = 0, estado = 0
      WHERE zona_id = ? 
        AND tipo_mercaderia_id = ? 
        AND activo = 1
    ";
    $stmt = prep($sql);
    $stmt->bind_param("ii", $zona_id, $tipo_id);
    if (!$stmt->execute()) {
        return "❌ Error al desactivar anterior: {$stmt->error}";
    }
    $stmt->close();

    // 2) Inserta el nuevo
    $sql = "
      INSERT INTO valor_referencial
        (zona_id, tipo_mercaderia_id, anio, monto, estado, activo)
      VALUES (?, ?, ?, ?, 1, 1)
    ";
    $stmt = prep($sql);
    $stmt->bind_param("iiid", $zona_id, $tipo_id, $anio, $monto);
    if (!$stmt->execute()) {
        return "❌ Error al guardar nuevo valor: {$stmt->error}";
    }
    $stmt->close();

    return '';



	/**
 * Trae los datos de la vista vw_valores_local
 */


}

function listarValoresPivot() {
    $sql = "SELECT * FROM vw_valores_local ORDER BY zona_id";
    $stmt = prep($sql);
    if (!$stmt->execute()) {
        die("Error en execute() de listarValoresPivot: ({$stmt->errno}) {$stmt->error}");
    }
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}
