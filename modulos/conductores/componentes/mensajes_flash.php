<?php
	// archivo: includes/mensajes_flash.php

	if (session_status() === PHP_SESSION_NONE) {
    	session_start();
		}

	if (isset($_SESSION['flash']) && is_array($_SESSION['flash'])):
    	foreach ($_SESSION['flash'] as $msg):
        	$tipo  = $msg['tipo'] ?? 'info';
        	$texto = $msg['texto'] ?? '';

        	// Validar tipo
        	$tiposValidos = ['success', 'error', 'warning', 'info'];
        	if (!in_array($tipo, $tiposValidos)) {
            	$tipo = 'info';
        		}

        	// Iconos Font Awesome
        	switch ($tipo) {
            	case 'success': $icono = '<i class="fa-solid fa-circle-check me-2"></i>'; break;
            	case 'error':   $icono = '<i class="fa-solid fa-circle-xmark me-2"></i>'; break;
            	case 'warning': $icono = '<i class="fa-solid fa-triangle-exclamation me-2"></i>'; break;
            	case 'info':    $icono = '<i class="fa-solid fa-circle-info me-2"></i>'; break;
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