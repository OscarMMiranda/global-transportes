<?php
// archivo: /modulos/vehiculos/vistas/fotos.php

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Validación del ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("<div class='alert alert-danger'>ID inválido</div>");
}

$id = intval($_GET['id']);

// Conexión correcta
require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/config.php";

$conn = getConnection();
if (!$conn) {
    die("<div class='alert alert-danger'>Error de conexión a la base de datos.</div>");
}

// Consulta REAL según tu estructura
$sql = "
    SELECT 
        id_foto,
        ruta_archivo,
        descripcion,
        creado_en,
        creado_por
    FROM vehiculo_fotos
    WHERE id_vehiculo = $id
    ORDER BY id_foto DESC
";

$result = $conn->query($sql);
?>

<h5 class="section-title mb-3">
    <i class="fa-solid fa-image me-2 text-primary"></i>
    Galería de fotos
</h5>

<?php if (!$result || $result->num_rows === 0): ?>

    <div class="alert alert-info">
        No hay fotos registradas para este vehículo.
    </div>

<?php else: ?>

    <div class="row g-3 fotos-grid">

        <?php while ($row = $result->fetch_assoc()): ?>

            <div class="col-6 col-md-4 col-lg-3">
                <div class="foto-card shadow-sm">

                    <a href="<?= htmlspecialchars($row['ruta_archivo']) ?>" target="_blank">
                        <img src="<?= htmlspecialchars($row['ruta_archivo']) ?>" class="foto-img" alt="Foto del vehículo">
                    </a>

                    <?php if (!empty($row['descripcion'])): ?>
                        <div class="foto-desc small text-muted mt-1">
                            <?= htmlspecialchars($row['descripcion']) ?>
                        </div>
                    <?php endif; ?>

                    <div class="foto-meta small text-muted mt-1">
                        <i class="fa-regular fa-clock me-1"></i>
                        <?= htmlspecialchars($row['creado_en']) ?>
                    </div>

                </div>
            </div>

        <?php endwhile; ?>

    </div>

<?php endif; ?>