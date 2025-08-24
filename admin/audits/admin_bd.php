<?php
// 1) Modo depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'error_log.txt');


session_start();


// 2) Cargar conexión y helpers
require_once __DIR__ . '/../../includes/conexion.php';


// 4) Obtener la conexión
    $conn = getConnection();

require_once __DIR__ . '/../../includes/helpers.php';

error_log("⚙️ SESSION en admin_db.php: " . print_r($_SESSION, true));


// 02. Verificar acceso solo para administradores
		if (!isset($_SESSION['usuario']) || $_SESSION['rol_nombre'] !== 'admin') 
			{
    		error_log("❌ Acceso no autorizado: " . $_SERVER['REMOTE_ADDR']);
    		header("Location: login.php");
    		exit();
			}

// Obtener todas las tablas de la base de datos con validación de errores
$sql = "SHOW TABLES";
$resultado = $conn->query($sql);

if (!$resultado) {
    error_log("❌ Error en consulta SQL: " . $conn->error);
    die("<p style='color: red;'>Error al obtener las tablas. Intenta nuevamente o verifica la conexión.</p>
        <a href='diagnostico.php'>🔍 Diagnóstico del sistema</a>");
}

// Exportación a CSV
if (isset($_GET['exportar']) && $_GET['exportar'] === 'csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="base_de_datos.csv"');

    $output = fopen('php://output', 'w');
    fputcsv($output, ["Tabla"]);

    while ($fila = $resultado->fetch_assoc()) {
        fputcsv($output, [reset($fila)]);
    }
    fclose($output);
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administración de BD</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/base.css">
    <script>
        function filtrarTablas() {
            var input = document.getElementById("buscarTabla");
            var filtro = input.value.toUpperCase();
            var filas = document.querySelectorAll("table tr");

            filas.forEach((fila, index) => {
                if (index === 0) return; 
                var nombre = fila.getElementsByTagName("td")[0];
                fila.style.display = nombre && nombre.textContent.toUpperCase().includes(filtro) ? "" : "none";
            });
        }
    </script>
</head>
<body>
<header>
    <h1>📂 Administración de Base de Datos</h1>
    <nav>
        <a href="../../modulos/erp_dashboard.php" class="btn-nav">⬅️ Volver al Panel</a>
        <a href="../../index.php" class="btn-nav">🏠 Inicio</a>
        <a href="crear_tabla.php" class="btn-nav">➕ Crear Nueva Tabla</a>
        <a href="historial_bd.php" class="btn-nav">📜 Ver Historial de Cambios</a>
        <a href="admin_db.php?exportar=csv" class="btn-nav">📥 Exportar a CSV</a>
    </nav>
</header>

<main>
    <h3>Buscar Tabla</h3>
    <input type="text" id="buscarTabla" onkeyup="filtrarTablas()" placeholder="🔍 Buscar tabla...">
    
    <table class="table-db" border="1">
        <tr>
            <th>Tabla</th>
            <th>Acción</th>
        </tr>
        <?php while ($fila = $resultado->fetch_assoc()) { 
            $tabla = reset($fila);
        ?>
            <tr>
                <td><?= htmlspecialchars($tabla) ?></td>
                <td>
                    <a href="editar_tabla.php?tabla=<?= htmlspecialchars($tabla) ?>">✏️ Editar Estructura</a>
                    <a href="ver_datos.php?tabla=<?= htmlspecialchars($tabla) ?>">🔍 Ver Datos</a>
                    <a href="registro_cambios.php?tabla=<?= htmlspecialchars($tabla) ?>">📝 Historial</a>
                    <a href="editar_registros.php?tabla=<?= htmlspecialchars($tabla) ?>">🖊️ Editar Registros</a>
                    <a href="agregar_registro.php?tabla=<?= htmlspecialchars($tabla) ?>">➕ Agregar Registro</a>
                    <a href="eliminar_tabla.php?tabla=<?= htmlspecialchars($tabla) ?>" 
                        onclick="return confirm('⚠️ ¿Estás seguro de eliminar la tabla \'<?= htmlspecialchars($tabla) ?>\'? Esta acción es irreversible.')">🗑 Eliminar Tabla</a>
                </td>
            </tr>
        <?php } ?>
    </table>
</main>

<footer>
    <p>&copy; 2025 Global Transportes. Todos los derechos reservados.</p>
</footer>
</body>
</html>
