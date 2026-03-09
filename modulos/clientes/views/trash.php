<?php
if (!defined('GT_APP')) {
    define('GT_APP', true);
}
?>

<div class="container mt-4 modulo-clientes-trash">

    <!-- Tabs de estado -->
    <?php require __DIR__ . '/../componentes/tabs.php'; ?>

    <h2 class="titulo-modulo mb-4">
        Clientes Eliminados
    </h2>

    <!-- Botón volver -->
    <div class="acciones-modulo mb-3">
        <a href="index.php?action=list" class="btn btn-primary">
            Volver al listado
        </a>
    </div>
 
    <!-- Mensaje -->
    <?php if (!empty($_GET['msg'])): ?>
        <div class="alert alert-<?= $_GET['msg'] === 'restored' ? 'success' : 'danger' ?>">
            <?= $_GET['msg'] === 'restored'
                ? 'Cliente restaurado correctamente.'
                : 'No se pudo restaurar el cliente.' ?>
        </div>
    <?php endif; ?>

    <!-- Tabla de eliminados -->
    <div class="contenedor-tabla-clientes">
        <table id="tablaClientesEliminados" class="table table-striped table-bordered table-sm tabla-clientes">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>RUC</th>
                    <th>Dirección</th>
                    <th>Correo</th>
                    <th>Teléfono</th>
                    <th class="col-acciones">Acciones</th>
                </tr>
            </thead>

            <tbody>
                <?php if (!empty($clientesEliminados)): ?>
                    <?php foreach ($clientesEliminados as $c): ?>
                        <tr>
                            <td><?= htmlspecialchars($c['id'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?= htmlspecialchars($c['nombre'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?= htmlspecialchars($c['ruc'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?= htmlspecialchars($c['direccion'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?= htmlspecialchars($c['correo'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?= htmlspecialchars($c['telefono'], ENT_QUOTES, 'UTF-8'); ?></td>

                            <td class="acciones">
                                <a href="index.php?action=restore&id=<?= $c['id']; ?>"
                                   class="btn btn-sm btn-success"
                                   onclick="return confirm('¿Restaurar este cliente?');">
                                    Restaurar
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted">
                            No hay clientes eliminados.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Footer corporativo (JS del módulo) -->
    <?php require __DIR__ . '/../componentes/FooterClientes.php'; ?>

</div>
