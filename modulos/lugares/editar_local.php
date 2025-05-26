<?php
require_once '../../includes/conexion.php';

if (!isset($_GET['id'])) {
    die("<div class='alert alert-danger text-center'>❌ Error: ID de local no proporcionado.</div>");
}

$id = intval($_GET['id']);
$sql = "SELECT * FROM locales WHERE id = $id";
$result = $conn->query($sql);

if (!$result || $result->num_rows === 0) {
    die("<div class='alert alert-danger text-center'>❌ Error: Local no encontrado.</div>");
}

$local = $result->fetch_assoc();
$distritos = $conn->query("SELECT id, nombre FROM distritos ORDER BY nombre");
?>

<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-header bg-warning text-white text-center">
            <h2>✏️ Editar Local</h2>
        </div>

        <div class="card-body">
            <form action="procesar_edicion_local.php" method="POST">
                <input type="hidden" name="id" value="<?= $local['id'] ?>">
                <input type="hidden" name="lugar_id" value="<?= $local['lugar_id'] ?>">

                <div class="mb-3">
                    <label class="form-label">📌 Nombre del Local:</label>
                    <input type="text" name="nombre" class="form-control border-primary" value="<?= htmlspecialchars($local['nombre']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">📍 Dirección del Local:</label>
                    <input type="text" name="direccion" class="form-control border-secondary" value="<?= htmlspecialchars($local['direccion']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">🌎 Distrito:</label>
                    <select name="distrito_id" class="form-control border-success" required>
                        <option value="">Seleccione</option>
                        <?php while ($dist = $distritos->fetch_assoc()): ?>
                            <option value="<?= $dist['id'] ?>" <?= $local['distrito_id'] == $dist['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($dist['nombre']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-success w-100">✅ Guardar Cambios</button>
            </form>
        </div>

        <div class="card-footer text-center">
            <a href="crear_local.php?lugar_id=<?= $local['lugar_id'] ?>" class="btn btn-outline-primary">⬅️ Volver</a>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
