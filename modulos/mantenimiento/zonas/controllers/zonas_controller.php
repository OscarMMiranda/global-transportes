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
 * Trae Zonas padre
 */
function listarZonasPadre() {
    global $conn;
    $sql = "SELECT id, nombre 
		FROM zona 
		WHERE estado = 1 
		ORDER BY nombre";

    // Intentamos preparar la consulta
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        // Si hay error, lo mostramos y detenemos la ejecución
        die("Error en prepare() de listarZonasPadre: (" 
            . $conn->errno . ") " . $conn->error);
    }

    // Ejecutamos
    if (!$stmt->execute()) {
        die("Error en execute() de listarZonasPadre: (" 
            . $stmt->errno . ") " . $stmt->error);
    }

    // Obtenemos el resultado
    $result = $stmt->get_result();
    if (!$result) {
        die("Error en get_result() de listarZonasPadre: (" 
            . $stmt->errno . ") " . $stmt->error);
    }

    $rows = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $rows;
}

// function listarDistritosDisponibles() {
//     $sql = "
//         SELECT id, nombre 
//         FROM distritos 
//         WHERE departamento_id = ? AND provincia_id = ?
//         ORDER BY nombre
//     ";
//     $stmt = prep($sql);
//     $departamentoLima = 15; // ejemplo: Lima = 15
//     $provinciaLima    = 1501; // ejemplo: Lima provincia = 1501
//     $stmt->bind_param("ii", $departamentoLima, $provinciaLima);
//     if (!$stmt->execute()) {
//         die("Error en execute(): ({$stmt->errno}) {$stmt->error}");
//     }
//     $res = $stmt->get_result();
//     return $res->fetch_all(MYSQLI_ASSOC);
// }

function listarDistritosDisponibles() {
    // IDs permitidos manualmente
    $idsLima = [1, 2, 3, 4, 5]; // <- reemplaza con los reales de tu tabla

    // Arma el listado dinámicamente
    $placeholders = implode(',', array_fill(0, count($idsLima), '?'));
    $sql = "SELECT id, nombre FROM distritos WHERE id IN ($placeholders) ORDER BY nombre";

    $stmt = prep($sql);
    $stmt->bind_param(str_repeat('i', count($idsLima)), ...$idsLima);
    if (!$stmt->execute()) {
        die("Error al filtrar distritos de Lima: ({$stmt->errno}) {$stmt->error}");
    }
    $res = $stmt->get_result();
    return $res->fetch_all(MYSQLI_ASSOC);
}


function listarDistritos() {
    $sql = "
      SELECT 
        z.id,
        zp.nombre AS zona_padre,
        d.nombre  AS distrito,
        z.estado
      FROM zonas z
      JOIN zona      zp ON z.zona_id     = zp.id
      JOIN distritos d  ON z.distrito_id = d.id
      ORDER BY zp.nombre, d.nombre
    ";
    $stmt = prep($sql);
    if (!$stmt->execute()) {
        die("Error en execute(): ({$stmt->errno}) {$stmt->error}");
    }
    $res = $stmt->get_result();
    if (!$res) {
        die("Error en get_result(): ({$stmt->errno}) {$stmt->error}");
    }
    $data = $res->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $data;
}

function obtenerDistrito($id) {
  global $conn;
  $id = (int)$id;
  $sql = "SELECT id, zona_id, distrito_id FROM zonas WHERE id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $res = $stmt->get_result()->fetch_assoc();
  $stmt->close();
  return $res ?: ['id'=>0, 'zona_id'=>0, 'distrito_id'=>0];
}

function procesarDistrito($post) {
  global $conn;
  $id          = (int) $post['id'];
  $zona_id     = (int) $post['zona_id'];
  $distrito_id = (int) $post['distrito_id'];

  if (!$zona_id) return '❌ Debes seleccionar una zona principal.';
  if (!$distrito_id) return '❌ Debes seleccionar un distrito.';

  // Evitar duplicados
  $sqlChk = $id > 0
    ? "SELECT id FROM zonas WHERE zona_id = ? AND distrito_id = ? AND id <> ?"
    : "SELECT id FROM zonas WHERE zona_id = ? AND distrito_id = ?";
  $stmt = $conn->prepare($sqlChk);
  if ($id > 0) $stmt->bind_param("iii", $zona_id, $distrito_id, $id);
  else         $stmt->bind_param("ii",  $zona_id, $distrito_id);
  $stmt->execute();
  if ($stmt->get_result()->num_rows) {
    $stmt->close();
    return '❌ Ese distrito ya está asignado a la zona seleccionada.';
  }
  $stmt->close();

  // Insertar o actualizar
  if ($id > 0) {
    $sql = "UPDATE zonas SET zona_id = ?, distrito_id = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $zona_id, $distrito_id, $id);
  } else {
    $sql = "INSERT INTO zonas (zona_id, distrito_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $zona_id, $distrito_id);
  }

  if (!$stmt->execute()) {
    $err = $stmt->error;
    $stmt->close();
    return "❌ Error al guardar: $err";
  }

  $stmt->close();
  return '';
}
