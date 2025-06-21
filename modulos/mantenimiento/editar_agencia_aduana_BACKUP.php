<?php
	// Habilitar visualización de errores (solo en desarrollo)
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	session_start();
	require_once __DIR__ . '/../../includes/conexion.php';

	// Verificar conexión (esto puede removerse en producción)
	if (!$conn) {
	    die("Error en la conexión: " . mysqli_connect_error());
		}

	// Acceso restringido solo a administradores
	if (empty($_SESSION['usuario']) || $_SESSION['rol_nombre'] !== 'admin') {
	    header('Location: ../sistema/login.php');
	    exit;
		}

	// Procesamiento de acciones: Inserción, Edición y Eliminación

	// 1. Procesar la inserción de una nueva agencia aduana
	if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar'])) {

		// recoge de forma segura cada índice o ponle 0
    	$departamento_id = isset($_POST['departamento_id']) 
            			? (int) $_POST['departamento_id'] 
                    	: 0;
    	$provincia_id   = isset($_POST['provincia_id']) 
                       	? (int) $_POST['provincia_id'] 
                       	: 0;
    	$distrito_id    = isset($_POST['distrito_id']) 
                       	? (int) $_POST['distrito_id'] 
                       	: 0;

    	$nombre           = trim($_POST['nombre']);
    	$ruc              = trim($_POST['ruc']);
    	$direccion        = trim($_POST['direccion']);
    	$telefono         = trim($_POST['telefono']);
    	$correo_general   = trim($_POST['correo_general']);
    	$contacto         = trim($_POST['contacto']);

    // Validación mínima: se valida que el campo "nombre" y que los IDs sean numéricos 



	
    if (empty($nombre)) {
        $error = "El nombre es obligatorio.";
    }
    if (!is_numeric($distrito_id) || !is_numeric($provincia_id) || !is_numeric($departamento_id)) {
        $error = "Distrito, Provincia y Departamento deben ser valores numéricos.";
    }

    if (!isset($error)) {
        $stmtInsert = $conn->prepare("INSERT INTO agencias_aduanas (nombre, ruc, direccion, distrito_id, provincia_id, departamento_id, telefono, correo_general, contacto) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if (!$stmtInsert) {
            die("Error en la preparación para insertar: " . $conn->error);
        }
        $stmtInsert->bind_param("sssiiisss", $nombre, $ruc, $direccion, $distrito_id, $provincia_id, $departamento_id, $telefono, $correo_general, $contacto);
        if ($stmtInsert->execute()) {
            header("Location: editar_agencia_aduana.php?msg=agregado");
            exit;
        } else {
            $error = "Error al agregar la agencia aduana: " . $conn->error;
        }
    }
}

