<?php
// archivo: /modulos/clientes/componentes/tabs.php

if (!defined('GT_APP')) {
    define('GT_APP', true);
}

$estado = isset($_GET['estado']) ? $_GET['estado'] : 'todos';
$action = isset($_GET['action']) ? $_GET['action'] : 'list';
?>

<ul class="nav nav-tabs modulo-tabs-estado">

    <li class="nav-item">
        <a class="nav-link <?= ($estado === 'todos') ? 'active' : '' ?>"
           href="?action=<?= htmlspecialchars($action, ENT_QUOTES, 'UTF-8'); ?>&estado=todos">
            Todos
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($estado === 'Activo') ? 'active' : '' ?>"
           href="?action=<?= htmlspecialchars($action, ENT_QUOTES, 'UTF-8'); ?>&estado=Activo">
            Activos
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($estado === 'Inactivo') ? 'active' : '' ?>"
           href="?action=<?= htmlspecialchars($action, ENT_QUOTES, 'UTF-8'); ?>&estado=Inactivo">
            Inactivos
        </a>
    </li>

</ul>
