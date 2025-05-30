<?php
session_start();
require_once '../../includes/conexion.php'; // Conectar a la base de datos

// ACTIVAR VISUALIZACIÓN DE ERRORES
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verificar que el usuario sea administrador
if (!isset($_SESSION['usuario']) || !isset($_SESSION['rol_nombre']) || $_SESSION['rol_nombre'] !== 'admin') {
    header("Location: ../sistema/login.php");
    exit();
}

// Función de respuesta JSON para solicitudes AJAX
function respondJSON($success, $message) {
    header('Content-Type: application/json');
    echo json_encode(["success" => $success, "message" => $message]);
    exit();
}

// Detectamos si la solicitud es AJAX
$isAjax = false;
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    $isAjax = true;
}

// OBTENER EL ID DEL ESTADO "ACTIVO" (para asignaciones)
$sql_estado_activo = "SELECT id FROM estado_asignacion WHERE nombre = 'activo'";
$result_estado_activo = $conn->query($sql_estado_activo);
if (!$result_estado_activo || $result_estado_activo->num_rows === 0) {
    if ($isAjax) {
        respondJSON(false, "No se encontró el estado 'activo' en estado_asignacion.");
    } else {
        die("No se encontró el estado 'activo' en estado_asignacion.");
    }
}
$row_estado_activo = $result_estado_activo->fetch_assoc();
$estado_id_activo = $row_estado_activo['id'];

// Recuperar lista de vehículos
$vehicles = [];
$sql_vehiculos = "SELECT id, placa, modelo FROM vehiculos ORDER BY placa ASC";
$result_vehiculos = $conn->query($sql_vehiculos);
if ($result_vehiculos) {
    while ($row = $result_vehiculos->fetch_assoc()) {
        $vehicles[] = $row;
    }
} else {
    if ($isAjax) {
        respondJSON(false, "Error al obtener vehículos: " . $conn->error);
    } else {
        die("Error al obtener vehículos: " . $conn->error);
    }
}

// Recuperar lista de conductores
$conductores = [];
$sql_conductores = "SELECT id, nombres, apellidos FROM conductores ORDER BY nombres ASC";
$result_conductores = $conn->query($sql_conductores);
if ($result_conductores) {
    while ($row = $result_conductores->fetch_assoc()) {
        $conductores[] = $row;
    }
} else {
    if ($isAjax) {
        respondJSON(false, "Error al obtener conductores: " . $conn->error);
    } else {
        die("Error al obtener conductores: " . $conn->error);
    }
}

// Procesamiento del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Se espera recibir vehiculo_id y conductor_id vía POST
    $vehiculo_id = isset($_POST['vehiculo_id']) ? intval($_POST['vehiculo_id']) : 0;
    $conductor_id = isset($_POST['conductor_id']) ? intval($_POST['conductor_id']) : 0;
    
    if ($vehiculo_id <= 0 || $conductor_id <= 0) {
        if ($isAjax) {
            respondJSON(false, "Debes seleccionar un vehículo y un conductor válidos.");
        } else {
            $error = "Debes seleccionar un vehículo y un conductor válidos.";
        }
    } else {
        // Validación: Verificar si el vehículo ya está asignado con estado activo
        $sql_check_vehicle = "SELECT id FROM asignaciones_conductor WHERE vehiculo_id = ? AND estado_id = ?";
        $stmt_check_vehicle = $conn->prepare($sql_check_vehicle);
        $stmt_check_vehicle->bind_param("ii", $vehiculo_id, $estado_id_activo);
        $stmt_check_vehicle->execute();
        $result_check_vehicle = $stmt_check_vehicle->get_result();
        if ($result_check_vehicle->num_rows > 0) {
            if ($isAjax) {
                respondJSON(false, "El vehículo ya está asignado a otro conductor.");
            } else {
                $error = "El vehículo ya está asignado a otro conductor.";
            }
        }
        $stmt_check_vehicle->close();
        
        // Validación: Verificar si el conductor ya tiene una asignación activa
        $sql_check_conductor = "SELECT id FROM asignaciones_conductor WHERE conductor_id = ? AND estado_id = ?";
        $stmt_check_conductor = $conn->prepare($sql_check_conductor);
        $stmt_check_conductor->bind_param("ii", $conductor_id, $estado_id_activo);
        $stmt_check_conductor->execute();
        $result_check_conductor = $stmt_check_conductor->get_result();
        if ($result_check_conductor->num_rows > 0) {
            if ($isAjax) {
                respondJSON(false, "El conductor ya tiene asignada una unidad.");
            } else {
                $error = "El conductor ya tiene asignada una unidad.";
            }
        }
        $stmt_check_conductor->close();
        
        // Si no hubo errores en validaciones, proceder con la inserción
        if (!isset($error)) {
            $stmt = $conn->prepare("INSERT INTO asignaciones_conductor (vehiculo_id, conductor_id, fecha_inicio, estado_id) VALUES (?, ?, NOW(), ?)");
            if ($stmt) {
                $stmt->bind_param("iii", $vehiculo_id, $conductor_id, $estado_id_activo);
                if ($stmt->execute()) {
                    if ($isAjax) {
                        respondJSON(true, "Asignación registrada con éxito.");
                    } else {
                        header("Location: asignaciones.php");
                        exit();
                    }
                } else {
                    if ($isAjax) {
                        respondJSON(false, "Error al insertar la asignación: " . $stmt->error);
                    } else {
                        $error = "Error al insertar la asignación: " . $stmt->error;
                    }
                }
                $stmt->close();
            } else {
                if ($isAjax) {
                    respondJSON(false, "Error en la preparación de la consulta: " . $conn->error);
                } else {
                    $error = "Error en la preparación de la consulta: " . $conn->error;
                }
            }
        }
    }
    // En el caso no sea AJAX, se sigue mostrando el formulario con mensaje de error
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Asignar Vehículo a Conductor</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- CSS Personalizado -->
    <link rel="stylesheet" href="../../css/asignaciones.css">
    <!-- FontAwesome para íconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- (Opcional) Select2 para mejorar los dropdowns -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>
