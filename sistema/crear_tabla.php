<?php
session_start();
require_once '../includes/conexion.php';

// Verificar acceso solo para administradores
if (!isset($_SESSION['usuario']) || $_SESSION['rol_nombre'] !== 'admin') {
    die("❌ Acceso denegado.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Nueva Tabla</title>
    <link rel="stylesheet" href="../css/base.css">
    <script>
        function agregarFila() {
            var tabla = document.getElementById("tablaColumnas");
            var fila = tabla.insertRow();
            fila.innerHTML = '<td><input type="text" name="columnas[]" required></td>' +
                             '<td><select name="tipos[]">' +
                                '<option value="INT">INT (Entero)</option>' +
                                '<option value="VARCHAR(255)">VARCHAR (Texto Corto)</option>' +
                                '<option value="TEXT">TEXT (Texto Largo)</option>' +
                                '<option value="DATE">DATE (Fecha)</option>' +
                                '<option value="BOOLEAN">BOOLEAN (Verdadero/Falso)</option>' +
                             '</select></td>' +
                             '<td><select name="restricciones[]">' +
                                '<option value="">Ninguna</option>' +
                                '<option value="NOT NULL">NOT NULL</option>' +
                                '<option value="UNIQUE">UNIQUE</option>' +
                                '<option value="DEFAULT">DEFAULT</option>' +
                                '<option value="PRIMARY KEY">PRIMARY KEY</option>' +
                                '<option value="FOREIGN KEY">FOREIGN KEY</option>' +
                             '</select></td>';
        }

        function confirmarEnvio() {
            return confirm("⚠️ ¿Estás seguro de crear esta tabla?");
        }
    </script>
</head>
<body>
<header>
    <h1>➕ Crear Nueva Tabla</h1>
    <nav>
        <a href="admin_db.php" class="btn-nav">🏠 Panel Principal</a>
    </nav>
</header>

<main>
    <form action="procesar_creacion.php" method="POST" onsubmit="return confirmarEnvio();">
        <label for="nombre_tabla">Nombre de la Tabla:</label>
        <input type="text" name="nombre_tabla" pattern="[a-zA-Z0-9_]+" title="Solo letras, números y guiones bajos." required>

        <h3>Definir Columnas</h3>
        <table border="1" id="tablaColumnas">
            <tr>
                <th>Nombre de la Columna</th>
                <th>Tipo de Dato</th>
                <th>Restricción</th>
            </tr>
        </table>
        <button type="button" onclick="agregarFila()">➕ Agregar Columna</button>
        
        <button type="submit">💾 Crear Tabla</button>
    </form>
</main>

<footer>
    <p>&copy; 2025 Global Transportes. Todos los derechos reservados.</p>
</footer>
</body>
</html>