// 2. Procesar la edición de un registro
if (isset($_GET['editar'])) {
    $id = (int) $_GET['editar'];

    // Preparar consulta para obtener el registro a editar
    $stmt = $conn->prepare("SELECT * FROM agencias_aduanas WHERE id = ?");
    if (!$stmt) {
        die("Error en la preparación: " . $conn->error);
    }
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();

    // Si no se encuentra el registro, redirigir a la lista
    if ($resultado->num_rows === 0) {
        header("Location: editar_agencia_aduana.php");
        exit;
    }

    $registro = $resultado->fetch_assoc();

    // Si se ha enviado el formulario de actualización
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nombre           = trim($_POST['nombre']);
        $ruc              = trim($_POST['ruc']);
        $direccion        = trim($_POST['direccion']);
        $distrito_id      = trim($_POST['distrito_id']);
        $provincia_id     = trim($_POST['provincia_id']);
        $departamento_id  = trim($_POST['departamento_id']);
        $telefono         = trim($_POST['telefono']);
        $correo_general   = trim($_POST['correo_general']);
        $contacto         = trim($_POST['contacto']);


		// validaciones
    	if ($departamento_id <= 0 || $provincia_id <= 0 || $distrito_id <= 0) {
        	$error = "Debes elegir un Departamento, una Provincia y un Distrito válidos.";
    	}

        if (empty($nombre)) {
            $error = "El nombre es obligatorio.";
        }
        if (!is_numeric($distrito_id) || !is_numeric($provincia_id) || !is_numeric($departamento_id)) {
            $error = "Distrito, Provincia y Departamento deben ser valores numéricos.";
        }
        if (!isset($error)) {
            $stmtUpdate = $conn->prepare("UPDATE agencias_aduanas SET nombre = ?, ruc = ?, direccion = ?, distrito_id = ?, provincia_id = ?, departamento_id = ?, telefono = ?, correo_general = ?, contacto = ? WHERE id = ?");
            if (!$stmtUpdate) {
                die("Error en la preparación para actualizar: " . $conn->error);
            }
            $stmtUpdate->bind_param("sssiiisssi", $nombre, $ruc, $direccion, $distrito_id, $provincia_id, $departamento_id, $telefono, $correo_general, $contacto, $id);
            if ($stmtUpdate->execute()) {
                header("Location: editar_agencia_aduana.php?msg=actualizado");
                exit;
            } else {
                $error = "Error al actualizar el registro.";
            }
        }
    }
    ?>
    <!-- <!DOCTYPE html>
    <html lang="es">
    <head>
         <meta charset="UTF-8">
         <meta name="viewport" content="width=device-width, initial-scale=1">
         <title>Editar Agencia Aduana – Global Transportes</title>
         <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
         <link rel="stylesheet" href="../css/base.css">
         <link rel="stylesheet" href="../css/dashboard.css">
    </head>
    <body class="bg-light">
         <header class="dashboard-header bg-white shadow-sm py-3">
              <div class="container d-flex align-items-center justify-content-between">
                   <h1 class="h4 mb-0">📦 Editar Agencia Aduana</h1>
                   <a href="editar_agencia_aduana.php" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Volver
                   </a>
              </div>
         </header>
         <main class="container py-4">
              <?php if (isset($error)): ?>
                   <div class="alert alert-danger"><?php echo $error; ?></div>
              <?php endif; ?>
              <form method="post" action="editar_agencia_aduana.php?editar=<?php echo $id; ?>">
                    <div class="mb-3">
                         <label for="nombre" class="form-label">Nombre</label>
                         <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($registro['nombre']); ?>" required>
                    </div>
                    <div class="mb-3">
                         <label for="ruc" class="form-label">RUC</label>
                         <input type="text" class="form-control" id="ruc" name="ruc" value="<?php echo htmlspecialchars($registro['ruc']); ?>" required>
                    </div>
                    <div class="mb-3">
                         <label for="direccion" class="form-label">Dirección</label>
                         <input type="text" class="form-control" id="direccion" name="direccion" value="<?php echo htmlspecialchars($registro['direccion']); ?>" required>
                    </div>
                    <div class="mb-3">
                         <label for="distrito_id" class="form-label">Distrito ID</label>
                         <input type="number" class="form-control" id="distrito_id" name="distrito_id" value="<?php echo htmlspecialchars($registro['distrito_id']); ?>" required>
                    </div>
                    <div class="mb-3">
                         <label for="provincia_id" class="form-label">Provincia ID</label>
                         <input type="number" class="form-control" id="provincia_id" name="provincia_id" value="<?php echo htmlspecialchars($registro['provincia_id']); ?>" required>
                    </div>
                    <div class="mb-3">
                         <label for="departamento_id" class="form-label">Departamento ID</label>
                         <input type="number" class="form-control" id="departamento_id" name="departamento_id" value="<?php echo htmlspecialchars($registro['departamento_id']); ?>" required>
                    </div>
                    <div class="mb-3">
                         <label for="telefono" class="form-label">Teléfono</label>
                         <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo htmlspecialchars($registro['telefono']); ?>">
                    </div>
                    <div class="mb-3">
                         <label for="correo_general" class="form-label">Correo General</label>
                         <input type="email" class="form-control" id="correo_general" name="correo_general" value="<?php echo htmlspecialchars($registro['correo_general']); ?>">
                    </div>
                    <div class="mb-3">
                         <label for="contacto" class="form-label">Contacto</label>
                         <textarea class="form-control" id="contacto" name="contacto" rows="4"><?php echo htmlspecialchars($registro['contacto']); ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                    <a href="editar_agencia_aduana.php" class="btn btn-secondary">Cancelar</a>
              </form>
         </main>
         <footer class="footer bg-white text-center py-3 mt-auto">
              <div class="container">
                   <small class="text-muted">&copy; 2025 Global Transportes. Todos los derechos reservados.</small>
              </div>
         </footer>
         <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html> -->
    <?php
    exit;
} // Fin de acción "editar"

