<?php
session_start();
require_once '../includes/conexion.php';

// Verificar acceso solo para administradores
if (!isset($_SESSION['usuario']) || $_SESSION['rol_nombre'] !== 'admin') {
    die("❌ Acceso denegado.");
}

// Validar si se recibió el nombre de la tabla
if (!isset($_GET['tabla'])) {
    die("❌ Error: No se ha especificado una tabla.");
}

$tabla = htmlspecialchars($_GET['tabla']);

// Obtener los registros de la tabla
$sql = "SELECT * FROM `$tabla`";
$resultado = $conn->query($sql);

if (!$resultado) {
    die("❌ Error en consulta SQL: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Registros - <?= $tabla ?></title>
    <link rel="stylesheet" href="../css/base.css">
</head>
<body>
<header>
    <h1>🖊️ Editar Registros en <?= $tabla ?></h1>
    <nav>
        <a href="admin_db.php" class="btn-nav">🏠 Panel Principal</a>
        <a href="ver_datos.php?tabla=<?= $tabla ?>" class="btn-nav">🔍 Ver Datos</a>
    </nav>
</header>

<main>
    <h3>Registros de la Tabla</h3>
    <form action="procesar_edicion.php" method="POST">
        <input type="hidden" name="tabla" value="<?= $tabla ?>">
        <table border="1">
            <tr>
                <?php foreach ($resultado->fetch_fields() as $campo) { ?>
                    <th><?= htmlspecialchars($campo->name) ?></th>
                <?php } ?>
                <th>Acción</th>
            </tr>
            <?php while ($fila = $resultado->fetch_assoc()) { ?>
                <tr>
                    <?php foreach ($fila as $campo => $valor) { ?>
                        <td><input type="text" name="datos[<?= $fila['id'] ?>][<?= $campo ?>]" value="<?= htmlspecialchars($valor) ?>"></td>
                    <?php } ?>
                    <td><button type="submit" name="guardar" value="<?= $fila['id'] ?>">💾 Guardar</button></td>
                </tr>
            <?php } ?>
        </table>
    </form>
</main>

<footer>
    <p>&copy; 2025 Global Transportes. Todos los derechos reservados.</p>
</footer>
</body>
</html>

