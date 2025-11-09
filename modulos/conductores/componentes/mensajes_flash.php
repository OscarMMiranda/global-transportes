<?php
    // archivo: includes/mensajes_flash.php

    if (session_status() === PHP_SESSION_NONE) {
		session_start();
		}

	if (isset($_SESSION['flash']) && is_array($_SESSION['flash'])):
    	foreach ($_SESSION['flash'] as $msg):
        	$tipo = isset($msg['tipo']) ? $msg['tipo'] : 'info';
        	$texto = isset($msg['texto']) ? $msg['texto'] : '';
        	$icono = '';

        switch ($tipo) {
            case 'success': $icono = '✅'; break;
            case 'error':   $icono = '❌'; break;
            case 'warning': $icono = '⚠️'; break;
            case 'info':    $icono = 'ℹ️'; break;
        	}
?>
    <div class="alert alert-<?= htmlspecialchars($tipo) ?> alert-dismissible fade show" role="alert">
        <?= $icono ?> <?= htmlspecialchars($texto) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
<?php
    endforeach;
    	unset($_SESSION['flash']);
	endif;
?>