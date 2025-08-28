<?php
// Iniciar salida en buffer para controlar cabeceras y limpiar la salida previa
ob_start();
require_once '../../includes/conexion.php';
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);


$conn = getConnection();

$usuarioId = isset($_SESSION['usuario']['id']) ? (int)$_SESSION['usuario']['id'] : 0;


	// Convertir texto a mayúsculas (salvo el campo "observaciones")
	$placa            = strtoupper(trim($_POST['placa']));
	$tipo_id          = intval($_POST['tipo_id']);
	$marca_id         = intval($_POST['marca_id']);	
	$estado_id        = intval($_POST['estado_id']);
	$configuracion_id = 1;
	$modelo           = strtoupper(trim($_POST['modelo']));
	$anio             = intval($_POST['anio']);
	$empresa_id       = intval($_POST['empresa_id']);
	$observaciones    = isset($_POST['observaciones'])
                    	? trim($_POST['observaciones'])
    					: null;

	// Primero, verificar si la placa ya existe en la base de datos
	$dup_sql = "SELECT id FROM vehiculos WHERE placa = ?";
	$dup_stmt = $conn->prepare($dup_sql);
	if (!$dup_stmt) {
	    $respuesta = [
	        'success' => false,
	        'message' => "❌ Error preparando consulta para duplicados: " . $conn->error
			];
    	mostrar_modal($respuesta);
    	exit();
		}
	$dup_stmt->bind_param("s", $placa);
	$dup_stmt->execute();
	$dup_stmt->store_result();

	if ($dup_stmt->num_rows > 0) {
    	$respuesta = [
    	    'success' => false,
    	    'message' => "❌ La placa ya existe."
    		];
    	$dup_stmt->close();
    	// Redirigir al formulario para vehículos en caso de duplicidad
    	mostrar_modal($respuesta, "form_vehiculos.php");
    	exit();
		}
	$dup_stmt->close();

	// Preparar la consulta de inserción
	$sql = 
		"INSERT INTO vehiculos (
        	placa, tipo_id, marca_id, estado_id, configuracion_id, modelo, anio, empresa_id, observaciones,
        	creado_por, fecha_creado) 
		VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
	if (!$stmt) {
    	$respuesta = [
    	    'success' => false,
        	'message' => "❌ Error preparando la consulta: " . $conn->error
    		];
    	mostrar_modal($respuesta);
    	exit();
		}

	$stmt->bind_param(
	    "siiissssii", 
    $placa, 
    $tipo_id, 
    	$marca_id, 
    	$estado_id, 
    	$configuracion_id, 
    	$modelo, 
    	$anio, 
    	$empresa_id, 
    	$observaciones,
    	$usuarioId
		);

if ($stmt->execute()) {
    $respuesta = [
        'success' => true,
        'message' => "✅ Registro exitoso en la base de datos."
    ];
} else {
    $respuesta = [
        'success' => false,
        'message' => "❌ Error al insertar el vehículo: " . $stmt->error
    ];
}
$stmt->close();

/**
 * Función para mostrar un modal de Bootstrap con el resultado y redirigir
 * @param array $respuesta Arreglo con las claves 'success' (bool) y 'message' (string)
 * @param string $redir URL de redirección (por defecto "vehiculos.php")
 */
function mostrar_modal($respuesta, $redir = "vehiculos.php") {
    // Limpiar el buffer para que no se envíe contenido previo
    ob_clean();
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Resultado Registro Vehículo</title>
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    </head>
    <body>
    <!-- Modal de Resultado -->
    <div class="modal fade" id="resultadoModal" tabindex="-1" aria-labelledby="resultadoModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header <?php echo $respuesta['success'] ? 'bg-success' : 'bg-danger'; ?>">
            <h5 class="modal-title text-white" id="resultadoModalLabel">
                <?php echo $respuesta['success'] ? '¡Éxito!' : 'Error'; ?>
            </h5>
          </div>
          <div class="modal-body">
            <p><?php echo htmlspecialchars($respuesta['message']); ?></p>
          </div>
          <div class="modal-footer">
            <button type="button" id="cerrarModal" class="btn <?php echo $respuesta['success'] ? 'btn-success' : 'btn-danger'; ?>">
                Cerrar
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Bootstrap JS Bundle (incluye Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function(){
        var resultadoModal = new bootstrap.Modal(document.getElementById('resultadoModal'));
        resultadoModal.show();
        
        document.getElementById('cerrarModal').addEventListener('click', function(){
            window.location.href = "<?php echo $redir; ?>";
        });
        
        // Redirecciona automáticamente después de 3 segundos
        setTimeout(function(){
            window.location.href = "<?php echo $redir; ?>";
        }, 3000);
    });
    </script>
    </body>
    </html>
    <?php
    ob_end_flush();
    exit();
}

// Mostrar resultado final (para inserción exitosa o error en la inserción)
mostrar_modal($respuesta);
?>
