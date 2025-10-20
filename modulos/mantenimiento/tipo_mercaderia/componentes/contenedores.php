<?php
	// archivo: componentes/tipo_mercaderia/contenedores.php
	// propósito: contenedores visuales para mostrar los tipos de mercadería activos e inactivos
?>

<!-- Contenido dinámico por pestaña -->
<div class="tab-content">

  	<!-- 🟢 Contenedor de tipos activos -->
  	<div class="tab-pane fade show active" id="activos">
    	<div id="contenedorActivos">
      		<!-- Aquí se inyecta la tabla de tipos activos vía JS -->
    	</div>
  	</div>

  	<!-- 🔴 Contenedor de tipos inactivos -->
  	<div class="tab-pane fade" id="inactivos">
    	<div id="contenedorInactivos">
      		<!-- Aquí se inyecta la tabla de tipos inactivos vía JS -->
    	</div>
  	</div>

</div>