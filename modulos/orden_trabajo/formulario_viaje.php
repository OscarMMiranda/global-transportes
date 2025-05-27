<?php
require_once '../../includes/conexion.php';

// Obtener el ID de la orden de trabajo desde `$_GET` o `$_SESSION`
$ordenID = isset($_GET['orden_trabajo_id']) ? intval($_GET['orden_trabajo_id']) : (isset($_SESSION['orden_trabajo_id']) ? intval($_SESSION['orden_trabajo_id']) : 0);

// Verificar que la orden se haya recibido correctamente
if ($ordenID === 0) {
    echo "<div class='alert alert-danger'>🚨 Error: No se recibió un ID de orden válido.</div>";
    exit();
} else {
    echo "<p>🔎 ID de la orden recibido: " . htmlspecialchars($ordenID) . "</p>";
}

// CONSULTA: Obtener vehículos disponibles
    $sqlVehiculos = "SELECT v.id, v.placa, CONCAT(c.nombres, ' ', c.apellidos) AS conductor 
                 FROM asignaciones_conductor ac
                 INNER JOIN vehiculos v ON ac.vehiculo_id = v.id
                 INNER JOIN conductores c ON ac.conductor_id = c.id
                 INNER JOIN estado_asignacion ea ON ac.estado_id = ea.id
                 WHERE ea.nombre = 'activo'
                 ORDER BY v.placa ASC";




$resultVehiculos = $conn->query($sqlVehiculos);

// Verificación de datos obtenidos
if (!$resultVehiculos) {
    die("🚨 Error en la consulta de vehículos: " . $conn->error);
}

if ($resultVehiculos->num_rows === 0) {
    echo "<div class='alert alert-warning'>🚨 No hay vehículos disponibles en la base de datos.</div>";
}

// CONSULTA: Obtener lugares disponibles
$sqlLugares = "SELECT l.id, l.nombre, d.nombre AS distrito 
               FROM lugares l 
               LEFT JOIN distritos d ON l.distrito_id = d.id 
               ORDER BY l.nombre ASC";
$resultLugares = $conn->query($sqlLugares);

if (!$resultLugares) {
    die("🚨 Error en la consulta de lugares: " . $conn->error);
}
?>

<form method="POST" action="registrar_viaje.php">
    <h4 class="text-primary">✍️ Registro de Viaje</h4>

    <!-- Campo oculto para el ID de la orden de trabajo -->
    <input type="hidden" name="orden_trabajo_id" value="<?= htmlspecialchars($ordenID) ?>">

    <!-- Fecha del viaje -->
    <div class="mb-3">
        <label for="fecha_salida">Fecha del viaje:</label>
        <input type="date" name="fecha_salida" id="fecha_salida" class="form-control w-25" required value="<?= date('Y-m-d') ?>">
    </div>

    <!-- Selección de vehículo -->
    <div class="mb-3">
        <label for="vehiculo_id">Vehículo:</label>
        <select name="vehiculo_id" id="vehiculo_id" class="form-control" required onchange="actualizarConductor()">
            <option value="">Selecciona un vehículo</option>
            <?php
            $resultVehiculos->data_seek(0); // Reiniciar el puntero
            while ($vehiculo = $resultVehiculos->fetch_assoc()) { ?>
                <option value="<?= htmlspecialchars($vehiculo['id']) ?>" data-conductor="<?= htmlspecialchars($vehiculo['conductor']) ?>">
                    <?= htmlspecialchars($vehiculo['placa']) ?>
                </option>
            <?php } ?>
        </select>
        <span id="conductor" class="text-muted">Conductor: No asignado</span>
    </div>

    <script>
        function actualizarConductor() {
            var select = document.getElementById("vehiculo_id");
            var conductorTexto = select.options[select.selectedIndex].getAttribute('data-conductor');
            document.getElementById("conductor").innerText = conductorTexto ? "Conductor: " + conductorTexto : "Conductor: No asignado";
        }
    </script>

    <!-- Selección de Origen -->
    <div class="mb-3">
        <label for="origen_id">Origen:</label>
        <select name="origen_id" id="origen_id" class="form-control" required>
            <option value="">-- Selecciona un lugar --</option>
            <?php while ($lugar = $resultLugares->fetch_assoc()) { ?>
                <option value="<?= htmlspecialchars($lugar['id']) ?>"><?= htmlspecialchars($lugar['nombre']) ?></option>
            <?php } ?>
        </select>
    </div>

    <!-- Selección de Destino -->
    <div class="mb-3">
        <label for="destino_id">Destino:</label>
        <select name="destino_id" id="destino_id" class="form-control" required>
            <option value="">-- Selecciona un lugar --</option>
            <?php
            $resultLugares->data_seek(0); // Reiniciar el puntero del resultado para reutilizarlo
            while ($lugar = $resultLugares->fetch_assoc()) { ?>
                <option value="<?= htmlspecialchars($lugar['id']) ?>"><?= htmlspecialchars($lugar['nombre']) ?></option>
            <?php } ?>
        </select>
    </div>

    <button type="submit" class="btn btn-success mt-3">🚀 Guardar viaje</button>
</form>
