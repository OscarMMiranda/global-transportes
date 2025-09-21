<?php
// archivo: /modulos/orden_trabajo/views/list.php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../../includes/header_erp.php';
$pageTitle = '📋 Listado de Órdenes de Trabajo';
include __DIR__ . '/partials/head.php';

// require_once __DIR__ . '/../controllers/ClienteController.php';
require_once __DIR__ . '/../controllers/ClienteController.php';
// require_once __DIR__ . '/../../clientes/controllers/ClienteController.php';
$clientesActivos = obtenerClientesActivos(); // función que devuelve array con ['id' => ..., 'nombre' => ...]


?>

<body>

<div class="container mt-4">
  <h4 class="text-center text-primary mb-4"><?php echo $pageTitle; ?></h4>

<!-- Botones superiores alineados y compactos -->
<div class="d-flex justify-content-end flex-wrap gap-2 mb-3">
  <a href="/modulos/orden_trabajo/views/create.php" class="btn btn-outline-primary btn-sm">
    <i class="fa-solid fa-plus"></i> Crear
  </a>
  <a href="../controllers/AnularController.php" class="btn btn-outline-warning btn-sm">
    <i class="fa-solid fa-ban"></i> Anular
  </a>
  <a href="../controllers/DeleteController.php" class="btn btn-outline-danger btn-sm">
    <i class="fa-solid fa-trash"></i> Eliminar
  </a>
</div>

  <!-- Pestañas -->
  <ul class="nav nav-tabs mb-3" id="ordenTabs" role="tablist">
  <li class="nav-item" role="presentation">
    <button class="nav-link active" id="activas-tab" data-bs-toggle="tab" data-bs-target="#activas" type="button" role="tab">
      <i class="fas fa-circle text-success me-1"></i> Activas
    </button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="anuladas-tab" data-bs-toggle="tab" data-bs-target="#anuladas" type="button" role="tab">
      <i class="fas fa-circle text-warning me-1"></i> Anuladas
    </button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="eliminadas-tab" data-bs-toggle="tab" data-bs-target="#eliminadas" type="button" role="tab">
      <i class="fas fa-circle text-danger me-1"></i> Eliminadas
    </button>
  </li>
</ul>

  <!-- Contenido de pestañas -->
  <div class="tab-content border rounded shadow-sm p-3 bg-white" id="ordenTabsContent">
    <div class="tab-pane fade show active" id="activas" role="tabpanel">
      <!-- Filtros -->
      <div class="row mb-3">
        <div class="col-md-4">
          <label for="filtroCliente" class="form-label">Filtrar por Cliente</label>
          <select id="filtroCliente" class="form-select">
            <option value="">Todos</option>
            <!-- Opciones dinámicas si querés -->
          </select>
        </div>
        <div class="col-md-4">
          <label for="filtroFecha" class="form-label">Filtrar por Fecha</label>
          <input type="date" id="filtroFecha" class="form-control">
        </div>
        <div class="col-md-4">
          <label for="busquedaGlobal" class="form-label">Buscar</label>
          <input type="text" id="busquedaGlobal" class="form-control" placeholder="🔍 Buscar orden...">
        </div>
      </div>

      <?php include __DIR__ . '/partials/tabla_activa.php'; ?>
    </div>
    <div class="tab-pane fade" id="anuladas" role="tabpanel">
      <?php include __DIR__ . '/partials/tabla_anulada.php'; ?>
    </div>
    <div class="tab-pane fade" id="eliminadas" role="tabpanel">
      <?php include __DIR__ . '/partials/tabla_eliminada.php'; ?>
    </div>
  </div>
</div>

<?php include __DIR__ . '/partials/scripts.php'; ?>
</body>

