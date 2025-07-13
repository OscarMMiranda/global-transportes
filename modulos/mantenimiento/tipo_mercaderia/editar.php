<?php
	ini_set('display_errors',1);
	ini_set('display_startup_errors',1);
	error_reporting(E_ALL);

	session_start();
	// require_once '../../../includes/conexion.php';

	require_once __DIR__ . '/../../../includes/conexion.php';

	if (!isset($_GET['id'])) {
    	header("Location: index.php");
    	exit;
		}

$id = (int) $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM tipos_mercaderia WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    $_SESSION['error'] = "Registro no encontrado.";
    header("Location: index.php");
    exit;
}

	$registro = $resultado->fetch_assoc();

	// $error = $_SESSION['error'] ?? null;
	$error = isset($_SESSION['error']) ? $_SESSION['error'] : null;

	unset($_SESSION['error']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Mercadería</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-4">
        <h1 class="h4 mb-4">✏️ Editar Tipo de Mercadería</h1>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="post" action="procesar_formulario.php">
            <input type="hidden" name="id" value="<?php echo $registro['id']; ?>">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" name="nombre" value="<?php echo htmlspecialchars($registro['nombre']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea class="form-control" name="descripcion" rows="3"><?php echo htmlspecialchars($registro['descripcion']); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar</button>
            <a href="index.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>
