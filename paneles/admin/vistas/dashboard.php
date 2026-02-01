<?php
// archivo: /paneles/admin/vistas/dashboard.php
// Panel de control principal para administradores del ERP Global Transportes

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

    <!-- Estilos del panel admin -->
    <link rel="stylesheet" href="/paneles/admin/assets/css/dashboard.css">
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
                        
                       
                        <?php include __DIR__ . '/../componentes/tarjetas/tarjeta_documentos_conductores.php'; ?>
                        <?php include __DIR__ . '/../componentes/tarjetas/tarjeta_documentos_vehiculos.php'; ?>
                        <?php include __DIR__ . '/../componentes/tarjetas/tarjeta_documentos_empresas.php'; ?>
                        <?php include __DIR__ . '/../componentes/tarjetas/tarjeta_conductores.php'; ?>
                        <?php include __DIR__ . '/../componentes/tarjetas/tarjeta_vehiculos.php'; ?>
                    </div>
                </section>

                <!-- Sección: Operaciones y Logística -->
                <section class="mb-4">
                    <h3>Operaciones y Logística</h3>
                    <div class="row row-cols-1 row-cols-md-3 g-4">
                        <?php include __DIR__ . '/../componentes/tarjetas/tarjeta_mantenimiento.php'; ?>
                        <?php include __DIR__ . '/../componentes/tarjetas/tarjeta_asignaciones.php'; ?>
                        <?php include __DIR__ . '/../componentes/tarjetas/tarjeta_orden_trabajo.php'; ?>
                    </div>
                </section>

                <!-- Sección: Administración General -->
                <section class="mb-4">
                    <h3>Administración General</h3>
                    <div class="row row-cols-1 row-cols-md-3 g-4">
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