<body>
<div class="container my-5">
    <h1 class="mb-4">Asignar Vehículo a Conductor</h1>
    <a href="asignaciones.php" class="btn btn-secondary mb-3">
        <i class="fas fa-arrow-left"></i> Volver a Asignaciones
    </a>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- El formulario tiene id="asignarForm" para usar AJAX -->
    <form id="asignarForm" action="asignar_conductor.php" method="post" class="needs-validation" novalidate>
        <div class="mb-3">
            <label for="vehiculo_id" class="form-label">Vehículo</label>
            <select name="vehiculo_id" id="vehiculo_id" class="form-select" required>
                <option value="">Seleccione un vehículo</option>
                <?php foreach ($vehicles as $vehiculo): ?>
                    <option value="<?= $vehiculo['id'] ?>">
                        <?= htmlspecialchars($vehiculo['placa'] . ' (' . $vehiculo['modelo'] . ')') ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <div class="invalid-feedback">Por favor, seleccione un vehículo.</div>
        </div>
        <div class="mb-3">
            <label for="conductor_id" class="form-label">Conductor</label>
            <select name="conductor_id" id="conductor_id" class="form-select" required>
                <option value="">Seleccione un conductor</option>
                <?php foreach ($conductores as $conductor): ?>
                    <option value="<?= $conductor['id'] ?>">
                        <?= htmlspecialchars($conductor['nombres'] . ' ' . $conductor['apellidos']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <div class="invalid-feedback">Por favor, seleccione un conductor.</div>
        </div>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Registrar Asignación
        </button>
    </form>
</div>

<!-- jQuery (Para AJAX y Select2) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<!-- Bootstrap JS Bundle (incluye Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- (Opcional) Select2 JS para mejorar los select -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function(){
    // (Opcional) Inicializar Select2 para los dropdowns
    $('#vehiculo_id, #conductor_id').select2({
        width: '100%',
        placeholder: "Seleccione una opción"
    });

    // Interceptar envío del formulario para enviarlo vía AJAX
    $("#asignarForm").on("submit", function(e){
        e.preventDefault();
        var form = $(this);

        // Si la validación HTML5 falla o Bootstrap la detecta, no hacemos AJAX
        if (this.checkValidity() === false) {
            e.stopPropagation();
            form.addClass("was-validated");
            return;
        }
        // Enviar el formulario vía AJAX
        $.ajax({
            type: form.attr("method"),
            url: form.attr("action"),
            data: form.serialize(),
            dataType: "json"
        }).done(function(response) {
            if(response.success) {
                // Mostrar notificación (puedes usar un toast de Bootstrap en lugar de alert)
                alert(response.message);
                // Redirigir luego de un breve retardo o actualizar la página
                window.location.href = "asignaciones.php";
            } else {
                alert(response.message);
            }
        }).fail(function(){
            alert("Error en la solicitud. Intente nuevamente.");
        });
    });
});
</script>
</body>
</html>
