<?php
	//  archivo :   /modulos/erp_dashboard.php
	session_start();

	// Verificar acceso solo para administradores
	if (!isset($_SESSION['usuario']) || !isset($_SESSION['rol_nombre']) || $_SESSION['rol_nombre'] !== 'admin') {
    	header("Location: /login.php");
    	exit();
		}
?>


<!DOCTYPE html>
<html lang="es">
	<head>
    	<meta charset="UTF-8">
    	<title>ERP - Global Transportes</title>
    	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    	
		<!-- Bootstrap CSS -->
    	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    	
		<!-- Estilos personalizados -->
    	<link rel="stylesheet" href="../css/dashboard.css">
	</head>
	
	<body>
		<!-- Navbar superior -->
    	<nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
        	<div class="container-fluid">
            	<a class="navbar-brand" href="../index.php">
            		<img src="../img/logo.png" alt="Logo Global Transportes" width="40" class="me-2">
            			ERP Global Transportes
            	</a>
            	<button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                	data-bs-target="#navbarTop" aria-controls="navbarTop" aria-expanded="false" aria-label="Toggle navigation">
                	<span class="navbar-toggler-icon"></span>
            	</button>
            	<div class="collapse navbar-collapse" id="navbarTop">
                	<ul class="navbar-nav ms-auto">
                    	<li class="nav-item"><a class="nav-link" href="../index.php">🏠 Inicio</a></li>
						<li class="nav-item"><a class="nav-link" href="../sistema/logout.php">🔒 Cerrar Sesión</a></li>
                    	<li class="nav-item"><a class="nav-link" href="../paneles/panel_admin.php">⚙️ Panel Admin</a></li>
                    	<li class="nav-item"><a class="nav-link" href="../modulos/documentos/documentos.php">📄 Documentos</a></li>
                    	<li class="nav-item"><a class="nav-link" href="../admin/audits/admin_bd.php">🛠 Gestionar BD</a></li>
                	</ul>
            	</div>
        	</div>
			</nav>

			<!-- Contenedor principal -->
			<div class="container-fluid app-container">
				<div class="row">
        	
            		<!-- Sidebar modular -->
					<?php include '../modulos/componentes/sidebar/sidebar_admin.php'; ?>

            		<!-- Contenido principal -->
					<main class="col-md-8 col-lg-10 px-md-4">
                		<!-- Sección de Bienvenida -->
                		<section class="my-4 p-4 bg-light rounded shadow-sm">
  							<h2 class="fw-bold text-primary">
    							Bienvenido al ERP, <?= htmlspecialchars($_SESSION['usuario']) ?>
  							</h2>
  							<p class="mb-0 text-secondary">
    							Accede a la gestión completa de tu empresa de transporte.
  							</p>
						</section>


               <!-- Sección: Gestión Administrativa -->
               <section class="mb-4">
                  	<h3>Gestión Administrativa</h3>
                  	<div class="row row-cols-1 row-cols-md-3 g-4">
                     	<?php include '../modulos/componentes/tarjetas/tarjeta_orden_trabajo.php'; ?>
						<?php include '../modulos/componentes/tarjetas/tarjeta_conductores.php'; ?>
						<?php include '../modulos/componentes/tarjetas/tarjeta_vehiculos.php'; ?>
                  	</div>
               </section>

               <!-- Sección: Operaciones y Logística -->
               	<section class="mb-4">
                  	<h3>Operaciones y Logística</h3>
                  	<div class="row row-cols-1 row-cols-md-3 g-4">
						<?php include '../modulos/componentes/tarjetas/tarjeta_mantenimiento.php'; ?>
						<?php include '../modulos/componentes/tarjetas/tarjeta_asignaciones.php'; ?>
                        <?php include '../modulos/componentes/tarjetas/tarjeta_documentos_vehiculos.php'; ?>

                    </div>
                </section>

				<!-- Sección: Administración General -->
				<section class="mb-4">
  					<h3>Administración General</h3>
  					<div class="row row-cols-1 row-cols-md-3 g-4">
    					<?php include '../modulos/componentes/tarjetas/tarjeta_clientes.php'; ?>
    					<?php include '../modulos/componentes/tarjetas/tarjeta_auditoria_bd.php'; ?>
    					<?php include '../modulos/componentes/tarjetas/tarjeta_erp_completo.php'; ?>
  					</div>
				</section>
            </main>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer bg-dark text-white text-center py-3">
        <div class="container">
            <p class="mb-0">&copy; 2025 Global Transportes. Todos los derechos reservados.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->
	<!-- Bootstrap CSS -->
<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> 

</body>
</html>