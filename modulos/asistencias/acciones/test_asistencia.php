<?php
require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

$sql = "
    SELECT 
        ac.id,
        ac.tipo_id,
        ac.hora_entrada,
        ac.hora_salida,
        ac.observacion,
        ac.fecha,
        CONCAT(c.nombres, ' ', c.apellidos) AS conductor
    FROM asistencia_conductores ac
    INNER JOIN conductores c ON c.id = ac.conductor_id
    WHERE ac.id = ?
";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

mysqli_stmt_bind_result(
    $stmt,
    $rid,
    $rtipo,
    $rentrada,
    $rsalida,
    $robs,
    $rfecha,
    $rconductor
);

if (mysqli_stmt_fetch($stmt)) {

    echo json_encode([
        'ok' => true,
        'data' => [
            'id'           => $rid,
            'tipo_id'      => $rtipo,
            'hora_entrada' => $rentrada,
            'hora_salida'  => $rsalida,
            'observacion'  => $robs,
            'fecha'        => $rfecha,
            'conductor'    => $rconductor
        ]
    ]);

} else {
    echo json_encode(['ok' => false, 'error' => 'Registro no encontrado']);
}
