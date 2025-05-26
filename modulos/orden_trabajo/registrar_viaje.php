<?php
// registrar_viaje.php
// Este archivo procesa el registro de un viaje.
// Requiere que exista el archivo de conexión, que defina la variable $conn.
require_once '../../includes/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recuperar datos enviados desde el formulario (formulario_viaje.php)
    // NOTA: El formulario envía "orden_trabajo_id" (este dato se utiliza para buscar la asignación de vehículo)
    $ordenTrabajoID = isset($_POST['orden_trabajo_id']) ? intval($_POST['orden_trabajo_id']) : 0;
    $vehiculoID     = isset($_POST['vehiculo_id']) ? intval($_POST['vehiculo_id']) : 0;
    $fechaSalida    = isset($_POST['fecha_salida']) ? $_POST['fecha_salida'] : '';
    $origenID       = isset($_POST['origen_id']) ? intval($_POST['origen_id']) : 0;
    $destinoID      = isset($_POST['destino_id']) ? intval($_POST['destino_id']) : 0;

    // Establecer valores predeterminados para campos no enviados en el formulario
    // (Estos valores pueden modificarse según las necesidades del sistema)
    $fechaLlegada  = $fechaSalida; // Para efectos de prueba, usamos la misma fecha de salida
    $semanaViaje   = date('W', strtotime($fechaSalida));
    $distanciaKM   = 0;           // Valor por defecto o calculado previamente (aquí lo ponemos en 0)
    $chofer        = "No asignado"; // Valor por defecto. (La información del conductor se obtiene de otra forma en el formulario)
    $estado        = "Programado";  // Estado inicial del viaje
    $observaciones = "";            // Sin observaciones al iniciar

    // Validar que los campos esenciales no estén vacíos o inválidos
    if ($ordenTrabajoID <= 0 || $vehiculoID <= 0 || empty($fechaSalida) || $origenID <= 0 || $destinoID <= 0) {
        die("❌ Error: Datos incompletos.");
    }

    // PASO 1: Recuperar el ID de 'ordenes_vehiculo' que asocia esta OT con el vehículo.
    // La tabla "ordenes_vehiculo" contiene la columna "orden_trabajo_id" y "vehiculo_id".
    $sqlSelect = "SELECT id FROM ordenes_vehiculo WHERE orden_trabajo_id = ? AND vehiculo_id = ?";
    $stmtSelect = $conn->prepare($sqlSelect);
    if (!$stmtSelect) {
        die("❌ Error al preparar la consulta SELECT: " . $conn->error);
    }
    $stmtSelect->bind_param("ii", $ordenTrabajoID, $vehiculoID);
    $stmtSelect->execute();
    $resultSelect = $stmtSelect->get_result();

    if ($resultSelect->num_rows === 0) {
        die("❌ Error: No se encontró la asignación de vehículo para la OT dada.");
    }

    $row = $resultSelect->fetch_assoc();
    $ordenVehiculoID = $row['id'];
    $stmtSelect->close();

    // PASO 2: Preparar la consulta INSERT para la tabla "viajes_orden"
    // Las columnas a insertar son:
    // orden_vehiculo_id, fecha_salida, fecha_llegada, semana_viaje, distancia_km,
    // chofer, estado, observaciones, origen_id, destino_id
    $sqlInsert = "INSERT INTO viajes_orden (
                    orden_vehiculo_id, fecha_salida, fecha_llegada, semana_viaje, 
                    distancia_km, chofer, estado, observaciones, origen_id, destino_id
                  ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmtInsert = $conn->prepare($sqlInsert);
    if (!$stmtInsert) {
        die("❌ Error al preparar la consulta INSERT: " . $conn->error);
    }
    
    // Enlazamos los parámetros. Los tipos son:
    // "i" → integer, "s" → string.
    // Orden de los parámetros:
    // 1. orden_vehiculo_id (integer)
    // 2. fecha_salida (string)
    // 3. fecha_llegada (string)
    // 4. semana_viaje (integer)
    // 5. distancia_km (integer)
    // 6. chofer (string)
    // 7. estado (string)
    // 8. observaciones (string)
    // 9. origen_id (integer)
    // 10. destino_id (integer)
    $stmtInsert->bind_param("ississssii", 
        $ordenVehiculoID, 
        $fechaSalida, 
        $fechaLlegada, 
        $semanaViaje, 
        $distanciaKM, 
        $chofer, 
        $estado, 
        $observaciones, 
        $origenID, 
        $destinoID
    );

    // Ejecutar la consulta INSERT y comprobar el resultado
    if ($stmtInsert->execute()) {
        echo "✅ Viaje registrado correctamente.";
    } else {
        echo "❌ Error al registrar el viaje: " . $stmtInsert->error;
    }
    $stmtInsert->close();

} else {
    echo "❌ Método no permitido.";
}
?>
