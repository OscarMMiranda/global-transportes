<?php
// archivo: /modulos/mantenimiento/tipo_vehiculo/vistas/form_edit.php

// 🔍 Modo depuración (solo en desarrollo)
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../error_log.txt');

// Validación defensiva
if (!isset($vehiculo) || !is_array($vehiculo)) {
    echo '<div class="alert alert-danger">❌ Vehículo no encontrado.</div>';
    return;
}

// Capturar error si viene por GET
$error = isset($_GET['error']) ? htmlspecialchars($_GET['error'], ENT_QUOTES, 'UTF-8') : '';

// Preservar valores si hubo error en POST
$oldNombre      = isset($_POST['nombre'])       ? trim($_POST['nombre'])       : $vehiculo['nombre'];
$oldDescripcion = isset($_POST['descripcion'])  ? trim($_POST['descripcion'])  : $vehiculo['descripcion'];
$oldCategoriaId = isset($_POST['categoria_id']) ? (int) $_POST['categoria_id'] : (int) $vehiculo['categoria_id'];
?>

<!-- Bootstrap 5 y FontAwesome -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<div class="container py-2">
    <div class="card shadow-sm">
       	
		<div class="card-header text-white d-flex align-items-center" 
     		style="background: linear-gradient(90deg, #0d6efd, #0a58ca);">
    		<i class="fas fa-edit fa-lg me-2"></i>
    		<h5 class="mb-0 fw-bold">Editar Tipo de Vehículo</h5>
		</div>

        <div class="card-body">
            <?php if ($error): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle"></i> <?= $error ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form method="post" action="index.php?action=update&id=<?= (int) $vehiculo['id'] ?>">
                <!-- Nombre -->
                <div class="mb-2">
                    <label for="nombre" class="form-label">
                        <i class="fas fa-car"></i> Nombre
                    </label>
                    <input
                        type="text"
                        class="form-control"
                        name="nombre"
                        id="nombre"
                        value="<?= htmlspecialchars($oldNombre, ENT_QUOTES, 'UTF-8') ?>"
                        required
                        maxlength="100"
                    >
                </div>

                <!-- Categoría -->
                <div class="mb-2">
                    <label for="categoria_id" class="form-label">
                        <i class="fas fa-layer-group"></i> Categoría
                    </label>
                    <select class="form-select" name="categoria_id" id="categoria_id" required>
                        <option value="">[Seleccionar]</option>
                        <?php foreach ($categorias as $cat): ?>
                            <option 
                                value="<?= (int) $cat['id'] ?>"
                                <?= $oldCategoriaId === (int) $cat['id'] ? 'selected' : '' ?>
                            >
                                <?= htmlspecialchars($cat['nombre'], ENT_QUOTES, 'UTF-8') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Descripción -->
                <div class="mb-2">
                    <label for="descripcion" class="form-label">
                        <i class="fas fa-align-left"></i> Descripción
                    </label>
                    <textarea
                        class="form-control"
                        name="descripcion"
                        id="descripcion"
                        rows="2"
                        required
                    ><?= htmlspecialchars($oldDescripcion, ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>

                <!-- Última modificación -->
                <div class="mb-2">
                    <label class="form-label"><i class="fas fa-clock"></i> Última modificación</label>
                    <div class="form-control-plaintext">
                        <?= !empty($vehiculo['fecha_modificacion'])
                            ? htmlspecialchars($vehiculo['fecha_modificacion'], ENT_QUOTES, 'UTF-8')
                            : '<span class="text-danger">[Sin fecha registrada]</span>' ?>
                    </div>
                </div>

                <!-- Botones -->
                <div class="d-flex justify-content-end gap-2">
                    <button type="submit" name="actualizar" class="btn btn-success shadow-sm">
                        <i class="fas fa-save"></i> Guardar cambios
                    </button>
                    <a href="index.php" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>