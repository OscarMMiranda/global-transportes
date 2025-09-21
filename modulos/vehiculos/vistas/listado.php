<?php
// listado.php — Vista principal del módulo Vehículos

if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Evita duplicar sesiones
}

require_once __DIR__ . '/../layout/header_vehiculos.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Vehículos</title>
    <link rel="stylesheet" href="/assets/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/fontawesome.min.css">
</head>

<body>

<!-- ✅ Mensajes de sesión -->
<?php if (isset($_SESSION['msg'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($_SESSION['msg']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
    <?php unset($_SESSION['msg']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($_SESSION['error']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<!-- ✅ Encabezado -->
<div class="card mb-4 shadow-sm">
    <div class="card-body d-flex justify-content-between align-items-center">
        <h2 class="mb-0 text-primary">
            <i class="fas fa-truck me-2"></i> Listado de Vehículos
        </h2>
        <a href="index.php?action=create" class="btn btn-success shadow-sm">
            <i class="fas fa-plus-circle me-1"></i> Nuevo Vehículo
        </a>
    </div>
</div>

<!-- ✅ Navegación por pestañas -->
<ul class="nav nav-tabs mb-3" id="tabVehiculos" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="tab-activos" data-bs-toggle="tab"
                data-bs-target="#activos" type="button" role="tab"
                aria-controls="activos" aria-selected="true">
            <i class="fas fa-check-circle text-success"></i> Activos
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="tab-inactivos" data-bs-toggle="tab"
                data-bs-target="#inactivos" type="button" role="tab"
                aria-controls="inactivos" aria-selected="false">
            <i class="fas fa-times-circle text-danger"></i> Inactivos
        </button>
    </li>
</ul>

<!-- ✅ Contenido de pestañas -->
<div class="tab-content" id="tabVehiculosContent">
    <div class="tab-pane fade show active p-3" id="activos" role="tabpanel" aria-labelledby="tab-activos">
        <?php include __DIR__ . '/tabla_activos.php'; ?>
    </div>
    <div class="tab-pane fade p-3" id="inactivos" role="tabpanel" aria-labelledby="tab-inactivos">
        <?php include __DIR__ . '/tabla_inactivos.php'; ?>
    </div>
</div>

<!-- ✅ Modal para ver detalles -->
<div class="modal fade" id="modalVerVehiculo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle"></i> Detalles del Vehículo
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center text-muted">
                    <i class="fas fa-spinner fa-spin"></i> Cargando...
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer_erp.php'; ?>

<!-- ✅ Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>
<script src="js/vehiculos.js"></script>

<!-- ✅ Auto-cierre de alertas -->
<script>
    setTimeout(() => {
        const alert = document.querySelector('.alert');
        if (alert) {
            alert.classList.remove('show');
            alert.classList.add('fade');
        }
    }, 5000);
</script>

</body>
</html>