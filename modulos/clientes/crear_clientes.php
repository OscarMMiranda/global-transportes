<?php
// crear_clientes.php (PHP 5.6 + Bootstrap 5)
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once '../../includes/conexion.php';

// Control de acceso
if (!isset($_SESSION['rol_nombre']) || !in_array($_SESSION['rol_nombre'], ['admin', 'empleado'])) {
    header("Location: ../../sistema/login.php");
    exit();
}

// Carga de departamentos
$departamentos = $conn->query("SELECT id, nombre FROM departamentos ORDER BY nombre");

// Flash messages
function flash($key) {
    if (isset($_SESSION[$key])) {
        $type = $key === 'mensaje' ? 'alert-success' : 'alert-danger';
        echo "<div class='alert {$type} alert-dismissible fade show' role='alert'>"
           . htmlspecialchars($_SESSION[$key])
           . "<button type='button' class='btn-close' data-bs-dismiss='alert'></button>"
           . "</div>";
        unset($_SESSION[$key]);
    }
}
?>
<link rel="stylesheet" href="../../css/estilo.css">

<!-- Si tienes reglas en estilo.css que limitan .container o .contenido,
     forzamos override aquí mismo: -->
<style>
  /* Forzar que MAIN ocupe todo el ancho */
  main.fullwidth {
    width: 100%;
    max-width: none !important;
    padding: 2rem 5%;
  }

  /* La tarjeta será 90% del viewport y centrada, con un max-width cómodo */
  .wide-card {
    width: 90vw;
    max-width: 1200px;
    margin: 0 auto;
  }
</style>

<main class="fullwidth">
  <h2 class="titulo-pagina text-center mb-4">➕ Registrar Cliente</h2>

  <?php
    flash('mensaje');
    flash('error');
  ?>

  <div class="card shadow-sm wide-card">
    <div class="card-body">
      <form action="guardar_clientes.php" method="POST" id="formCliente">
        <div class="row gx-4 gy-4">
          <div class="col-md-6 mb-3">
            <label for="nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
            <input type="text" id="nombre" name="nombre"
                   class="form-control" required maxlength="100">
          </div>

          <div class="col-md-6 mb-3">
            <label for="ruc" class="form-label">RUC <span class="text-danger">*</span></label>
            <input type="text" id="ruc" name="ruc"
                   class="form-control" required pattern="\d{11}"
                   title="11 dígitos numéricos" maxlength="11">
          </div>

          <div class="col-md-6 mb-3">
            <label for="direccion" class="form-label">Dirección</label>
            <input type="text" id="direccion" name="direccion"
                   class="form-control" maxlength="150">
          </div>

          <div class="col-md-6 mb-3">
            <label for="telefono" class="form-label">Teléfono</label>
            <input type="text" id="telefono" name="telefono"
                   class="form-control" pattern="\d+"
                   title="Solo dígitos" maxlength="15">
          </div>

          <div class="col-md-6 mb-3">
            <label for="correo" class="form-label">Correo</label>
            <input type="email" id="correo" name="correo"
                   class="form-control" maxlength="100">
          </div>

          <div class="col-md-6 mb-3">
            <label for="departamento" class="form-label">
              Departamento <span class="text-danger">*</span>
            </label>
            <select id="departamento" name="departamento_id"
                    class="form-select" required>
              <option value="">Seleccione</option>
              <?php while ($dep = $departamentos->fetch_assoc()): ?>
                <option value="<?= $dep['id'] ?>">
                  <?= htmlspecialchars($dep['nombre']) ?>
                </option>
              <?php endwhile; ?>
            </select>
          </div>

          <div class="col-md-6 mb-3">
            <label for="provincia" class="form-label">
              Provincia <span class="text-danger">*</span>
            </label>
            <select id="provincia" name="provincia_id"
                    class="form-select" required>
              <option value="">Seleccione un departamento</option>
            </select>
          </div>

          <div class="col-md-6 mb-3">
            <label for="distrito" class="form-label">
              Distrito <span class="text-danger">*</span>
            </label>
            <select id="distrito" name="distrito_id"
                    class="form-select" required>
              <option value="">Seleccione una provincia</option>
            </select>
          </div>
        </div>

        <div class="mt-4">
          <button type="submit" class="btn btn-success">💾 Guardar Cliente</button>
          <a href="clientes.php" class="btn btn-outline-secondary ms-2">
            ⬅ Cancelar
          </a>
        </div>
      </form>
    </div>
  </div>
</main>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js">
</script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(function(){
  $('#departamento').on('change', function() {
    $('#provincia').load(
      '../ubicaciones/ajax_provincias.php?departamento_id='+$(this).val()
    );
    $('#distrito').html(
      '<option value="">Seleccione una provincia</option>'
    );
  });
  $('#provincia').on('change', function() {
    $('#distrito').load(
      '../ubicaciones/ajax_distritos.php?provincia_id='+$(this).val()
    );
  });
});
</script>

<?php
require_once '../../includes/footer.php';
$conn->close();
?>
