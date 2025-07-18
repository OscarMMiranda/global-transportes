<?php
session_start();

// Verificar acceso solo para administradores
if (!isset($_SESSION['usuario']) || !isset($_SESSION['rol_nombre']) || $_SESSION['rol_nombre'] !== 'admin') {
    header("Location: ../sistema/login.php");
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
            	<a class="navbar-brand" href="../index.html">
            		<img src="../img/logo.png" alt="Logo Global Transportes" width="40" class="me-2">
            			ERP Global Transportes
            	</a>
            	<button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                	data-bs-target="#navbarTop" aria-controls="navbarTop" aria-expanded="false" aria-label="Toggle navigation">
                	<span class="navbar-toggler-icon"></span>
            	</button>
            	<div class="collapse navbar-collapse" id="navbarTop">
                	<ul class="navbar-nav ms-auto">
                    	<li class="nav-item"><a class="nav-link" href="../index.html">🏠 Inicio</a></li>
						<li class="nav-item"><a class="nav-link" href="../sistema/logout.php">🔒 Cerrar Sesión</a></li>
                    	<li class="nav-item"><a class="nav-link" href="../sistema/panel_admin.php">⚙️ Panel Admin</a></li>
                    	<li class="nav-item"><a class="nav-link" href="../modulos/documentos/documentos.php">📄 Documentos</a></li>
                    	<li class="nav-item"><a class="nav-link" href="../sistema/admin_db.php">🛠 Gestionar BD</a></li>
                	</ul>
            	</div>
        	</div>
			</nav>

	<!-- Contenedor principal -->
	<div class="container-fluid app-container">
		<div class="row">
        	
            <!-- Sidebar -->
        	<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block sidebar collapse">
            	<div class="position-sticky pt-3">
					<h2 class="h5 px-3">Menú Principal</h2>
                    <ul class="nav flex-column px-3">
                        <li class="nav-item">
							<a 
								href="../modulos/clientes/index.php"  
								class="nav-link">👥 Clientes
							</a>
						</li>
                        <li class="nav-item">
							<a 
								href="../modulos/asignaciones/index.php" 
								class="nav-link">
    							<i class="fas fa-users me-2"></i> 
								ASIGNACIONES
  							</a>
						</li>
   						<li class="nav-item"><a href="../modulos/orden_trabajo/orden_trabajo.php" class="nav-link">📋 Órdenes de Trabajo</a></li>
                        <li class="nav-item"><a href="../modulos/vehiculos/vehiculos.php" class="nav-link">🚛 Vehículos</a></li>
                        <li class="nav-item"><a href="../modulos/mantenimiento/mantenimiento.php" class="nav-link">🛠 Mantenimiento de Datos</a></li>
                        <li class="nav-item"><a href="../sistema/panel_admin.php" class="nav-link">⚙️ Administración</a></li>
                    </ul>
				</div>
			</nav>

            <!-- Contenido principal -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <!-- Sección de Bienvenida -->
                <section class="my-4">
                    <h2>Bienvenido al ERP, <?= htmlspecialchars($_SESSION['usuario']) ?></h2>
                    <p>Accede a la gestión completa de tu empresa de transporte.</p>
               </section>

               <!-- Sección: Gestión Administrativa -->
               <section class="mb-4">
                  <h3>Gestión Administrativa</h3>
                  <div class="row row-cols-1 row-cols-md-3 g-4">
            
                     <div class="col">
                        <div class="card card-dashboard">
                           <h4>🚛 Vehículos</h4>
                           <p>Registrar y controlar la flota de transporte.</p>
                           <a href="../modulos/vehiculos/vehiculos.php" class="btn btn-primary">Ir</a>
                        </div>
                     </div>
                     <div class="col">
                        <div class="card card-dashboard">
                           <h4>📋 Órdenes de Trabajo (OT)</h4>
                           <p>Gestiona las órdenes de trabajo y los viajes de transporte.</p>
                           <a href="../modulos/orden_trabajo/orden_trabajo.php" class="btn btn-primary">Ir</a>
                        </div>
                     </div>
                     <div class="col">
                        <div class="card card-dashboard">
                           <h4>📄 Conductores</h4>
                           <p>Registrar y administrar conductores.</p>
                           <a href="../modulos/conductores/conductores.php" class="btn btn-primary">Ir</a>
                        </div>
                     </div>
                  </div>

               </section>

               <!-- Sección: Operaciones y Logística -->
               <section class="mb-4">
                  <h3>Operaciones y Logística</h3>
                  <div class="row row-cols-1 row-cols-md-3 g-4">
                        <div class="col">
                            <div class="card card-dashboard p-3">
                                <h4>🚛 Vehículos</h4>
                                <p>Registrar y controlar la flota de transporte.</p>
                                <a href="../modulos/vehiculos/vehiculos.php" class="btn btn-primary">Ir</a>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card card-dashboard p-3">
                                <h4>📋 Asignaciones </h4>
                                <p>Gestiona las órdenes de trabajo y los viajes de transporte.</p>
                                <a href="../modulos/asignaciones_conductor/asignaciones.php" class="btn btn-primary">Ir</a>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card card-dashboard p-3">
                                <h4>📄 Documentos Vehiculares</h4>
                                <p>Registrar y administrar documentos de cada vehículo.</p>
                                <a href="../modulos/documentos/documentos.php" class="btn btn-primary">Ir</a>
                            </div>
                        </div>
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
