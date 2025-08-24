<?php	
	

	session_start();

	// 1) Modo depuración (solo DEV)
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors',     1);
ini_set('error_log',      __DIR__ . '/error_log.txt');

// 2) Cargar configuración y conexión
require_once __DIR__ . '/../../../includes/config.php';
// require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();


	require_once __DIR__ . '/controllers/valores_controller.php';

// Solo admins pueden
if (!isset($_SESSION['usuario']) || $_SESSION['rol_nombre'] !== 'admin') {
    header('Location: ../../sistema/login.php');
    exit;
}

$alert = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Procesar el nuevo valor referencial
    $error = procesarValorReferencial($_POST);
    if ($error === '') {
        $alert = '<div class="alert alert-success">✅ Valor referencial guardado correctamente.</div>';
    } else {
        $alert = "<div class=\"alert alert-danger\">$error</div>";
    }
}

// Traemos datos para listado y selects
$valores = listarValoresReferenciales();
$valores_pivot = listarValoresPivot();

$zonas   = listarZonasPadre();
$tipos   = listarTiposMercaderia();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Valores Referenciales – Mantenimiento</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
        rel="stylesheet">
</head>
<body class="bg-light">

<!-- Botón de regreso -->
    <a href="../mantenimiento.php" 
       class="btn btn-outline-secondary btn-sm mb-3">
      <i class="fas fa-arrow-left me-1"></i> Volver a Mantenimiento
    </a>

  <div class="container py-4">
    <h1 class="h4 mb-4">💰 Valores Referenciales</h1>
    <?= $alert ?>

    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalValores">
      + Nuevo valor
    </button>

    <table class="table table-striped align-middle">
      <thead>
        <tr>
          <th>#</th>
          <th>Zona</th>
          <th>Tipo Mercadería</th>
          <th>Año</th>
          <th>Monto</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($valores)): ?>
          <tr><td colspan="5" class="text-center">No hay valores vigentes.</td></tr>
        <?php else: ?>
          <?php foreach($valores as $i => $v): ?>
            <tr>
              <td><?= $i+1 ?></td>
              <td><?= htmlspecialchars($v['zona']) ?></td>
              <td><?= htmlspecialchars($v['tipo_mercaderia']) ?></td>
              <td><?= $v['anio'] ?></td>
              <td><?= number_format($v['monto'],2) ?></td>
            </tr>
          <?php endforeach ?>
        <?php endif ?>
      </tbody>
    </table>
  </div>

  <!-- Modal de nuevo/editar -->
  <?php include __DIR__ . '/views/form_valores.php' ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js">
  </script>
</body>
</html>

