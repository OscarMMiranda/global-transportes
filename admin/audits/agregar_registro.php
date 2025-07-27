<?php
session_start();

// 2) Cargar conexión y helpers
require_once __DIR__ . '/../../includes/conexion.php';
require_once __DIR__ . '/../../includes/helpers.php';

// Verificar acceso solo para administradores
if (!isset($_SESSION['usuario']) || $_SESSION['rol_nombre'] !== 'admin') {
    die("❌ Acceso denegado.");
}

// Validar si se recibió el nombre de la tabla
if (!isset($_GET['tabla'])) {
    die("❌ Error: No se ha especificado una tabla.");
}

$tabla = htmlspecialchars($_GET['tabla']);

// Obtener las columnas de la tabla
$sql = "SHOW COLUMNS FROM `$tabla`";
$resultado = $conn->query($sql);

if (!$resultado) {
    die("❌ Error en consulta SQL: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Registro - <?= $tabla ?></title>
    <link rel="stylesheet" href="../css/base.css">
</head>
<body>
<header>
    <h1>➕ Agregar Registro en <?= $tabla ?></h1>
    <nav>
        <a href="admin_db.php" class="btn-nav">🏠 Panel Principal</a>
        <a href="ver_datos.php?tabla=<?= $tabla ?>" class="btn-nav">🔍 Ver Datos</a>
    </nav>
</header>

<main>
    <h3>Nuevo Registro</h3>
    <form action="procesar_agregar.php" method="POST">
        <input type="hidden" name="tabla" value="<?= $tabla ?>">
        <?php while ($columna = $resultado->fetch_assoc()) { ?>
            <label for="<?= htmlspecialchars($columna['Field']) ?>"><?= htmlspecialchars($columna['Field']) ?>:</label>
            <input type="text" name="datos[<?= htmlspecialchars($columna['Field']) ?>]" required>
        <?php } ?>
        <button type="submit">💾 Guardar Registro</button>
    </form>
</main>

<footer>
    <p>&copy; 2025 Global Transportes. Todos los derechos reservados.</p>
</footer>
</body>
</html>
