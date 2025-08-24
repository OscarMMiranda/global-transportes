<?php


// 2) Modo depuración (solo DEV)
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors',     1);
ini_set('error_log',      __DIR__ . '/error_log.txt');

// 3) Cargar config.php (define getConnection() y rutas)
require_once __DIR__ . '/../../includes/config.php';

// 4) Obtener la conexión
$conn = getConnection();


require_once '../../includes/header_erp.php';

// Verificar si se ha enviado el ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("<p style='color: red;'>❌ ID de vehículo no válido.</p>");
}

$vehiculo_id = intval($_GET['id']);

// Consultar datos actuales del vehículo
$sql = "SELECT * FROM vehiculos WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $vehiculo_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("<p style='color: red;'>❌ Vehículo no encontrado.</p>");
}

$vehiculo = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Vehículo</title>
    <link rel="stylesheet" href="../../css/base.css">
    <script>
        function convertirMayusculas(input) {
            input.value = input.value.toUpperCase();
        }
    </script>
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center mb-4">Editar Vehículo: <?= htmlspecialchars($vehiculo['placa']) ?></h2>

        <form action="procesar_edicion.php" method="POST">
            <input type="hidden" name="id" value="<?= $vehiculo['id'] ?>">

            <div class="mb-3">
                <label class="form-label">Placa:</label>
                <input type="text" name="placa" class="form-control" value="<?= htmlspecialchars($vehiculo['placa']) ?>" required oninput="convertirMayusculas(this)">
            </div>

            <div class="mb-3">
                <label class="form-label">Tipo:</label>
                <select name="tipo_id" class="form-select">
                    <option value="" disabled>Seleccione un tipo...</option>
                    <?php
                    $sqlTipos = "SELECT id, nombre FROM tipo_vehiculo ORDER BY nombre ASC";
                    $resultTipos = $conn->query($sqlTipos);
                    while ($tipo = $resultTipos->fetch_assoc()) {
                        $selected = ($tipo['id'] == $vehiculo['tipo_id']) ? "selected" : "";
                        echo "<option value='{$tipo['id']}' {$selected}>{$tipo['nombre']}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Marca:</label>
                <select name="marca_id" class="form-select">
                    <option value="" disabled>Seleccione una marca...</option>
                    <?php
                    $sqlMarcas = "SELECT id, nombre FROM marca_vehiculo ORDER BY nombre ASC";
                    $resultMarcas = $conn->query($sqlMarcas);
                    while ($marca = $resultMarcas->fetch_assoc()) {
                        $selected = ($marca['id'] == $vehiculo['marca_id']) ? "selected" : "";
                        echo "<option value='{$marca['id']}' {$selected}>{$marca['nombre']}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Modelo:</label>
                <input type="text" name="modelo" class="form-control" value="<?= htmlspecialchars($vehiculo['modelo']) ?>" required oninput="convertirMayusculas(this)">
            </div>

            <div class="mb-3">
                <label class="form-label">Año:</label>
                <input type="number" name="anio" class="form-control" value="<?= htmlspecialchars($vehiculo['anio']) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Empresa:</label>
                <select name="empresa_id" class="form-select">
                    <option value="" disabled>Seleccione una empresa...</option>
                    <?php
                    $sqlEmpresas = "SELECT id, razon_social FROM empresa ORDER BY razon_social ASC";
                    $resultEmpresas = $conn->query($sqlEmpresas);
                    while ($empresa = $resultEmpresas->fetch_assoc()) {
                        $selected = ($empresa['id'] == $vehiculo['empresa_id']) ? "selected" : "";
                        echo "<option value='{$empresa['id']}' {$selected}>{$empresa['razon_social']}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Estado del Vehículo:</label>
                <select name="estado_id" class="form-select">
                    <?php
                    $sqlEstados = "SELECT id, nombre FROM estado_vehiculo ORDER BY nombre ASC";
                    $resultEstados = $conn->query($sqlEstados);
                    while ($estado = $resultEstados->fetch_assoc()) {
                        $selected = ($estado['id'] == $vehiculo['estado_id']) ? "selected" : "";
                        echo "<option value='{$estado['id']}' {$selected}>{$estado['nombre']}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Observaciones:</label>
                <textarea name="observaciones" class="form-control"><?= htmlspecialchars($vehiculo['observaciones']) ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </form>
    </div>

    <?php require_once '../../includes/footer_erp.php'; ?>
</body>
</html>
