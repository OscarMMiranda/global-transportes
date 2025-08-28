<?php
// vista_listado.php
$flash = getFlash();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Asignaciones de Conductores</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" rel="stylesheet">
    <!-- CSS personalizado -->
    <link href="css/asignaciones.css" rel="stylesheet">
</head>
<body>
<div class="container my-5">
    <h2 class="mb-4 text-primary d-flex align-items-center">
        <i class="fas fa-exchange-alt me-2"></i> Asignaciones
        <small class="text-muted ms-2">(Conductor - Tracto - Remolque)</small>
    </h2>

    <?php if (!empty($flash)): ?>
        <div class="alert alert-<?= sanitize($flash['type']) ?> alert-dismissible fade show" role="alert">
            <i class="fas <?= ($flash['type'] === 'success') ? 'fa-check-circle' : (($flash['type'] === 'danger') ? 'fa-exclamation-triangle' : 'fa-info-circle') ?> me-2"></i>
            <?= sanitize($flash['msg']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="mb-3">
        <a href="asignar_conductor.php" class="btn btn-primary me-2">
            <i class="fas fa-plus"></i> Asignación
        </a>
        <a href="/../../views/dashboard/erp_dashboard.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver al Dashboard
        </a>
    </div>

<!-- Navegación por pestañas -->
<ul class="nav nav-tabs mb-4" id="asignacionesTabs" role="tablist">
  <li class="nav-item" role="presentation">
    <button class="nav-link active" id="activas-tab" data-bs-toggle="tab" data-bs-target="#activas" type="button" role="tab" aria-controls="activas" aria-selected="true">
      <i class="fas fa-car-side me-2"></i> Activas
    </button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="historial-tab" data-bs-toggle="tab" data-bs-target="#historial" type="button" role="tab" aria-controls="historial" aria-selected="false">
      <i class="fas fa-history me-2"></i> Historial
    </button>
  </li>
</ul>



    <!-- Asignaciones Activas -->
    <!-- <h3 class="mt-5 mb-3 text-success d-flex align-items-center border-bottom pb-2 fw-bold">
        <i class="fas fa-car-side me-2"></i> Asignaciones Activas
    </h3> -->

<!-- Contenido de pestañas -->
<div class="tab-content" id="asignacionesTabsContent">


<!-- TAB: Activas -->
<div class="tab-pane fade show active" id="activas" role="tabpanel">
  <div id="activasContainer">
    <?php include __DIR__ . '/tabla_activas.php'; ?>
  </div>
</div>



<!-- TAB: Historial -->
<div class="tab-pane fade" id="historial" role="tabpanel">
  <div id="historialContainer">
    <?php include __DIR__ . '/tabla_historial.php'; ?>
  </div>
</div>


<!-- Modal de confirmación -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="confirmModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i> Confirmar Finalización
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas finalizar esta asignación? Esta acción no eliminará el registro, pero lo marcará como finalizado.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" id="confirmBtn" class="btn btn-danger">Sí, finalizar</button>
            </div>
        </div>
    </div>
</div>

<!-- JS: jQuery, DataTables, Bootstrap y script personalizado -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/asignaciones.js"></script>
</body>
</html>
