<header 
	role="banner" 
	class="bg-primary-subtle py-3 border-bottom">
  	<nav role="navigation" class="navbar navbar-expand-lg bg-global fixed-top">
    	<div class="container-fluid" px-0>
      		
			<!-- Logo y nombre de empresa -->
			<a href="index.php" class="navbar-brand d-flex align-items-center gap-2" text-decoration-none aria-label="Inicio Global Transportes">
        		<img 
					src		="/img/logo.png" 
					alt		="Logo Global Transportes" 
					class	="img-fluid" 
					style	="max-height: 50px;" 
				/>
        		<span 
					class="fw-bold  fs-5 mb-0 text-empresa">
					Global Transportes S.A.C.
				</span>
      		</a>

			<!-- Botón responsive -->
      		<button 
        		class			="navbar-toggler border-0" 
        		type			="button" 
        		data-bs-toggle	="collapse" 
        		data-bs-target	="#navbarTop" 
        		aria-controls	="navbarTop" 
        		aria-expanded	="false" 
        		aria-label		="Menú">
        		<span class="navbar-toggler-icon"></span>
      		</button>

			<!-- Menú principal -->
      		<div class="collapse navbar-collapse navbar-dark" id="navbarTop">
        		<ul class="navbar-nav ms-auto mb-2 mb-md-0 gap-2">
          			<?php
          				// Menú principal del sitio
          				$menu = [
            				['Inicio',       'index.php'],
            				['Quiénes Somos','nosotros.php'],
            				['Servicios',    'servicios.php'],
            				['Contacto',     'contacto.php'],
            				['Correo',       'https://correo.globaltransportes.com'],
            				['Sistema',      'login.php']
          					];

          				foreach ($menu as $item) {
            				echo '<li class="nav-item">';
            				echo '<a class="nav-link btn btn-sm btn-primary text-white px-3 ms-md-2" href="' . $item[1] . '">' . $item[0] . '</a>';
            				echo '</li>';
          					}
          			?>
        		</ul>
      		</div>
    	</div>
  	</nav>
</header>
