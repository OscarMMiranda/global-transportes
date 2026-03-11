<?php
// archivo: /modulos/clientes/componentes/tabs.php
//


if (!defined('GT_APP')) {
    exit('Acceso directo no permitido');
}

$estado = isset($_GET['estado']) ? $_GET['estado'] : 'todos';
$action = isset($_GET['action']) ? $_GET['action'] : 'list';

$estados = array(
    'todos'    => 'Todos',
    'Activo'   => 'Activos',
    'Inactivo' => 'Inactivos'
);
?>

<ul class="nav nav-tabs modulo-tabs-estado">

<?php foreach ($estados as $valor => $label): ?>

<li class="nav-item">
    <a class="nav-link <?php echo ($estado === $valor) ? 'active' : ''; ?>"
       href="?action=<?php echo htmlspecialchars($action, ENT_QUOTES, 'UTF-8'); ?>&estado=<?php echo $valor; ?>">
        <?php echo $label; ?>
    </a>
</li>

<?php endforeach; ?>

</ul>