 <?php
    // archivo : /modulos/clientes/componentes/TablaClientes.php


if (!defined('GT_APP')) {
    define('GT_APP', true);
}

// $clientes viene del controlador
if (!isset($clientes) || !is_array($clientes)) {
    $clientes = array();
}

// Helper seguro
function campo_cliente($row, $key, $placeholder = '') {
    if (!isset($row[$key]) || $row[$key] === '' || $row[$key] === null) {
        return $placeholder;
    }
    return htmlspecialchars($row[$key], ENT_QUOTES, 'UTF-8');
}
?>

<div class="contenedor-tabla-clientes">
    <table id="tablaClientes" class="table table-striped table-bordered table-sm tabla-clientes">
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
        <?php if (empty($clientes)): ?>
            <tr>
                <td colspan="7" class="text-center">No se encontraron clientes.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($clientes as $c): ?>
                <tr>
                    <td><?= campo_cliente($c, 'id'); ?></td>
                    <td><?= campo_cliente($c, 'nombre'); ?></td>
                    <td><?= campo_cliente($c, 'ruc'); ?></td>
                    <td><?= campo_cliente($c, 'direccion'); ?></td>
                    <td><?= campo_cliente($c, 'correo'); ?></td>
                    <td><?= campo_cliente($c, 'telefono'); ?></td>

                    <td class="acciones">
                        <a href="?action=form&id=<?= campo_cliente($c, 'id'); ?>" 
                           class="btn btn-sm btn-primary">Editar</a>

                        <a href="?action=delete&id=<?= campo_cliente($c, 'id'); ?>" 
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('¿Eliminar este cliente?');">
                           Eliminar
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>
