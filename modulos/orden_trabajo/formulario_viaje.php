<?php
require_once '../../includes/conexion.php';

// Obtener `tipo_ot_id` desde GET
$tipo_ot_id = isset($_GET['tipo_ot_id']) ? intval($_GET['tipo_ot_id']) : 0;

// Consultar vehículos disponibles
$sqlVehiculos = "SELECT v.id, v.placa, CONCAT(c.nombres, ' ', c.apellidos) AS conductor 
                 FROM vehiculos v
                 LEFT JOIN asignaciones_conductor ac ON v.id = ac.vehiculo_id AND ac.activo = 1
                 LEFT JOIN conductores c ON ac.conductor_id = c.id
                 ORDER BY v.placa ASC";
$resultVehiculos = $conn->query($sqlVehiculos);

// Consultar lugares disponibles
$sqlLugares = "SELECT l.id, l.nombre, d.nombre AS distrito 
               FROM lugares l 
               LEFT JOIN distritos d ON l.distrito_id = d.id 
               ORDER BY l.nombre ASC";
$resultLugares = $conn->query($sqlLugares);
?>

<form method="POST" action="registrar_viaje.php">
    <!-- Se envían los identificadores necesarios como campos ocultos -->
    <input type="hidden" name="orden_trabajo_id" value="<?= htmlspecialchars($_GET['orden_trabajo_id']) ?>">
    <input type="hidden" name="tipo_ot_id" value="<?= $tipo_ot_id ?>">

    <!-- Fecha del viaje -->
    <div class="mb-3">
        <label for="fecha_salida">Fecha del viaje:</label>
        <input type="date" name="fecha_salida" id="fecha_salida" class="form-control w-25" required value="<?= date('Y-m-d') ?>">
    </div>

    <!-- Selección de vehículo -->
    <div class="mb-3 d-flex align-items-center">
        <label for="vehiculo_id" class="me-2">Vehículo:</label>
        <select name="vehiculo_id" id="vehiculo_id" class="form-control" style="width: 200px;" required onchange="actualizarConductor()">
            <option value="">Selecciona un vehículo</option>
            <?php while ($vehiculo = $resultVehiculos->fetch_assoc()) { ?>
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
    <div class="mb-3 d-flex align-items-center">
        <label for="origen_id" class="me-2">Origen:</label>
        <select name="origen_id" id="origen_id" class="form-control w-25 me-2" required onchange="actualizarDistrito('origen_id', 'distrito_origen')">
            <option value="">-- Selecciona un lugar --</option>
            <?php while ($lugar = $resultLugares->fetch_assoc()) { ?>
                <option value="<?= htmlspecialchars($lugar['id']) ?>" data-distrito="<?= htmlspecialchars($lugar['distrito']) ?>">
                    <?= htmlspecialchars($lugar['nombre']) ?> (<?= htmlspecialchars($lugar['distrito']) ?>)
                </option>
            <?php } ?>
        </select>
        <span id="distrito_origen" class="text-muted">Distrito: No seleccionado</span>
    </div>

    <!-- Selección de Destino -->
    <div class="mb-3 d-flex align-items-center">
        <label for="destino_id" class="me-2">Destino:</label>
        <select name="destino_id" id="destino_id" class="form-control w-25 me-2" required onchange="actualizarDistrito('destino_id', 'distrito_destino')">
            <option value="">-- Selecciona un lugar --</option>
            <?php
            $resultLugares->data_seek(0);
            while ($lugar = $resultLugares->fetch_assoc()) { ?>
                <option value="<?= htmlspecialchars($lugar['id']) ?>" data-distrito="<?= htmlspecialchars($lugar['distrito']) ?>">
                    <?= htmlspecialchars($lugar['nombre']) ?> (<?= htmlspecialchars($lugar['distrito']) ?>)
                </option>
            <?php } ?>
        </select>
        <span id="distrito_destino" class="text-muted">Distrito: No seleccionado</span>
    </div>

    <script>
        function actualizarDistrito(idSelect, idDistrito) {
            var select = document.getElementById(idSelect);
            var distritoTexto = select.options[select.selectedIndex].getAttribute('data-distrito');
            document.getElementById(idDistrito).innerText = distritoTexto ? "Distrito: " + distritoTexto : "Distrito: No seleccionado";
        }
    </script>

    <!-- Se han eliminado los campos: Orden de Cliente y el contenedor de campos dinámicos (DAM, Booking y Otros) -->
    
    <button type="submit" class="btn btn-success">🚀 Guardar viaje</button>
</form>
