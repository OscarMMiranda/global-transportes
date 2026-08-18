<?php
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 0);
error_reporting(E_ALL);

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

if ($id <= 0) {
    echo json_encode([
        "success" => false,
        "html" => "<div class='text-danger text-center py-4'>ID inválido</div>"
    ]);
    exit;
}

$conn = getConnection();

/*
 * 🔥 IMPORTANTE:
 * Tu tabla REAL tiene la columna: descripcion
 * NO existe: descripcion_archivo
 * Por eso corregimos el SELECT
 */
$sql = "SELECT id, archivo, descripcion, created_at 
        FROM papeleta_archivos 
        WHERE papeleta_id = $id 
          AND (deleted_at IS NULL OR deleted_at = '') 
        ORDER BY created_at DESC";

$res = mysqli_query($conn, $sql);

if (!$res) {
    echo json_encode([
        "success" => false,
        "html" => "<div class='text-danger text-center py-4'>Error SQL: " . mysqli_error($conn) . "</div>"
    ]);
    exit;
}

$html = "<table class='table table-bordered table-hover'>";
$html .= "<thead class='table-light'>
            <tr>
                <th>Archivo</th>
                <th>Descripción</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
         </thead>
         <tbody>";

$baseUrl  = "/uploads/papeletas/";
$basePath = $_SERVER['DOCUMENT_ROOT'] . $baseUrl;

while ($row = mysqli_fetch_assoc($res)) {

    $archivo     = $row['archivo'];
    $descripcion = htmlspecialchars($row['descripcion'], ENT_QUOTES, 'UTF-8');
    $fecha       = $row['created_at'];

    // Ruta física del archivo
    $rutaFisica  = $basePath . $archivo;

    // Ruta pública para abrir en navegador
    $rutaPublica = $baseUrl . $archivo;

    $existe = file_exists($rutaFisica);

    $html .= "<tr>";

    if ($existe) {
        $html .= "<td><a href='$rutaPublica' target='_blank'>$archivo</a></td>";
    } else {
        $html .= "<td class='text-danger'>$archivo (no encontrado)</td>";
    }

    $html .= "<td>$descripcion</td>";
    $html .= "<td>$fecha</td>";

    $html .= "<td>
                <button class='btn btn-danger btn-sm btnEliminarArchivo' data-id='" . $row['id'] . "'>
                    <i class='fa fa-trash'></i>
                </button>
              </td>";

    $html .= "</tr>";
}

$html .= "</tbody></table>";

echo json_encode([
    "success" => true,
    "html" => $html
]);
exit;
