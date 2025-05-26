<?php
//session_start();
require_once '../../includes/conexion.php';
require_once '../../includes/header_erp.php';

// Verificar acceso solo para administradores
if (!isset($_SESSION['usuario']) || !isset($_SESSION['rol_nombre']) || $_SESSION['rol_nombre'] !== 'admin') {
    die("❌ Acceso denegado.");
}

// Activar depuración de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');

// Consultar lugares en la base de datos con cantidad de locales y dirección
$sql = "SELECT l.id, l.nombre, l.direccion, tl.nombre AS tipo, d.nombre AS distrito, 
            (SELECT COUNT(*) FROM locales WHERE lugar_id = l.id) AS cantidad_locales 
        FROM lugares l
        JOIN tipo_lugares tl ON l.tipo_id = tl.id
        JOIN distritos d ON l.distrito_id = d.id
        ORDER BY l.nombre ASC";

$result = $conn->query($sql);
if (!$result) {
    die("❌ Error en la consulta de lugares: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Lugares</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center">📍 Gestión de Lugares</h2>
        <div class="text-end mb-3">
            <a href="crear_lugar.php" class="btn btn-primary">➕ Crear Nuevo Lugar</a>
        </div>

        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Nombre</th>
                    <th>Tipo</th>
                    <th>Distrito</th>
                    <th>Dirección</th>
                    <th>Cantidad de Locales</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . htmlspecialchars($row['nombre']) . "</td>
                                <td>" . htmlspecialchars($row['tipo']) . "</td>
                                <td>" . htmlspecialchars($row['distrito']) . "</td>
                                <td>" . htmlspecialchars($row['direccion']) . "</td>
                                <td>" . $row['cantidad_locales'] . "</td>
                                <td>
                                    <button class='btn btn-info btn-sm' data-bs-toggle='modal' data-bs-target='#modalLugar'
                                        onclick='cargarDetalles(".json_encode($row).")'>👁️ Ver Detalles</button>
                                    <a href='editar_lugar.php?id=" . $row['id'] . "' class='btn btn-warning btn-sm'>✏️ Editar</a>
                                    <a href='eliminar_lugar.php?id=" . $row['id'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"¿Estás seguro?\")'>🗑 Eliminar</a>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='text-center'>No hay lugares registrados.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Modal para mostrar detalles del lugar -->
    <div class="modal fade" id="modalLugar" tabindex="-1" aria-labelledby="modalLugarLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalLugarLabel">Detalles del Lugar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title" id="nombreLugar"></h5>
                            <p class="card-text"><strong>Dirección:</strong> <span id="direccionLugar"></span></p>
                            <p class="card-text"><strong>Tipo:</strong> <span id="tipoLugar"></span></p>
                            <p class="card-text"><strong>Distrito:</strong> <span id="distritoLugar"></span></p>
                            <p class="card-text"><strong>Cantidad de Locales:</strong> <span id="cantidadLocales"></span></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
    function cargarDetalles(lugar) {
        document.getElementById('nombreLugar').textContent = lugar.nombre;
        document.getElementById('direccionLugar').textContent = lugar.direccion;
        document.getElementById('tipoLugar').textContent = lugar.tipo;
        document.getElementById('distritoLugar').textContent = lugar.distrito;
        document.getElementById('cantidadLocales').textContent = lugar.cantidad_locales;
    }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php require_once '../../includes/footer_lugar.php'; ?>
