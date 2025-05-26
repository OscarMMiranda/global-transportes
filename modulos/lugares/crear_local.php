<?php
require_once '../../includes/conexion.php';

if (!isset($_GET['lugar_id'])) {
    die("<div class='alert alert-danger text-center'>❌ Error: No se ha seleccionado un lugar.</div>");
}

$lugar_id = intval($_GET['lugar_id']);
$lugar = $conn->query("SELECT nombre FROM lugares WHERE id = $lugar_id")->fetch_assoc();
$departamentos = $conn->query("SELECT id, nombre FROM departamentos ORDER BY nombre");
?>

<div class="container mt-4">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white text-center">
            <h2>🏢 Registrar Local en <?= htmlspecialchars($lugar['nombre']) ?></h2>
        </div>

        <div class="card-body">
            <form action="procesar_local.php" method="POST">
                <input type="hidden" name="lugar_id" value="<?= $lugar_id ?>">

                <div class="mb-3">
                    <label class="form-label">Nombre del Local:</label>
                    <input type="text" name="nombre" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Dirección del Local:</label>
                    <input type="text" name="direccion" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Departamento:</label>
                    <select name="departamento_id" id="departamento" class="form-control" required>
                        <option value="">Seleccione</option>
                        <?php while ($dep = $departamentos->fetch_assoc()): ?>
                            <option value="<?= $dep['id'] ?>"><?= htmlspecialchars($dep['nombre']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Provincia:</label>
                    <select name="provincia_id" id="provincia" class="form-control" required>
                        <option value="">Seleccione un departamento</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Distrito:</label>
                    <select name="distrito_id" id="distrito" class="form-control" required>
                        <option value="">Seleccione una provincia</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-success w-100">🚀 Registrar Local</button>
            </form>
        </div>
    </div>

    <div class="card mt-4 shadow">
        <div class="card-header bg-secondary text-white text-center">
            <h3>Locales registrados en este lugar</h3>
        </div>

        <div class="card-body">
            <?php
            $locales = $conn->query("SELECT * FROM locales WHERE lugar_id = $lugar_id");
            if ($locales->num_rows > 0):
            ?>
                <ul class="list-group">
                    <?php while ($local = $locales->fetch_assoc()): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><strong><?= htmlspecialchars($local['nombre']) ?></strong> - <?= htmlspecialchars($local['direccion']) ?></span>
                            <div>
                                <a href="editar_local.php?id=<?= $local['id'] ?>" class="btn btn-warning btn-sm">✏️ Editar</a>
                                <a href="eliminar_local.php?id=<?= $local['id'] ?>&lugar_id=<?= $local['lugar_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que deseas eliminar este local?')">🗑️ Eliminar</a>
                            </div>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p class="text-muted text-center">🔍 No hay locales registrados aún.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="mt-3 text-center">
        <a href="editar_lugar.php?id=<?= $lugar_id ?>" class="btn btn-outline-primary">⬅️ Volver a Lugar</a>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    $('#departamento').change(function () {
        const depId = $(this).val();
        if (depId) {
            $('#provincia').load('../../modulos/ubicaciones/ajax_provincias.php?departamento_id=' + depId);
            $('#distrito').html('<option value="">Seleccione una provincia</option>');
        } else {
            $('#provincia').html('<option value="">Seleccione un departamento</option>');
            $('#distrito').html('<option value="">Seleccione una provincia</option>');
        }
    });

    $('#provincia').change(function () {
        const provId = $(this).val();
        if (provId) {
            $('#distrito').load('../../modulos/ubicaciones/ajax_distritos.php?provincia_id=' + provId);
        } else {
            $('#distrito').html('<option value="">Seleccione una provincia</option>');
        }
    });
});
</script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
