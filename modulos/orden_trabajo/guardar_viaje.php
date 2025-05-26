<?php
session_start();
require_once '../../includes/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ordenID = intval($_POST['orden_trabajo_id']);
    $vehiculoID = intval($_POST['vehiculo_id']);
    $fechaSalida = trim($_POST['fecha_salida']);
    $fechaLlegada = trim($_POST['fecha_llegada']);
    $destino = trim($_POST['destino']);
    $distancia = intval($_POST['distancia_km']);
    $chofer = trim($_POST['chofer']);
    $estado = trim($_POST['estado']);
    $observaciones = trim($_POST['observaciones']);

    // Validación de datos antes de proceder
    if ($ordenID <= 0 || $vehiculoID <= 0 || empty($fechaSalida) || empty($destino) || $distancia <= 0 || empty($chofer) || empty($estado)) {
        header("Location: registrar_viaje.php?orden_trabajo_id=$ordenID&error=❌ Datos incompletos.");
        exit();
    }

    // Calcular la semana del viaje según la fecha de salida
    $semanaViaje = date('W', strtotime($fechaSalida));

    // Obtener el ID correcto de `ordenes_vehiculo` que relaciona la orden y el vehículo
    $stmtBuscarOrdenVehiculo = $conn->prepare("SELECT id FROM ordenes_vehiculo WHERE orden_trabajo_id = ? AND vehiculo_id = ?");
    $stmtBuscarOrdenVehiculo->bind_param("ii", $ordenID, $vehiculoID);
    $stmtBuscarOrdenVehiculo->execute();
    $resultOrdenVehiculo = $stmtBuscarOrdenVehiculo->get_result();

    if ($resultOrdenVehiculo->num_rows === 0) {
        header("Location: registrar_viaje.php?orden_trabajo_id=$ordenID&error=❌ No se encontró el vehículo en la orden.");
        exit();
    }

    $ordenVehiculo = $resultOrdenVehiculo->fetch_assoc();
    $ordenVehiculoID = $ordenVehiculo['id']; // ID correcto de la relación

    // Insertar nuevo viaje en `viajes_orden` usando orden_vehiculo_id (no se inserta orden_trabajo_id)
    $sql = "INSERT INTO viajes_orden (orden_vehiculo_id, fecha_salida, fecha_llegada, semana_viaje, destino, distancia_km, chofer, estado, observaciones) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        // Mostrar el error de la preparación para depuración
        die("❌ Error al preparar la consulta: " . $conn->error);
    }
    
    $stmt->bind_param("iississss", $ordenVehiculoID, $fechaSalida, $fechaLlegada, $semanaViaje, $destino, $distancia, $chofer, $estado, $observaciones);

    if ($stmt->execute()) {
        header("Location: orden_trabajo.php?orden_trabajo_id=$ordenID&success=✅ Viaje registrado correctamente en la semana $semanaViaje.");
        exit();
    } else {
        header("Location: registrar_viaje.php?orden_trabajo_id=$ordenID&error=❌ Error al guardar el viaje.");
        exit();
    }
} else {
    header("Location: registrar_viaje.php?error=Método no permitido.");
    exit();
}
?>
