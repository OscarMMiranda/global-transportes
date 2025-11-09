<?php
	/**
	* Encabezado visual del módulo
 	* Requiere: $titulo (string)
 	* Opcional: $icono (string) — clase Font Awesome
 	*/

	// archivo: /modulos/conductores/componentes/header.php
?>

<div class="container py-2">
  	<div class="d-flex align-items-center justify-content-between mb-2">
    	<h2 class="mb-0">
      		<?php if (!empty($icono)): ?>
        		<i class="<?= htmlspecialchars($icono) ?> me-2 text-primary"></i>
      		<?php endif; ?>
      		<?= htmlspecialchars($titulo) ?>
    	</h2>
  	</div>
</div>