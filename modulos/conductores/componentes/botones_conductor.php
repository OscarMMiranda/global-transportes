<?php
	// archivo  :   /modulos/conductores/componentes/botones_conductor.php

	// Requiere $c definido (conductor actual)
?>

<div class="btn-group" role="group" aria-label="Botones de acción del conductor">

	<!-- Ver -->
	<button 
  		type="button" 
  		class="btn btn-info btn-view" 
  		data-id="<?= $c['id'] ?>" 
  		title="Ver conductor"
	>
  		<i class="fa-solid fa-eye"></i>
	</button>

  	<!-- Editar -->
  	<button 
		type="button" 
    	class="btn btn-primary btn-edit" 
    	data-id="<?= $c['id'] ?>" 
    	title="Editar conductor"
  	>
    	<i class="fa-solid fa-pen-to-square"></i>
  	</button>

  	<?php if ($c['activo'] == 1): ?>
    	<!-- Eliminar -->
    	<button 
      		type="button" 
      		class="btn btn-danger btn-delete" 
      		data-id="<?= $c['id'] ?>" 
      		title="Eliminar conductor"
    	>
      		<i class="fa-solid fa-trash-can"></i>
    	</button>
  	<?php else: ?>
    	<!-- Restaurar -->
    	<button 
    	  	type="button" 
   		   	class="btn btn-success btn-restore" 
    	  	data-id="<?= $c['id'] ?>" 
    	  	title="Restaurar conductor"
    	>
    	  	<i class="fa-solid fa-rotate-left"></i>
    	</button>
  	<?php endif; ?>

</div>