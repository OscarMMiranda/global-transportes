<?php
	// Habilitar visualización de errores (solo en desarrollo)
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	session_start();
	require_once __DIR__ . '/../../../includes/conexion.php';

	// Verificar conexión (esto puede removerse en producción)
	if (!$conn) 
		{
	    die("Error en la conexión: " . mysqli_connect_error());
		}

	// Acceso restringido solo a administradores
	if (empty($_SESSION['usuario']) || $_SESSION['rol_nombre'] !== 'admin') 
		{
		header('Location: ../../sistema/login.php');
	    exit;
		}

	// -------------------------------------------------------------------------
	// Procesamiento de acciones: Edición, Eliminación y Agregar
	// -------------------------------------------------------------------------

	// Procesar la inserción de nueva mercadería
	if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar'])) 
		{
    	$nombre = trim($_POST['nombre']);
    	$descripcion = trim($_POST['descripcion']);

    	// Validación mínima
    	if (empty($nombre)) 
			{
    	    $error = "El nombre es obligatorio para agregar una nueva mercadería.";
    		}
		if (!isset($error)) 
			{
        	$stmtInsert = $conn->prepare("INSERT INTO tipos_mercaderia (nombre, descripcion) VALUES (?, ?)");
        	if (!$stmtInsert) 
				{
            	die("Error en la preparación para insertar: " . $conn->error);
        		}
			$stmtInsert->bind_param("ss", $nombre, $descripcion);
        	if ($stmtInsert->execute()) {
            	header("Location: editar_tipo_mercaderia.php?msg=agregado");
            	exit;
        		} 
			else 
				{
            	$error = "Error al agregar la mercadería: " . $conn->error;
        		}
    		}
		}

	// Si se solicita editar un registro (por ejemplo: editar_tipo_mercaderia.php?editar=3)
	if (isset($_GET['editar'])) 
		{
    	$id = (int) $_GET['editar'];
    
		// Preparar consulta para obtener el registro a editar
    	$stmt = $conn->prepare("SELECT * FROM tipos_mercaderia WHERE id = ?");
    	if (!$stmt) 
			{
        	die("Error en la preparación: " . $conn->error);
    		}
			$stmt->bind_param("i", $id);
    		$stmt->execute();
    		$resultado = $stmt->get_result();
    
    	// Si no se encuentra el registro, se redirige a la lista
    	if ($resultado->num_rows === 0) 
			{
        	header("Location: editar_tipo_mercaderia.php");
        	exit;
    		}
    
    	$registro = $resultado->fetch_assoc();
    
    	// Si se ha enviado el formulario de actualización
    	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        	$nombre = trim($_POST['nombre']);
        	$descripcion = trim($_POST['descripcion']);
        
        // Validación mínima
        if (empty($nombre)) {
            $error = "El nombre es obligatorio.";
        	}
        
        if (!isset($error)) {
            $stmtUpdate = $conn->prepare("UPDATE tipos_mercaderia SET nombre = ?, descripcion = ? WHERE id = ?");
            if (!$stmtUpdate) {
                die("Error en la preparación para actualizar: " . $conn->error);
            }
            $stmtUpdate->bind_param("ssi", $nombre, $descripcion, $id);
            if ($stmtUpdate->execute()) {
                header("Location: editar_tipo_mercaderia.php?msg=actualizado");
                exit;
            } else {
                $error = "Error al actualizar el registro.";
            }
        }
    }
    ?>
<!DOCTYPE html>
	<html lang="es">
    	<head>
    		<meta charset="UTF-8">
        	<meta name="viewport" content="width=device-width, initial-scale=1">
        	<title>Editar Tipo de Mercadería – Global Transportes</title>
        	<!-- Bootstrap 5 -->
        	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        	<!-- CSS del sistema -->
        	<link rel="stylesheet" href="../css/base.css">
        	<link rel="stylesheet" href="../css/dashboard.css">
    	</head>
    <body class="bg-light">
        <!-- HEADER -->
        <header class="dashboard-header bg-white shadow-sm py-3">
            <div class="container d-flex align-items-center justify-content-between">
                <h1 class="h4 mb-0">📦 Editar Tipo de Mercadería</h1>
                <a href="editar_tipo_mercaderia.php" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Volver
                </a>
            </div>
        </header>
    
        <!-- MAIN -->
        <main class="container py-4">
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            <form method="post" action="editar_tipo_mercaderia.php?editar=<?php echo $id; ?>">
                <div class="mb-3 row align-items-center">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($registro['nombre']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea class="form-control" id="descripcion" name="descripcion" rows="2"><?php echo htmlspecialchars($registro['descripcion']); ?></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Actualizar</button>
                <a href="editar_tipo_mercaderia.php" class="btn btn-secondary">Cancelar</a>
            </form>
        </main>
    
        <!-- FOOTER -->
        <footer class="footer bg-white text-center py-3 mt-auto">
            <div class="container">
                <small class="text-muted">&copy; 2025 Global Transportes. Todos los derechos reservados.</small>
            </div>
        </footer>
    
        <!-- JS de Bootstrap -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>
    <?php
    exit;
} // Fin de acción "editar"

