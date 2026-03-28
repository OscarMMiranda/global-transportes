<?php
// archivo: /paneles/admin/vistas/dashboard.php
// Panel de control principal para administradores del ERP Global Transportes

// IMPORTANTE:
// La sesión y el rol YA fueron validados en el controlador.
// No volver a validar aquí para evitar rechazos falsos.
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>ERP - Global Transportes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <!-- Estilos del panel admin -->
    <link rel="stylesheet" href="/paneles/admin/assets/css/dashboard.css">

    <!-- Ajuste para navbar fijo -->
    <style>
        body { padding-top: 70px; }
    </style>
</head>

<body>

    <!-- Navbar superior -->
    <?php include __DIR__ . '/../componentes/navbar.php'; ?>

    <!-- Contenedor principal -->
    <div class="container-fluid app-container">
        <div class="row">

            <!-- Sidebar -->
            <?php include __DIR__ . '/../componentes/sidebar.php'; ?>

            <!-- Contenido principal -->
            <main class="col-md-10 col-lg-12 px-md-4">

                <!-- Sección de Bienvenida -->
                <section class="my-2 p-2 rounded shadow-sm border-start border-0 border-primary bg-white">
                    <div class="d-flex align-items-center justify-content-between flex-wrap">
                        <div>
                            <h1 class="fw-bold mb-0 text-dark">
                                Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario']); ?>
                            </h1>
                            <p class="mb-0 text-muted">
                                Sistema ERP de Gestión para Empresa de Transporte
                            </p>
                        </div>

                        <div class="text-end">
                            <span class="badge bg-primary fs-6 px-3 py-2">
                                Panel Principal
                            </span>
                        </div>
                    </div>
                </section>

                <!-- Sección: Gestión Administrativa -->
                <section class="mb-1">

                    <!-- Encabezado -->
                    <!-- <div class="d-flex align-items-center justify-content-between mb-2">
                          <h3 class="fw-bold text-dark mb-0">
                            <i class="fa fa-briefcase text-primary me-1"></i>
                            Gestión Administrativa
                        </h3> 
                        <span class="badge bg-primary px-3 py-2">
                            Módulo Administrativo
                        </span> 
                    </div> -->

                    <!-- Subgrupo: Documentación -->
                    <div class="mb-3">
    					<h4 class="fw-semibold text-secondary border-bottom pb-2 mb-1">
        					Documentación
    					</h4>

    					<div class="row row-cols-1 row-cols-md-4 g-3 mt-1">
        					<?php include __DIR__ . '/../componentes/tarjetas/tarjeta_documentos_conductores.php'; ?>
        					<?php include __DIR__ . '/../componentes/tarjetas/tarjeta_documentos_vehiculos.php'; ?>
        					<?php include __DIR__ . '/../componentes/tarjetas/tarjeta_documentos_empresas.php'; ?>
        					<?php include __DIR__ . '/../componentes/tarjetas/tarjeta_documentos_empleados.php'; ?>
    					</div>
					</div>
				</section>
                    <!-- Subgrupo: Gestión General -->
                 
				
				
				<section class="mb-2">
					<h4 class="fw-semibold text-secondary border-bottom pb-2">
                            Gestión General
                        </h4>
                        <div class="row row-cols-1 row-cols-md-4 g-3 mt-1">
                            <?php include __DIR__ . '/../componentes/tarjetas/tarjeta_conductores.php'; ?>
                            <?php include __DIR__ . '/../componentes/tarjetas/tarjeta_vehiculos.php'; ?>
                            <?php include __DIR__ . '/../componentes/tarjetas/tarjeta_empleados.php'; ?>
                            <?php include __DIR__ . '/../componentes/tarjetas/tarjeta_asistencias.php'; ?>
                        </div>
                    
                </section>

                <!-- Sección: Operaciones y Logística -->
				<section class="mb-2">
                <h4 class="fw-semibold text-secondary border-bottom pb-2">
                    Operaciones y Logística</h4>
                    <div class="row row-cols-1 row-cols-md-4 g-3 mt-2">
                        <?php include __DIR__ . '/../componentes/tarjetas/tarjeta_mantenimiento.php'; ?>
                        <?php include __DIR__ . '/../componentes/tarjetas/tarjeta_asignaciones.php'; ?>
                        <?php include __DIR__ . '/../componentes/tarjetas/tarjeta_orden_trabajo.php'; ?>
                    </div>
                </section>

                <!-- Sección: Administración General -->
                <section class="mb-2">
                    <h4 class="fw-semibold text-secondary border-bottom pb-2">
						Administración General</h4>
                    <div class="row row-cols-1 row-cols-md-4 g-3 mt-2">
                        <?php include __DIR__ . '/../componentes/tarjetas/tarjeta_clientes.php'; ?>
                        <?php include __DIR__ . '/../componentes/tarjetas/tarjeta_auditoria_bd.php'; ?>
                        <?php include __DIR__ . '/../componentes/tarjetas/tarjeta_erp_completo.php'; ?>
                        <?php include __DIR__ . '/../componentes/tarjetas/tarjeta_permisos.php'; ?>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
