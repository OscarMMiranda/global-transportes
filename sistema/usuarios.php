<?php
session_start();
include '../includes/conexion.php';

// Verificar que el usuario esté logueado y sea admin por nombre de rol
if (!isset($_SESSION['usuario']) || !isset($_SESSION['rol_nombre']) || $_SESSION['rol_nombre'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Obtener lista de usuarios y roles
$sql = "SELECT u.id, u.nombre, u.apellido, u.usuario, u.correo, r.nombre AS rol, u.creado_en
        FROM usuarios u
        JOIN roles r ON u.rol = r.id
        ORDER BY u.id ASC";

$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Usuarios</title>
    <link rel="stylesheet" href="../css/estilo.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
</head>
<body>
<div class="container">
    <h1>Lista de Usuarios</h1>

    <?php if (isset($_GET['mensaje'])): ?>
        <div class="mensaje-sistema" style="background-color: #e6ffed; color: #007700; padding: 10px; margin-bottom: 15px;">
            <?= htmlspecialchars($_GET['mensaje']) ?>
        </div>
    <?php elseif (isset($_GET['error'])): ?>
        <div class="mensaje-sistema" style="background-color: #fee; color: #a00; padding: 10px; margin-bottom: 15px;">
            <?= htmlspecialchars($_GET['error']) ?>
        </div>
    <?php endif; ?>

    <a href="crear_usuario.php" class="boton-accion">Crear Usuario</a>

    <?php if ($resultado && $resultado->num_rows > 0): ?>
        <table id="tablaUsuarios" class="display tabla-usuarios" style="width:100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Usuario</th>
                    <th>Correo</th>
                    <th>Rol</th>
                    <th>Fecha de Creación</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($fila = $resultado->fetch_assoc()) : ?>
                <tr>
                    <td><?= $fila['id']; ?></td>
                    <td><?= htmlspecialchars($fila['nombre']); ?></td>
                    <td><?= htmlspecialchars($fila['apellido']); ?></td>
                    <td><?= htmlspecialchars($fila['usuario']); ?></td>
                    <td><?= htmlspecialchars($fila['correo']); ?></td>
                    <td><?= htmlspecialchars(ucfirst($fila['rol'])); ?></td>
                    <td><?= htmlspecialchars($fila['creado_en']); ?></td>
                    <td class="acciones">
                        <a href="editar_usuario.php?id=<?= $fila['id']; ?>">Editar</a> |
                        <a href="eliminar_usuario.php?id=<?= $fila['id']; ?>" onclick="return confirm('¿Eliminar este usuario?');">Eliminar</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No hay usuarios registrados.</p>
    <?php endif; ?>

    <p><a href="panel_admin.php" class="boton-accion">← Volver al Panel</a></p>
</div>

<!-- jQuery y DataTables -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function () {
        $('#tablaUsuarios').DataTable({
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
            },
            responsive: true,
            pageLength: 10
        });
    });
</script>
</body>
</html>
