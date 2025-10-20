<?php
	// 	archivo	: 	/modulos/mantenimiento/zonas/acciones/editar.php

	require_once __DIR__ . '/../../../includes/config.php';
	$conn = getConnection();
	require_once __DIR__ . '/controllers/zonas_controller.php';

	$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
	$registro = $id > 0 ? obtenerRutaExtendida($id) : [
  		'id' => 0,
  		'zona_id' => 0,
  		'origen_id' => 0,
  		'destino_id' => 0,
  		'kilometros' => ''
		];

$zonasPadre = listarZonasPadre();
$distritos  = listarDistritosDisponibles();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Ruta</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

<div class="container">
  <h3 class="mb-4">Editar Ruta</h3>
  <form method="POST" action="index.php">
    <input type="hidden" name="id" value="<?= $registro['id'] ?>">

    <!-- Zona -->
    <div class="mb-3">
      <label for="zona_id" class="form-label">Zona</label>
      <select name="zona_id" id="zona_id" class="form-select" required>
        <option value="">Seleccione una zona</option>
        <?php foreach ($zonasPadre as $zona): ?>
          <option value="<?= $zona['id'] ?>" <?= $zona['id'] == $registro['zona_id'] ? 'selected' : '' ?>>
            <?= $zona['nombre'] ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <!-- Origen -->
    <div class="mb-3">
      <label for="origen_id" class="form-label">Distrito de Origen</label>
      <select name="origen_id" id="origen_id" class="form-select" required>
        <option value="">Seleccione distrito de origen</option>
        <?php foreach ($distritos as $d): ?>
          <option value="<?= $d['id'] ?>" <?= $d['id'] == $registro['origen_id'] ? 'selected' : '' ?>>
            <?= $d['departamento'] ?> / <?= $d['provincia'] ?> / <?= $d['nombre'] ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <!-- Destino -->
    <div class="mb-3">
      <label for="destino_id" class="form-label">Distrito de Destino</label>
      <select name="destino_id" id="destino_id" class="form-select" required>
        <option value="">Seleccione distrito de destino</option>
        <?php foreach ($distritos as $d): ?>
          <option value="<?= $d['id'] ?>" <?= $d['id'] == $registro['destino_id'] ? 'selected' : '' ?>>
            <?= $d['departamento'] ?> / <?= $d['provincia'] ?> / <?= $d['nombre'] ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <!-- Kilometraje -->
    <div class="mb-3">
      <label for="kilometros" class="form-label">Kilometraje (opcional)</label>
      <input type="number" step="0.01" name="kilometros" id="kilometros" class="form-control"
             value="<?= htmlspecialchars($registro['kilometros']) ?>" placeholder="Ej. 12.5">
    </div>

    <div class="mt-4">
      <button type="submit" class="btn btn-primary">Guardar cambios</button>
      <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </div>
  </form>
</div>

</body>
</html>