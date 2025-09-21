<?php
// vistas/ver_vehiculo.php — Vista de detalle de un vehículo

require_once __DIR__ . '/../layout/header_vehiculos.php';

?>


<div class="container mt-4">
  <div class="card shadow-sm">
    <div class="card-header bg-info text-white d-flex align-items-center">
      <i class="fas fa-eye me-2"></i>
      <h5 class="mb-0">Detalles del Vehículo</h5>
    </div>

    <div class="card-body">
      <div class="row g-3">
        <div class="col-md-6">
          <strong><i class="fas fa-id-card-alt me-1 text-secondary"></i> Placa:</strong><br>
          <?= htmlspecialchars($vehiculo['placa']) ?>
        </div>

        <div class="col-md-6">
          <strong><i class="fas fa-car-side me-1 text-secondary"></i> Modelo:</strong><br>
          <?= htmlspecialchars($vehiculo['modelo']) ?>
        </div>

        <div class="col-md-6">
          <strong><i class="fas fa-calendar-alt me-1 text-secondary"></i> Año:</strong><br>
          <?= htmlspecialchars($vehiculo['anio']) ?>
        </div>

        <div class="col-md-6">
          <strong><i class="fas fa-truck-moving me-1 text-secondary"></i> Tipo:</strong><br>
          <?= htmlspecialchars($vehiculo['tipo']) ?>
        </div>

        <div class="col-md-6">
          <strong><i class="fas fa-industry me-1 text-secondary"></i> Marca ID:</strong><br>
          <?= htmlspecialchars($vehiculo['marca_id']) ?>
        </div>

        <div class="col-md-6">
          <strong><i class="fas fa-building me-1 text-secondary"></i> Empresa:</strong><br>
          <?= htmlspecialchars($vehiculo['empresa']) ?>
        </div>

		<div class="col-md-6">
  <strong><i class="fas fa-building me-1 text-secondary"></i> Estado:</strong><br>
  <?= htmlspecialchars($vehiculo['estado_nombre'], ENT_QUOTES, 'UTF-8') ?>
</div>

        <div class="col-md-12">
          <strong><i class="fas fa-comment-dots me-1 text-secondary"></i> Observaciones:</strong><br>
          <div class="border rounded p-2 bg-light">
            <?= nl2br(htmlspecialchars($vehiculo['observaciones'])) ?>
          </div>
        </div>
      </div>
    </div>

    <div class="card-footer text-end">
      <a href="index.php" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Volver al listado
      </a>
    </div>
  </div>
</div>