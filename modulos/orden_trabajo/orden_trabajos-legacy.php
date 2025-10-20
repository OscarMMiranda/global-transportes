<?php
// archivo: /modulos/orden_trabajo/orden_trabajos.php
session_start();
require_once '../../includes/config.php';
$conn = getConnection();


// Activar depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');

// Verificar acceso solo para administradores
if (!isset($_SESSION['usuario']) || $_SESSION['rol_nombre'] !== 'admin') {
    die("❌ Acceso denegado.");
}

// Registrar auditoría
require_once '../../includes/funciones.php';
// registrarActividad($conn, $_SESSION['usuario'], 'Acceso a módulo Órdenes de Trabajo');

// Consulta de órdenes activas
$sql = "SELECT ot.id, ot.numero_ot, ot.fecha, ot.factura_numero,
               c.nombre AS cliente_nombre,
               eo.nombre AS estado_nombre
        FROM ordenes_trabajo ot
        LEFT JOIN clientes c ON ot.cliente_id = c.id
        LEFT JOIN estado_orden_trabajo eo ON ot.estado_ot = eo.id
        WHERE eo.id NOT IN (7,8)
        ORDER BY CAST(SUBSTRING_INDEX(numero_ot, '-', -1) AS UNSIGNED) DESC,
                 CAST(SUBSTRING_INDEX(numero_ot, '-', 1) AS UNSIGNED) DESC";

$result = $conn->query($sql);
if (!$result) {
    die("❌ Error en la consulta de órdenes: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Órdenes de Trabajo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap + DataTables -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
</head>
<body>
<div class="container mt-4">
    <h2 class="text-center">⚙️ Gestión de Órdenes de Trabajo</h2>

    <!-- Botones de acción -->
    <div class="d-flex gap-2 mb-3">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#crearOrdenModal">➕ Crear Nueva Orden</button>
        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editarOrdenModal">✏️ Editar Orden</button>
        <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#verOrdenModal">👁️ Ver Orden</button>
        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#eliminarOrdenModal">🗑 Eliminar Orden</button>
    </div>

    <!-- Tabla de órdenes -->
    <table id="ordenesTabla" class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>Fecha</th>
                <th>Número OT</th>
                <th>Cliente</th>
                <th>Estado</th>
                <th>Factura</th>
            </tr>
        </thead>
        <tbody>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $estado = $row['estado_nombre'];
                $estadoClase = ($estado === 'Pendiente') ? 'bg-warning' :
                               (($estado === 'Completado') ? 'bg-success text-white' : 'bg-danger text-white');

                echo "<tr>
                        <td>{$row['fecha']}</td>
                        <td>{$row['numero_ot']}</td>
                        <td>{$row['cliente_nombre']}</td>
                        <td class='$estadoClase'>{$estado}</td>
                        <td>" . (!empty($row['factura_numero']) ? $row['factura_numero'] : '-') . "</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='5' class='text-center'>No hay órdenes activas.</td></tr>";
        }
        ?>
        </tbody>
    </table>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    $('#ordenesTabla').DataTable({
        ordering: true,
        searching: true,
        paging: true,
        info: true
    });
});
</script>
</body>
</html>