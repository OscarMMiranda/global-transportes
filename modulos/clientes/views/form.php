<?php
	// public_html/modulos/clientes/views/form.php

	// Asegúrate de que estas variables vienen definidas:
	//   $editing       (bool), 
	//   $cliente       (array|null), 
	//   $departamentos (array), 
	//   $provincias    (array), 
	//   $distritos     (array).

	$editing       = isset($editing)       ? (bool) $editing       : false;
	$cliente       = isset($cliente) && is_array($cliente) ? $cliente : null;
	$departamentos = isset($departamentos) ? $departamentos     : array();
	$provincias    = isset($provincias)    ? $provincias        : array();
	$distritos     = isset($distritos)     ? $distritos         : array();
?>

<!-- 1) Hoja de estilos del módulo -->
<link
  	rel="stylesheet"
  	href="<?= BASE_URL ?>assets/css/clientes.css"
/>

<!-- 2) Variable global para el JS -->
<script>
  	window.CLIENTES_API_URL =
    	'<?= BASE_URL ?>modulos/clientes/controllers/ApiController.php';
</script>

<!-- 3) Tu script de clientes.js que usa la variable anterior -->
<script
  	src="<?= BASE_URL ?>assets/js/clientes.js"
  		defer>
</script>

<main class="container mt-4">
  	<h2 class="titulo-pagina text-center mb-4">
    	<i class="fas fa-user-edit me-2"></i>
    	<?php echo $editing ? 'Editar Cliente' : 'Registrar Cliente'; ?>
  	</h2>

  	<?php if (! empty($errorMessage)): ?>
    	<div class="alert alert-danger mb-3">
    		<?= htmlspecialchars($errorMessage, ENT_QUOTES) ?>
  		</div>
	<?php endif; ?>

  <form
    action="index.php?action=form<?php if ($editing) echo '&id='.$cliente['id']; ?>"
    method="post"
    class="mx-auto"
    style="max-width:600px;"
  >
    <?php if ($editing): ?>
      <input type="hidden" name="id" value="<?php echo $cliente['id']; ?>">
    <?php endif; ?>

    <!-- Nombre -->
    <div class="mb-3">
      	<label for="nombre" class="form-label">Nombre</label>
      	<input
        	type="text"
        	id="nombre"
        	name="nombre"
        	class="form-control uppercase"
        	required
        	value="<?php 
          		echo ($editing && isset($cliente['nombre']))
             	? htmlspecialchars($cliente['nombre'], ENT_QUOTES)
             	: '';
        		?>"
      	>
    </div>

    <!-- RUC -->
    <div class="mb-3">
      <label for="ruc" class="form-label">RUC</label>
      <input
        type="text"
        id="ruc"
        name="ruc"
        class="form-control"
        value="<?php 
          echo ($editing && isset($cliente['ruc']))
             ? htmlspecialchars($cliente['ruc'], ENT_QUOTES)
             : '';
        ?>"
      >
    </div>

    <!-- Dirección -->
    <div class="mb-3">
      <label for="direccion" class="form-label">Dirección</label>
      <input
        type="text"
        id="direccion"
        name="direccion"
        class="form-control uppercase"
        value="<?php 
          echo ($editing && isset($cliente['direccion']))
             ? htmlspecialchars($cliente['direccion'], ENT_QUOTES)
             : '';
        ?>"
      >
    </div>

    <!-- Correo -->
    <div class="mb-3">
      <label for="correo" class="form-label">Correo electrónico</label>
      <input
        type="email"
        id="correo"
        name="correo"
        class="form-control lowercase"
        value="<?php 
          echo ($editing && isset($cliente['correo']))
             ? htmlspecialchars($cliente['correo'], ENT_QUOTES)
             : '';
        ?>"
      >
    </div>

    <!-- Teléfono -->
    <div class="mb-3">
      <label for="telefono" class="form-label">Teléfono</label>
      <input
        type="text"
        id="telefono"
        name="telefono"
        class="form-control"
        value="<?php 
          echo ($editing && isset($cliente['telefono']))
             ? htmlspecialchars($cliente['telefono'], ENT_QUOTES)
             : '';
        ?>"
      >
    </div>

    <!-- Ubigeo dinámico -->
    <div class="row">
      <!-- Departamento -->
      <div class="mb-3 col-md-4">
        <label for="departamento_id" class="form-label">Departamento</label>
        <select id="departamento_id" name="departamento_id" class="form-select">
          <option value="">Selecciona...</option>
          <?php foreach ($departamentos as $dep): ?>
            <option 
              value="<?php echo $dep['id']; ?>" 
              <?php 
                if ($editing 
                 && isset($cliente['departamento_id']) 
                 && $cliente['departamento_id'] == $dep['id']
                ) echo 'selected'; 
              ?>
            >
              <?php echo htmlspecialchars($dep['nombre'], ENT_QUOTES); ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Provincia -->
      <div class="mb-3 col-md-4">
        <label for="provincia_id" class="form-label">Provincia</label>
        <select id="provincia_id" name="provincia_id" class="form-select">
          <option value="">Selecciona...</option>
          <?php foreach ($provincias as $prov): ?>
            <option 
              value="<?php echo $prov['id']; ?>" 
              <?php 
                if ($editing 
                 && isset($cliente['provincia_id']) 
                 && $cliente['provincia_id'] == $prov['id']
                ) echo 'selected'; 
              ?>
            >
              <?php echo htmlspecialchars($prov['nombre'], ENT_QUOTES); ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Distrito -->
      <div class="mb-3 col-md-4">
        <label for="distrito_id" class="form-label">Distrito</label>
        <select id="distrito_id" name="distrito_id" class="form-select">
          <option value="">Selecciona...</option>
          <?php foreach ($distritos as $dis): ?>
            <option 
              value="<?php echo $dis['id']; ?>" 
              <?php 
                if ($editing 
                 && isset($cliente['distrito_id']) 
                 && $cliente['distrito_id'] == $dis['id']
                ) echo 'selected'; 
              ?>
            >
              <?php echo htmlspecialchars($dis['nombre'], ENT_QUOTES); ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <!-- Botones -->
    <div class="text-center mt-4">
      <button type="submit" class="btn btn-success">
        <?php echo $editing ? 'Actualizar' : 'Guardar'; ?>
      </button>
      <a href="index.php?action=list" class="btn btn-secondary ms-2">
        Cancelar
      </a>
    </div>
  </form>
</main>
