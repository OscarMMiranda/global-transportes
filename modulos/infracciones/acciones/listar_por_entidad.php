<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
$conn = getConnection();

$entidad_id = isset($_POST['entidad_id']) ? intval($_POST['entidad_id']) : 0;

if ($entidad_id <= 0) {
    echo "";
    exit;
}

$sql = "
    SELECT 
        id,
        codigo,
        descripcion
    FROM infracciones
    WHERE entidad_emisora_id = $entidad_id
      AND estado = 'Activo'
    ORDER BY codigo ASC
";

$res = mysqli_query($conn, $sql);

$html = "";

if ($res) {
    while ($row = mysqli_fetch_assoc($res)) {

        // Formato: [CODIGO] DESCRIPCIÓN
        $texto = "[" . $row['codigo'] . "] " . $row['descripcion'];

        $html .= "<option value='" . $row['id'] . "'>" . $texto . "</option>";
    }
}

echo $html;
