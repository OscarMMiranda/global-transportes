<?php
require_once '../../includes/conexion.php';
require_once '../../includes/header.php';

$query = "SELECT c.id, c.nombre, c.ruc, c.direccion, c.telefono, c.correo, 
                 d.nombre AS departamento, p.nombre AS provincia, dis.nombre AS distrito
          FROM clientes c
          LEFT JOIN departamentos d ON c.departamento_id = d.id
          LEFT JOIN provincias p ON c.provincia_id = p.id
          LEFT JOIN distritos dis ON c.distrito_id = dis.id
          WHERE c.estado = 'Activo'
          ORDER BY c.id DESC";

$resultado = mysqli_query($conn, $query);
?>

<main class="contenido">
    <h1 class="titulo-pagina">📋 Listado de Clientes</h1>

    <div class="barra-superior acciones-clientes">
        <a href="crear_clientes.php" class="boton-accion">➕ Registrar Cliente</a>
        <a href="../erp_dashboard.php" class="boton-accion">🔙 Volver al Módulo</a>
    </div>

    <?php if (isset($_GET['mensaje'])): ?>
        <div class="mensaje-exito">
            <?= htmlspecialchars($_GET['mensaje']) ?>
        </div>
    <?php endif; ?>

    <div class="contenedor-tabla">
        <table class="tabla-usuarios tabla-estilizada">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>RUC</th>
                    <th>Dirección</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Departamento</th>
                    <th>Provincia</th>
                    <th>Distrito</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($cliente = mysqli_fetch_assoc($resultado)): ?>
                    <tr>
                        <td><?= $cliente['id'] ?></td>
                        <td><?= htmlspecialchars($cliente['nombre']) ?></td>
                        <td><?= htmlspecialchars($cliente['ruc']) ?></td>
                        <td><?= htmlspecialchars($cliente['direccion']) ?></td>
                        <td><?= htmlspecialchars($cliente['telefono']) ?></td>
                        <td><?= htmlspecialchars($cliente['correo']) ?></td>
                        <td><?= htmlspecialchars($cliente['departamento']) ?></td>
                        <td><?= htmlspecialchars($cliente['provincia']) ?></td>
                        <td><?= htmlspecialchars($cliente['distrito']) ?></td>
                        <td class="acciones">
                            <a href="editar_clientes.php?id=<?= $cliente['id'] ?>" class="btn-accion editar">✏️</a>
                            <a href="eliminar_clientes.php?id=<?= $cliente['id'] ?>" class="btn-accion eliminar" onclick="return confirm('¿Estás seguro de eliminar este cliente?');">🗑️</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</main>

<?php include '../../includes/footer.php'; ?>