// Si se solicita eliminar un registro (por ejemplo: editar_tipo_mercaderia.php?eliminar=3)
	if (isset($_GET['eliminar'])) {
    	$id = (int) $_GET['eliminar'];
    
    	$stmtDel = $conn->prepare("DELETE FROM tipos_mercaderia WHERE id = ?");
    	if (!$stmtDel) {
        	die("Error en la preparación para eliminar: " . $conn->error);
        }
    	$stmtDel->bind_param("i", $id);
    	if ($stmtDel->execute()) {
        	header("Location: editar_tipo_mercaderia.php?msg=eliminado");
        	exit;
    		} 
		else {
        	$error = "Error al eliminar el registro.";
    	}
	}
?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar Tipo de Mercadería – Global Transportes</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- CSS del sistema -->
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body class="bg-light">
    <!-- HEADER -->
    <header class="dashboard-header bg-white shadow-sm py-3">
        <div class="container d-flex align-items-center justify-content-between">
            <h1 class="h4 mb-0">📦 Editar Tipo de Mercadería</h1>
            <a href="../mantenimiento/mantenimiento.php" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Volver al Mantenimiento
            </a>
        </div>
    </header>

    <!-- MAIN -->
    <main class="container py-4">
        <?php
        // Mostrar mensajes de éxito si llegan por GET
        if (isset($_GET['msg']) && $_GET['msg'] == 'actualizado'):
        ?>
            <div class="alert alert-success">Registro actualizado correctamente.</div>
        <?php
        elseif (isset($_GET['msg']) && $_GET['msg'] == 'eliminado'):
        ?>
            <div class="alert alert-success">Registro eliminado correctamente.</div>
        <?php
        elseif (isset($_GET['msg']) && $_GET['msg'] == 'agregado'):
        ?>
            <div class="alert alert-success">Nueva mercadería agregada correctamente.</div>
        <?php
        endif;
        if (isset($error)):
        ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <!-- Formulario para agregar nueva mercadería -->
        <h2 class="h5 mb-4">Agregar Nueva Mercadería</h2>
        <form method="post" action="editar_tipo_mercaderia.php">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea class="form-control" id="descripcion" name="descripcion" rows="4" required></textarea>
            </div>
            <button type="submit" name="agregar" class="btn btn-success">Agregar Mercadería</button>
        </form>

        <hr>

        <div class="table-responsive">
            <?php
            // Ejecutar la consulta y depurar el número de registros
            $resultado = $conn->query("SELECT * FROM tipos_mercaderia");
            if (!$resultado) {
                die("Error en la consulta: " . $conn->error);
            }
            // Depuración: mostrar cantidad de registros devueltos
            echo "<p>Número de registros encontrados: " . $resultado->num_rows . "</p>";
            ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if ($resultado->num_rows === 0) {
                        echo "<tr><td colspan='4'>No se encontraron registros en la tabla.</td></tr>";
                    } else {
                        while ($row = $resultado->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['id']); ?></td>
                                <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($row['descripcion']); ?></td>
                                <td>
                                    <a class="btn btn-sm btn-warning me-2" href="editar_tipo_mercaderia.php?editar=<?php echo $row['id']; ?>">🔄 Modificar</a>
                                    <a class="btn btn-sm btn-danger" href="editar_tipo_mercaderia.php?eliminar=<?php echo $row['id']; ?>" onclick="return confirm('¿Estás seguro de eliminar este registro?')">🗑 Eliminar</a>
                                </td>
                            </tr>
                        <?php endwhile;
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </main>

    <!-- FOOTER -->
    <footer class="footer bg-white text-center py-3 mt-auto">
        <div class="container">
            <small class="text-muted">&copy; 2025 Global Transportes. Todos los derechos reservados.</small>
        </div>
    </footer>

    <!-- JS de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
