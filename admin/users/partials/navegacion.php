<?php
// archivo: /admin/users/partials/navegacion.php

if (!isset($_SESSION)) {
  session_start();
}
?>

<div class="container-fluid px-0">
  <div class="card shadow-sm border-0 mb-4 rounded-0 w-100">
    <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center bg-white border-0">
      
      <h4 class="fw-bold text-primary mb-2 mb-md-0">
        <i class="fa fa-users me-2 text-secondary"></i> Gestión de Usuarios
      </h4>
      
      <a href="../../paneles/panel_admin.php" class="btn btn-outline-secondary btn-sm">
        <i class="fa fa-arrow-left me-1"></i> Volver al Panel
      </a>
    </div>
  </div>
</div>



<div class="mb-3 d-flex flex-wrap gap-2">
	<button class="btn btn-success btn-sm" id="btnCrearUsuario">
    	<i class="fas fa-user-plus me-1"></i> Nuevo Usuario
    </button>
  	<a href="users.php?exportar=csv" class="btn btn-success">
    	<i class="fa fa-file-csv"></i> Exportar CSV
  	</a>
</div>

<nav class="mb-4">
  	<ul class="nav nav-tabs" id="tabsUsuarios">
    	<li class="nav-item">
      		<button class="nav-link active" id="tabActivos" type="button">Activos</button>
    	</li>
    	<li class="nav-item">
      		<button class="nav-link" id="tabEliminados" type="button">Eliminados</button>
    	</li>
  	</ul>
</nav>

