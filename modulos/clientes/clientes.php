<?php
session_start();
require_once '../../includes/conexion.php';

// Verificar rol
if (!isset($_SESSION['rol']) || ($_SESSION['rol'] !== 'admin' && $_SESSION['rol'] !== 'empleado')) {
    header("Location: ../login.php");
    exit();
}

$sql = "SELECT id, nombre, ruc, direccion, telefono, correo, distrito_id, provincia_id, departamento_id, estado, fecha_creacion FROM clientes ORDER BY id DESC";
$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Clientes</title>
    <link rel="stylesheet" href="../../css/estilo.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
</head>
<body>
<div class="container">
    <h1>Clientes</h1>

    <?php if (isset($_GET['mensaje'])): ?>
        <div class="mensaje-sistema success"><?= htmlspecialchars($_GET['mensaje']) ?></div>
    <?php elseif (isset($_GET['error'])): ?>
        <div class="mensaje-sistema error"><?= htmlspecialchars($_GET['error']) ?></div>
    <?php endif; ?>

    <a href="crear_cliente.php" class="boton-accion">➕ Crear Cliente</a>

    <?php if ($resultado && $resultado->num_rows > 0): ?>
        <table id="tablaClientes" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>RUC</th>
                    <th>Dirección</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Distrito ID</th>
                    <th>Provincia ID</th>
                    <th>Departamento ID</th>
                    <th>Estado</th>
                    <th>Fecha creación</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($cliente = $resultado->fetch_assoc()) : ?>
                <tr>
                    <td><?= $cliente['id'] ?></td>
                    <td><?= htmlspecialchars($cliente['nombre']) ?></td>
                    <td><?= htmlspecialchars($cliente['ruc']) ?></td>
                    <td><?= htmlspecialchars($cliente['direccion']) ?></td>
                    <td><?= htmlspecialchars($cliente['telefono']) ?></td>
                    <td><?= htmlspecialchars($cliente['correo']) ?></td>
                    <td><?= $cliente['distrito_id'] ?></td>
                    <td><?= $cliente['provincia_id'] ?></td>
                    <td><?= $cliente['departamento_id'] ?></td>
                    <td><?= $cliente['estado'] ?></td>
                    <td><?= $cliente['fecha_creacion'] ?></td>
                    <td>
                        <a href="editar_cliente.php?id=<?= $cliente['id'] ?>">Editar</a> |
                        <a href="eliminar_cliente.php?id=<?= $cliente['id'] ?>" onclick="return confirm('¿Eliminar este cliente?');">Eliminar</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No hay clientes registrados.</p>
    <?php endif; ?>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function () {
        $('#tablaClientes').DataTable({
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
            },
            pageLength: 10
        });
    });
</script>
</body>
</html>