// 3. Procesar la eliminación de un registro
if (isset($_GET['eliminar'])) {
    $id = (int) $_GET['eliminar'];
    $stmtDel = $conn->prepare("DELETE FROM agencias_aduanas WHERE id = ?");
    if (!$stmtDel) {
         die("Error en la preparación para eliminar: " . $conn->error);
    }
    $stmtDel->bind_param("i", $id);
    if ($stmtDel->execute()) {
         header("Location: editar_agencia_aduana.php?msg=eliminado");
         exit;
    } else {
         $error = "Error al eliminar el registro.";
    }
}
?>



<!DOCTYPE html>
<html lang="es">
<head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Editar Agencia Aduana – Global Transportes</title>
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
      <link rel="stylesheet" href="../css/base.css">
      <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body class="bg-light">
      <header class="dashboard-header bg-white shadow-sm py-3">
            <div class="container d-flex align-items-center justify-content-between">
                  <h1 class="h4 mb-0">📦 Editar Agencia Aduana</h1>
                  <a href="../mantenimiento/mantenimiento.php" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Volver al Mantenimiento
                  </a>
            </div>
      </header>
      <main class="container py-4">
            <?php
            // Mostrar mensajes de éxito según la acción realizada
            if (isset($_GET['msg'])) {
                  if ($_GET['msg'] === 'actualizado') {
                        echo "<div class='alert alert-success'>Registro actualizado correctamente.</div>";
                  } elseif ($_GET['msg'] === 'eliminado') {
                        echo "<div class='alert alert-success'>Registro eliminado correctamente.</div>";
                  } elseif ($_GET['msg'] === 'agregado') {
                        echo "<div class='alert alert-success'>Nueva agencia aduana agregada correctamente.</div>";
                  }
            }
            if (isset($error)) {
                  echo "<div class='alert alert-danger'>{$error}</div>";
            }
            ?>

            <!-- Formulario para agregar una nueva agencia aduana -->
            <h2 class="h5 mb-4">Agregar Nueva Agencia Aduana</h2>
            <form method="post" action="editar_agencia_aduana.php">
                
				<div class="mb-3 row align-items-center">
                	<label for="nombre" class="col-sm-2 col-form-label">Nombre</label>
  					<div class="col-sm-8">
    					<input type="text" class="form-control" id="nombre" name="nombre">
  					</div>
				</div>

                <div class="mb-3 row align-items-center">
  					<label for="ruc" class="col-sm-2 col-form-label">RUC</label>
  					<div class="col-sm-8">
    					<input type="text" class="form-control" id="ruc" name="ruc">
  					</div>
				</div>

                <div class="mb-3 row align-items-center">
  					<label for="direccion" class="col-sm-2 col-form-label">Dirección</label>
  					<div class="col-sm-8">
    					<input type="text" class="form-control" id="direccion" name="direccion">
  					</div>
				</div>

				<!-- Desplegable para elegir el Departamento -->
				<div class="mb-3 row align-items-center">
  					<label for="departamento" class="col-sm-2 col-form-label">Departamento:</label>
  					<div class="col-sm-4">
    					<select name="departamento" id="departamento" class="form-control" required>
      						<option value="">Elija un departamento</option>
      						<?php
      							// Consulta para obtener todos los departamentos desde la tabla "departamentos"
      							$query = "SELECT id, nombre FROM departamentos ORDER BY nombre ASC";
      							$result = $conn->query($query);
      							if ($result) {
          							while ($row = $result->fetch_assoc()) {
              							echo '<option value="' . $row['id'] . '">' . htmlspecialchars($row['nombre']) . '</option>';
          								}
      								}
      						?>
    					</select>
  					</div>
				</div>

				<!-- Desplegable para elegir la Provincia (cargado dinámicamente) -->
				<div class="mb-3 row align-items-center">
  					<label for="provincia" class="col-sm-2 col-form-label">Provincia:</label>
  					<div class="col-sm-4">
    					<select name="provincia" id="provincia" class="form-control" required disabled>
     	 					<option value="">Elija una provincia</option>
    					</select>
  					</div>
				</div>


                  <!-- Select de Distritos (a cargar dinámicamente) -->
				<div class="mb-3 row align-items-center">
  					<label for="distrito" class="col-sm-2 col-form-label">Distrito:</label>
  					<div class="col-sm-4">
    					<select name="distrito" id="distrito" class="form-control" required disabled>
      						<option value="">Elija un distrito</option>
    					</select>
  					</div>
				</div>
                  

                  <!-- Ejemplo con el sistema de grillas de Bootstrap para colocar el label y el select en una línea -->
				
                  
				<div class="mb-3 row align-items-center">
  					<label for="telefono" class="col-sm-2 col-form-label">Teléfono</label>
  					<div class="col-sm-8">
    					<input type="text" class="form-control" id="telefono" name="telefono">
  					</div>
				</div>

                 
				  <div class="mb-3">
                        <label for="correo_general" class="form-label">Correo General</label>
                        <input type="email" class="form-control" id="correo_general" name="correo_general">
                  </div>
                  <div class="mb-3">
                        <label for="contacto" class="form-label">Contacto</label>
                        <textarea class="form-control" id="contacto" name="contacto" rows="4"></textarea>
                  </div>
                  <button type="submit" name="agregar" class="btn btn-success">Agregar Agencia Aduana</button>
            </form>
            <hr>
            <div class="table-responsive">
                  <?php
                  $resultado = $conn->query("SELECT * FROM agencias_aduanas");
                  if (!$resultado) {
                        die("Error en la consulta: " . $conn->error);
                  }
                  echo "<p>Número de registros encontrados: " . $resultado->num_rows . "</p>";
                  ?>
                  <table class="table table-striped">
                        <thead>
                              <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>RUC</th>
                                    <th>Dirección</th>
                                    <th>Distrito ID</th>
                                    <th>Provincia ID</th>
                                    <th>Departamento ID</th>
                                    <th>Teléfono</th>
                                    <th>Correo General</th>
                                    <th>Contacto</th>
                                    <th>Acciones</th>
                              </tr>
                        </thead>
                        <tbody>
                              <?php 
                              if ($resultado->num_rows === 0) {
                                    echo "<tr><td colspan='11'>No se encontraron registros en la tabla.</td></tr>";
                              } else {
                                    while ($row = $resultado->fetch_assoc()):
                              ?>
                              <tr>
                                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($row['ruc']); ?></td>
                                    <td><?php echo htmlspecialchars($row['direccion']); ?></td>
                                    <td><?php echo htmlspecialchars($row['distrito_id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['provincia_id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['departamento_id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['telefono']); ?></td>
                                    <td><?php echo htmlspecialchars($row['correo_general']); ?></td>
                                    <td><?php echo htmlspecialchars($row['contacto']); ?></td>
                                    <td>
                                          <a class="btn btn-sm btn-warning me-2" href="editar_agencia_aduana.php?editar=<?php echo $row['id']; ?>">🔄 Modificar</a>
                                          <a class="btn btn-sm btn-danger" href="editar_agencia_aduana.php?eliminar=<?php echo $row['id']; ?>" onclick="return confirm('¿Estás seguro de eliminar este registro?')">🗑 Eliminar</a>
                                    </td>
                              </tr>
                              <?php endwhile; } ?>
                        </tbody>
                  </table>
            </div>
      </main>
      <footer class="footer bg-white text-center py-3 mt-auto">
            <div class="container">
                  <small class="text-muted">&copy; 2025 Global Transportes. Todos los derechos reservados.</small>
            </div>
      </footer>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


<!-- ... El HTML de tu formulario ... -->

<!-- Incluir jQuery (si aún no lo has hecho) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
$(document).ready(function(){
  $("#departamento").on("change", function(){
    var departamentoId = $(this).val();
    if (departamentoId) {
      $.ajax({
        type: "POST",
        url: "get_provincias.php", // Asegúrate que esta ruta es correcta
        data: { departamento_id: departamentoId },
        success: function(html) {
          $("#provincia").html(html);
          $("#provincia").prop("disabled", false);
        },
        error: function() {
          $("#provincia").html('<option value="">Error al cargar provincias</option>');
        }
      });
    } else {
      $("#provincia").html('<option value="">Elija una provincia</option>');
      $("#provincia").prop("disabled", true);
    }
  });
});
</script>

<!-- Incluir jQuery si aún no está incluido -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
$(document).ready(function(){
  $("#provincia").on("change", function(){
    var provinciaId = $(this).val();
    if (provinciaId) {
      $.ajax({
        type: "POST",
        url: "get_distritos.php", // Asegúrate de que la ruta sea correcta
        data: { provincia_id: provinciaId },
        success: function(html) {
          $("#distrito").html(html);
          $("#distrito").prop("disabled", false);
        },
        error: function() {
          $("#distrito").html('<option value="">Error al cargar distritos</option>');
        }
      });
    } else {
      $("#distrito").html('<option value="">Elija un distrito</option>');
      $("#distrito").prop("disabled", true);
    }
  });
});
</script>


</body>
</html>




	</body>
</html>



