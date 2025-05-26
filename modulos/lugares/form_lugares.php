<?php require_once '../../includes/conexion.php'; ?>

<?php 
// Obtener datos de la base de datos
$departamentos = $conn->query("SELECT id, nombre FROM departamentos ORDER BY nombre");
$tipos_lugar = $conn->query("SELECT id, nombre FROM tipo_lugares ORDER BY nombre");
?>

<div class="container mt-4">
    <h2 class="text-center text-primary mb-4">📍 Registrar/Editar Lugar</h2>

    <form action="registrar_lugar.php" method="POST">
        
        <div class="mb-3">
            <label class="form-label">Nombre Lugar:</label>
            <input type="text" name="Nombre" class="form-control" value="<?= isset($lugar) ? htmlspecialchars($lugar['nombre']) : '' ?>" style="text-transform: uppercase;" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Tipo Lugar:</label>
            <select name="tipo_id" class="form-control" required>
                <option value="">Seleccione</option>
                <?php while ($tipo = $tipos_lugar->fetch_assoc()): ?>
                    <option value="<?= $tipo['id'] ?>" <?= isset($lugar) && $lugar['tipo_id'] == $tipo['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($tipo['nombre']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Dirección:</label>
            <input type="text" name="Direccion" class="form-control" value="<?= isset($lugar) ? htmlspecialchars($lugar['direccion']) : '' ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Departamento:</label>
            <select name="departamento_id" id="departamento" class="form-control" required>
                <option value="">Seleccione</option>
                <?php while ($dep = $departamentos->fetch_assoc()): ?>
                    <option value="<?= $dep['id'] ?>" <?= isset($lugar) && $lugar['departamento_id'] == $dep['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($dep['nombre']) ?>
                    </option>
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

        <button type="submit" class="btn btn-success">🚀 Guardar Lugar</button>
        
        <?php if (isset($lugar) && !empty($lugar['id'])): ?>
            <a href="crear_local.php?lugar_id=<?= $lugar['id'] ?>" class="btn btn-secondary mt-2">➕ Agregar Locales</a>
        <?php endif; ?>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    // Convertir el texto del campo "Nombre Lugar" a mayúsculas automáticamente
    document.getElementsByName("Nombre")[0].addEventListener("input", function() {
        this.value = this.value.toUpperCase();
    });

    // Cargar provincias y distritos si estamos editando un lugar
    const depId = "<?= isset($lugar) ? $lugar['departamento_id'] : '' ?>";
    const provId = "<?= isset($lugar) ? $lugar['provincia_id'] : '' ?>";
    const distId = "<?= isset($lugar) ? $lugar['distrito_id'] : '' ?>";

    if (depId) {
        $('#provincia').load('../../modulos/ubicaciones/ajax_provincias.php?departamento_id=' + depId, function() {
            $('#provincia').val(provId);
        });
    }
    
    if (provId) {
        $('#distrito').load('../../modulos/ubicaciones/ajax_distritos.php?provincia_id=' + provId, function() {
            $('#distrito').val(distId);
        });
    }

    // Cargar provincias y distritos dinámicamente al cambiar el departamento o la provincia
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

<?php require_once '../../includes/footer_lugar.php'; ?>
