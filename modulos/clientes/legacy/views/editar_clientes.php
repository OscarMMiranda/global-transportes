<?php
// archivo: public_html/modulos/clientes/views/editar_clientes.php

// 1) Carga configuración global (define INCLUDES_PATH, arranca sesión y crea $conn)
require_once rtrim($_SERVER['DOCUMENT_ROOT'], '/\\') . '/includes/config.php';

// 2) Incluye el header
require_once INCLUDES_PATH . '/header.php';



// ─── 3) Validar ID recibido por GET ───
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    echo "<p class='mensaje-sistema error'>ID de cliente inválido.</p>";
    require_once INCLUDES_PATH . '/footer.php';
    exit;
}

// ─── 4) Recuperar cliente ───
$sql = "SELECT * FROM clientes WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();

if (!$res || $res->num_rows === 0) {
    echo "<p class='mensaje-sistema error'>Cliente no encontrado.</p>";
    require_once INCLUDES_PATH . '/footer.php';
    exit;
}
$cliente = $res->fetch_assoc();
$stmt->close();


// ─── 5) Precargar departamentos, provincias y distritos ───
$dptos = $conn->query("SELECT id, nombre FROM departamentos ORDER BY nombre");


// Provincias del departamento del cliente
$provs = $conn->prepare(
    "SELECT id, nombre 
     FROM provincias 
     WHERE departamento_id = ?
     ORDER BY nombre"
);

$provs->bind_param("i", $cliente['departamento_id']);
$provs->execute();
$provs = $provs->get_result();

// Distritos de la provincia del cliente
$dists = $conn->prepare(
    "SELECT id, nombre 
     FROM distritos 
    WHERE provincia_id = ?
     ORDER BY nombre"
);
$dists->bind_param("i", $cliente['provincia_id']);
$dists->execute();
$dists = $dists->get_result();

?>
<link rel="stylesheet" href="/css/estilo.css">

<main class="container py-4">
  <h2 class="titulo-pagina text-center mb-4">✏️ Editar Cliente</h2>

  <!-- Mensajes de éxito / error -->
   <?php if (isset($_GET['msg'])): ?>
    <p class="mensaje-sistema <?= ($_GET['msg'] === 'ok') ? 'exito' : 'error' ?>">
      <?= ($_GET['msg'] === 'ok')
          ? 'Cliente actualizado correctamente.'
          : 'Error al actualizar el cliente.' ?>
    </p>
  <?php endif; ?>

  <div class="login-form">
    <form 
		
		action="/modulos/clientes/controllers/actualizar_clientes.php"
		method="POST" 
		class="formulario">
      <input type="hidden" name="id" value="<?= htmlspecialchars($cliente['id'], ENT_QUOTES, 'UTF-8') ?>">

      <div class="row gx-3 gy-4">
        <div class="col-md-6">
          <label for="nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
          <input
            type="text"
            id="nombre"
            name="nombre"
            class="form-control"
            required
            value="<?= htmlspecialchars($cliente['nombre'], ENT_QUOTES, 'UTF-8') ?>"
          >
        </div>

        <div class="col-md-6">
          <label for="ruc" class="form-label">RUC</label>
          <input
            type="text"
            id="ruc"
            name="ruc"
            class="form-control"
            pattern="\d{11}"
            maxlength="11"
            value="<?= htmlspecialchars($cliente['ruc'], ENT_QUOTES, 'UTF-8') ?>"
          >
        </div>

        <div class="col-md-6">
          <label for="direccion" class="form-label">Dirección</label>
          <input
            type="text"
            id="direccion"
            name="direccion"
            class="form-control"
            value="<?= htmlspecialchars($cliente['direccion'], ENT_QUOTES, 'UTF-8') ?>"
          >
        </div>

        <div class="col-md-6">
          <label for="telefono" class="form-label">Teléfono</label>
          <input
            type="text"
            id="telefono"
            name="telefono"
            class="form-control"
            pattern="\d+"
            maxlength="15"
            value="<?= htmlspecialchars($cliente['telefono'], ENT_QUOTES, 'UTF-8') ?>"
          >
        </div>

        <div class="col-md-6">
          <label for="correo" class="form-label">Correo</label>
          <input
            type="email"
            id="correo"
            name="correo"
            class="form-control"
            value="<?= htmlspecialchars($cliente['correo'], ENT_QUOTES, 'UTF-8') ?>"
          >
        </div>

        <div class="col-md-6">
          	<label 
		  		for="departamento" 
		  		class="form-label">Departamento 
				<span class="text-danger">*</span>
			</label>
          <select 
		  		id="departamento" 
				name="departamento_id" 
				class="form-select" 
				required>
            <option value="">Seleccione</option>
            <?php while ($row = $dptos->fetch_assoc()): ?>
              <option
                value="<?= $row['id'] ?>"
                <?= $row['id'] == $cliente['departamento_id'] ? 'selected' : '' ?>
              >
                <?= htmlspecialchars($row['nombre'], ENT_QUOTES, 'UTF-8') ?>
              </option>
            <?php endwhile; ?>
          </select>
        </div>

        <div class="col-md-6">
          <label for="provincia" class="form-label">Provincia <span class="text-danger">*</span></label>
          <select id="provincia" name="provincia_id" class="form-select" required>
            <option value="">Seleccione</option>
            <?php while ($row = $provs->fetch_assoc()): ?>
              <option
                value="<?= $row['id'] ?>"
                <?= $row['id'] == $cliente['provincia_id'] ? 'selected' : '' ?>
              >
                <?= htmlspecialchars($row['nombre'], ENT_QUOTES, 'UTF-8') ?>
              </option>
            <?php endwhile; ?>
          </select>
        </div>
		
		<!-- Distrito -->
        <div class="col-md-6">
          <label 
		  		for="distrito" 
		  		class="form-label">
				Distrito 
				<span class="text-danger">*</span>
			</label>
          	<select 
				id="distrito" 
				name="distrito_id" 
				class="form-select" 
				required>
            	<option value="">
					Seleccione
				</option>
            	<?php while ($row = $dists->fetch_assoc()): ?>
				<option
                	value="<?= $row['id'] ?>"
                <?= $row['id'] == $cliente['distrito_id'] ? 'selected' : '' ?>
              >
                <?= htmlspecialchars($row['nombre'], ENT_QUOTES, 'UTF-8') ?>
              </option>
            <?php endwhile; ?>
          </select>
        </div>
		
		<!-- Botones -->
        <div class="col-12 text-end mt-4">
          <button type="submit" class="btn btn-outline-success btn-lg">
            <i class="fas fa-save me-1"></i> Actualizar
          </button>
          <a href="../clientes.php" class="btn btn-outline-primary btn-lg">
            <i class="fas fa-times"></i> Cancelar
          </a>
          <a href="../../erp_dashboard.php" class="btn btn-outline-secondary btn-lg">
            <i class="fas fa-arrow-left"></i> Volver al Dashboard
          </a>
        </div>
      </div>
    </form>
  </div>
</main>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Tu script de ubigeo (usa ruta absoluta desde public_html) -->
<script src="/modulos/clientes/js/ubigeo.js"></script>
<script>
  // Si ubigeo.js no precarga automáticamente, dispara el change
  $(function() {
    $('#departamento').trigger('change');
  });
</script>

<?php
// 6) Incluye el footer
require_once INCLUDES_PATH . '/footer.php';
