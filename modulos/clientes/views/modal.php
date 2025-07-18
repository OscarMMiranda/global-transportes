<?php
	// modules/clientes/views/modal.php

	// Asegúrate de que $cliente (array) está definido
	if (empty($cliente)) {
    	echo '<p class="text-danger">Cliente no encontrado.</p>';
		return;
		}
?>

<div class="container-fluid">
	<dl class="row mb-0">
    	<dt class="col-sm-4">ID</dt>
    	<dd class="col-sm-8"><?= htmlspecialchars($cliente['id'], ENT_QUOTES) ?></dd>

    	<dt class="col-sm-4">Nombre</dt>
    	<dd class="col-sm-8"><?= htmlspecialchars($cliente['nombre'], ENT_QUOTES) ?></dd>

    	<dt class="col-sm-4">RUC</dt>
    	<dd class="col-sm-8"><?= htmlspecialchars($cliente['ruc'], ENT_QUOTES) ?: '<em>No registrado</em>' ?></dd>

    	<dt class="col-sm-4">Dirección</dt>
    	<dd class="col-sm-8"><?= htmlspecialchars($cliente['direccion'], ENT_QUOTES) ?: '<em>No registrada</em>' ?></dd>

    	<dt class="col-sm-4">Correo</dt>
    	<dd class="col-sm-8"><?= htmlspecialchars($cliente['correo'], ENT_QUOTES) ?: '<em>No registrado</em>' ?></dd>

    	<dt class="col-sm-4">Teléfono</dt>
    	<dd class="col-sm-8"><?= htmlspecialchars($cliente['telefono'], ENT_QUOTES) ?: '<em>No registrado</em>' ?></dd>

    	<dt class="col-sm-4">Departamento</dt>
    	<dd class="col-sm-8"><?= htmlspecialchars($cliente['departamento'], ENT_QUOTES) ?></dd>

    	<dt class="col-sm-4">Provincia</dt>
    	<dd class="col-sm-8"><?= htmlspecialchars($cliente['provincia'], ENT_QUOTES) ?></dd>

    	<dt class="col-sm-4">Distrito</dt>
    	<dd class="col-sm-8"><?= htmlspecialchars($cliente['distrito'], ENT_QUOTES) ?></dd>
  	</dl>
</div>
