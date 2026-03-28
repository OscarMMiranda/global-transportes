<?php
require_once __DIR__ . '/../../../includes/config.php';

$conn = getConnection();
$id = intval($_POST['id']);

$sql = "
    SELECT fecha, accion, usuario, ip_origen
    FROM clientes_historial
    WHERE id_registro = $id
    ORDER BY fecha DESC
";

$result = $conn->query($sql);

$html = "";

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $html .= "
            <tr>
                <td>{$row['fecha']}</td>
                <td>{$row['accion']}</td>
                <td>{$row['usuario']}</td>
                <td>{$row['ip_origen']}</td>
                <td><button class='btn btn-dark btn-sm'>Ver</button></td>
            </tr>
        ";
    }
} else {
    $html = "
        <tr>
            <td colspan='5' class='text-center text-muted'>
                No hay historial registrado.
            </td>
        </tr>
    ";
}

echo $html;
exit;
