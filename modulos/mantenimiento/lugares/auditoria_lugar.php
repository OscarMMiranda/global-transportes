<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
$conn = getConnection();

// ✅ Validar parámetro ID
$lugar_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($lugar_id <= 0) {
    echo "<div class='alert alert-danger'>ID inválido</div>";
    return;
}

// 🔧 Función para obtener nombre legible desde una tabla por ID
function getNombre($tabla, $id, $conn) {
    $id = intval($id);
    $tabla = preg_replace('/[^a-z_]/', '', $tabla); // sanitizar tabla
    $sql = "SELECT nombre FROM $tabla WHERE id = $id LIMIT 1";
    $res = $conn->query($sql);
    if ($res && $row = $res->fetch_assoc()) {
        return $row['nombre'];
    }
    return "ID $id";
}

// 🔧 Función para resaltar campos modificados
function resaltarCambios($antes, $despues) {
    $resaltado = [];
    foreach ($despues as $clave => $valor) {
        $valor_antes = isset($antes[$clave]) ? $antes[$clave] : null;
        $cambio = ($valor != $valor_antes);
        $resaltado[$clave] = $cambio
            ? "<span style='background:#ffeeba; padding:2px 4px; border-radius:3px;'>$valor</span>"
            : htmlspecialchars($valor);
    }
    return $resaltado;
}

// 🔍 Consultar auditoría
$sql = "SELECT * FROM auditoria_lugares WHERE lugar_id = $lugar_id ORDER BY fecha DESC";
$res = $conn->query($sql);

if (!$res || $res->num_rows === 0) {
    echo "<div class='alert alert-warning'>No hay auditoría registrada para este lugar.</div>";
    return;
}

// 🎨 Estilos visuales
echo "<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css'>";
echo "<div class='container' style='margin-top:20px'>";
echo "<h4><i class='fa fa-history'></i> Auditoría del lugar #$lugar_id</h4>";
echo "<table class='table table-bordered table-striped'>";
echo "<thead><tr class='info'>
        <th>Fecha</th>
        <th>Usuario</th>
        <th>Acción</th>
        <th>Valores antes</th>
        <th>Valores después</th>
      </tr></thead><tbody>";

// ✅ Mapeo extendido para mostrar nombres legibles
$campos_a_mapear = [
    'tipo_id' => 'tipos',
    'entidad_id' => 'entidades',
    'distrito_id' => 'distritos',
    'provincia_id' => 'provincias',
    'departamento_id' => 'departamentos'
];

while ($row = $res->fetch_assoc()) {
    $fecha    = htmlspecialchars($row['fecha']);
    $usuario  = htmlspecialchars($row['usuario']);
    $accion   = htmlspecialchars($row['accion']);
    $antes    = json_decode($row['valores_antes'], true);
    $despues  = json_decode($row['valores_despues'], true);

    // 🔧 Corrección defensiva para auditorías contaminadas
    foreach (['antes', 'despues'] as $var) {
        foreach (['tipo', 'entidad'] as $campo) {
            if (isset($$var[$campo]) && preg_match('/ID (\d+)/', $$var[$campo], $match)) {
                $$var[$campo . '_id'] = intval($match[1]);
                unset($$var[$campo]);
            }
        }
    }

    // 🔧 Mapear nombres legibles
    foreach ($campos_a_mapear as $campo => $tabla) {
        if (isset($antes[$campo])) {
            $antes[str_replace('_id', '', $campo)] = getNombre($tabla, $antes[$campo], $conn);
            unset($antes[$campo]);
        }
        if (isset($despues[$campo])) {
            $despues[str_replace('_id', '', $campo)] = getNombre($tabla, $despues[$campo], $conn);
            unset($despues[$campo]);
        }
    }

    $resaltado = resaltarCambios($antes, $despues);

    echo "<tr>";
    echo "<td>$fecha</td>";
    echo "<td>$usuario</td>";
    echo "<td><span class='label label-" . ($accion === 'insert' ? 'success' : 'warning') . "'>$accion</span></td>";

    echo "<td><div style='max-height:200px; overflow:auto;'><pre>" .
         json_encode($antes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "</pre></div></td>";

    echo "<td><div style='max-height:200px; overflow:auto;'><pre>" .
         json_encode($resaltado, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "</pre></div></td>";

    echo "</tr>";
}

echo "</tbody></table></div>";
?>