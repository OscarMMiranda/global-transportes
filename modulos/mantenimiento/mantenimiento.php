<?php
session_start();
require_once '../../includes/conexion.php';

// Verificar acceso solo para administradores
if (!isset($_SESSION['usuario']) || $_SESSION['rol_nombre'] !== 'admin') {
    header("Location: ../sistema/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mantenimiento de Datos</title>
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>
    <header class="dashboard-header">
        <h1>🛠 Mantenimiento de Datos</h1>
        <a href="../sistema/erp_dashboard.php" class="btn-nav">⬅️ Volver al Dashboard</a>
    </header>

    <main class="dashboard-container">
        <section class="dashboard-section">
            <h3>Seleccione la tabla a actualizar</h3>
            <div class="dashboard-cards">
                <div class="card-dashboard">
                    <h4>📦 Tipo de Mercadería</h4>
                    <p>Editar y actualizar tipos de mercadería.</p>
                    <a href="editar_tipo_mercaderia.php" class="dashboard-btn">Actualizar</a>
                </div>
                <div class="card-dashboard">
                    <h4>📦 Categorías de Mercadería</h4>
                    <p>Gestionar categorías de productos.</p>
                    <a href="editar_categorias.php" class="dashboard-btn">Actualizar</a>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer">
        <p>&copy; 2025 Global Transportes. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
