<?php
	// Este archivo debe incluirse en todos los módulos (ej: panel_admin.php)
?>


<nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">


	<div class="container">
    	<a class="navbar-brand d-flex align-items-center" href="../index.html" aria-label="Inicio">
      		<img src="../img/logo.png" alt="Logo Global Transportes" width="250" class="me-2" />
      		<span class="fw-bold fs-5">Global Transportes</span>
    	</a>

    	<button 
  			class="navbar-toggler"         <!-- Clase de Bootstrap para estilo del botón -->
  			type="button"                  <!-- Tipo botón -->
  			data-bs-toggle="collapse"      <!-- Indica que activa un colapso -->
  			data-bs-target="#navMenu"      <!-- El ID del elemento que se colapsa/expande -->
  			aria-controls="navMenu"        <!-- Accesibilidad: indica qué controla -->
  			aria-expanded="false"          <!-- Accesibilidad: estado del menú (false por defecto) -->
  			aria-label="Mostrar navegación"<!-- Descripción accesible para lectores de pantalla -->
		>
  <span class="navbar-toggler-icon"></span> <!-- Ícono del botón (hamburguesa) -->
</button>

    <div class="collapse navbar-collapse" 
		id="navMenu">
      <ul class="navbar-nav ms-auto d-flex align-items-center gap-2">
    <li class="nav-item">
        <a class="btn btn-outline-light" href="usuarios.php">👤 Usuarios</a>
    </li>
    <li class="nav-item">
        <a class="btn btn-light text-primary fw-bold" href="historial_bd.php">🕓 Auditoría</a>
    </li>
</ul>

    </div>
  </div>
</nav>
