<?php
session_start();

// Require header (si usas un header compartido)
// require_once '../../includes/header_erp.php';

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
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>
    <header class="dashboard-header">
        <div class="contenedor">
            <div class="logo">
                <a href="../index.html">
                    <img src="../img/logo.png" alt="Logo Global Transportes" class="logo-img">
                </a>
            </div>
            <h1>ERP Global Transportes</h1>
            <nav>
                <ul class="nav-menu">
                    <li><a href="../index.html" class="btn-nav">🏠 Inicio</a></li>
                    <li><a href="../sistema/logout.php" class="btn-nav">🔒 Cerrar Sesión</a></li>
                    <li><a href="../sistema/panel_admin.php" class="btn-nav">⚙️ Panel Admin</a></li>
                    <li><a href="../documentos/documentos.php" class="btn-nav">📄 Documentos</a></li>
                    <li><a href="../sistema/admin_db.php" class="btn-nav">🛠 Gestionar BD</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="dashboard-container">
        <section class="bienvenida">
            <h2>Bienvenido al ERP, <?= htmlspecialchars($_SESSION['usuario']) ?></h2>
            <p>Accede a la gestión completa de tu empresa de transporte.</p>
        </section>

        <!-- Sección: Gestión Administrativa -->
        <section class="dashboard-section">
            <h3>Gestión Administrativa</h3>
            <div class="dashboard-cards">
                <div class="card-dashboard">
                    <h4>👥 Clientes</h4>
                    <p>Gestiona tus clientes.</p>
                    <a href="../modulos/clientes/clientes.php" class="dashboard-btn">Ir</a>
                </div>
                <div class="card-dashboard">
                    <h4>🧑‍💼 Empleados</h4>
                    <p>Ver o registrar personal de la empresa.</p>
                    <a href="../modulos/empleados/empleados.php" class="dashboard-btn">Ir</a>
                </div>
            </div>
        </section>

        <!-- Sección: Gestión de Conductores -->
        <section class="dashboard-section">
            <h3>Gestión de Conductores</h3>
            <div class="dashboard-cards">
                <div class="card-dashboard">
                    <h4>🚦 Conductores</h4>
                    <p>Registrar y administrar la lista de conductores.</p>
                    <a href="../modulos/conductores/conductores.php" class="dashboard-btn">Ir</a>
                </div>
                <div class="card-dashboard">
                    <h4>🔄 Asignación de Conductores</h4>
                    <p>Gestiona la asignación de conductores a vehículos.</p>
                    <a href="../modulos/asignaciones_conductor/asignaciones.php" class="dashboard-btn">Ir</a>
                </div>
            </div>
        </section>

        <!-- Sección: Operaciones y Logística -->
        <section class="dashboard-section">
            <h3>Operaciones y Logística</h3>
            <div class="dashboard-cards">
                <div class="card-dashboard">
                    <h4>🚛 Vehículos</h4>
                    <p>Registrar y controlar la flota de transporte.</p>
                    <a href="../modulos/vehiculos/vehiculos.php" class="dashboard-btn">Ir</a>
                </div>
                <div class="card-dashboard">
                    <h4>📋 Órdenes de Trabajo (OT)</h4>
                    <p>Gestiona las órdenes de trabajo y los viajes de transporte.</p>
                    <a href="../modulos/orden_trabajo/orden_trabajo.php" class="dashboard-btn">Ir</a>
                </div>
                <div class="card-dashboard">
                    <h4>📄 Documentos Vehiculares</h4>
                    <p>Registrar y administrar documentos de cada vehículo.</p>
                    <a href="../modulos/documentos/documentos.php" class="dashboard-btn">Ir</a>
                </div>
            </div>
        </section>

        <!-- Sección: Localizaciones -->
        <section class="dashboard-section">
            <h3>Localizaciones</h3>
            <div class="dashboard-cards">
                <div class="card-dashboard">
                    <h4>📍 Destinos</h4>
                    <p>Administrar rutas y zonas de entrega.</p>
                    <a href="../modulos/lugares/lugares.php" class="dashboard-btn">Ir</a>
                </div>
            </div>
        </section>

        <!-- Sección: Configuración y Administración -->
        <section class="dashboard-section">
            <h3>Configuración y Administración</h3>
            <div class="dashboard-cards">
                <div class="card-dashboard">
                    <h4>⚙️ Panel Admin</h4>
                    <p>Administrar permisos, usuarios y configuraciones.</p>
                    <a href="../sistema/panel_admin.php" class="dashboard-btn">Ir</a>
                </div>
                <div class="card-dashboard">
                    <h4>🛠 Gestión de Base de Datos</h4>
                    <p>Administrar y optimizar la base de datos.</p>
                    <a href="../sistema/admin_db.php" class="dashboard-btn">Ir</a>
                </div>
            </div>
        </section>

    </main>

    <footer class="footer">
        <p>&copy; 2025 Global Transportes. Todos los derechos reservados.</p>
    </footer>
</body>
</html>

