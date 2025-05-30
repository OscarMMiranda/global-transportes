<?php
session_start();
require_once '../../includes/conexion.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario']) || $_SESSION['rol_nombre'] !== 'admin') {
    header("Location: ../sistema/login.php");
    exit();
}

// Obtener lista de documentos desde la base de datos
$sql = "SELECT dv.id, v.placa, td.nombre AS tipo_documento, dv.numero_documento, dv.fecha_vencimiento, dv.archivo 
        FROM documentos_vehiculo dv 
        JOIN vehiculos v ON dv.vehiculo_id = v.id
        JOIN tipos_documento td ON dv.tipo_documento_id = td.id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Documentos</title>
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/documentos.css"> <!-- Estilos específicos del módulo -->
</head>
<body>
    <header class="dashboard-header">
        <div class="contenedor">
            <h1>📄 Gestión de Documentos Vehiculares</h1>
            <a href="form_documentos.php" class="boton-accion">➕ Registrar Nuevo Documento</a>
        </div>
    </header>

    <main class="contenedor">
        <table class="tabla-estilizada">
            <thead>
                <tr>
                    <th>Vehículo</th>
                    <th>Tipo de Documento</th>
                    <th>Número</th>
                    <th>Fecha de Vencimiento</th>
                    <th>Archivo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= htmlspecialchars($row['placa']) ?></td>
                    <td><?= htmlspecialchars($row['tipo_documento']) ?></td>
                    <td><?= htmlspecialchars($row['numero_documento']) ?></td>
                    <td><?= htmlspecialchars($row['fecha_vencimiento']) ?></td>
                    <td><a href="../uploads/<?= htmlspecialchars($row['archivo']) ?>" target="_blank">📎 Ver</a></td>
                    <td>
                        <a href="editar_documento.php?id=<?= $row['id'] ?>" class="btn-accion editar">✏️ Editar</a>
                        <a href="eliminar_documento.php?id=<?= $row['id'] ?>" class="btn-accion eliminar" onclick="return confirm('¿Seguro que deseas eliminar este documento?')">🗑️ Eliminar</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </main>

    <footer class="footer">
        <p>&copy; 2025 Global Transportes. Todos los derechos reservados.</p>
    </footer>
</body>
</html>

