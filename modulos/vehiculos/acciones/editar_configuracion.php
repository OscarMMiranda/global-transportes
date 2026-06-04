<?php
// archivo: /modulos/vehiculos/acciones/editar_configuracion.php

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
$conn = getConnection();

$id = intval($_GET['id']);

// ===============================
// CONSULTA PRINCIPAL
// ===============================
$sql = "
    SELECT 
        v.id,
        v.estado_id,
        v.kilometraje,
        v.horas_motor,
        v.prox_mantenimiento,
        v.centro_costo_id,
        v.observaciones
    FROM vehiculos v
    WHERE v.id = $id
    LIMIT 1
";

$res = $conn->query($sql);

if (!$res) {
    echo "<div class='alert alert-danger'>Error SQL: " . $conn->error . "</div>";
    exit;
}

$data = $res->fetch_assoc();

if (!$data) {
    echo "<div class='alert alert-danger'>Vehículo no encontrado</div>";
    exit;
}

// ===============================
// FUNCIONES DE RENDER
// ===============================
function input_text($label, $name, $value) {
    $value = htmlspecialchars($value);
    return "
        <div class='row mb-3'>
            <label class='col-md-4 fw-bold'>$label</label>
            <div class='col-md-8'>
                <input type='number' class='form-control' name='$name' value='$value'>
            </div>
        </div>
    ";
}

function textarea($label, $name, $value) {
    $value = htmlspecialchars($value);
    return "
        <div class='row mb-3'>
            <label class='col-md-4 fw-bold'>$label</label>
            <div class='col-md-8'>
                <textarea class='form-control' name='$name' rows='3'>$value</textarea>
            </div>
        </div>
    ";
}

function select_estado($conn, $selected) {
    $html = "
        <div class='row mb-3'>
            <label class='col-md-4 fw-bold'>Estado operativo</label>
            <div class='col-md-8'>
                <select name='estado_id' class='form-select'>
                    <option value=''>Seleccione...</option>
    ";

    $q = $conn->query("SELECT id, nombre FROM estado_vehiculo ORDER BY nombre");
    while ($r = $q->fetch_assoc()) {
        $sel = ($selected == $r['id']) ? "selected" : "";
        $html .= "<option value='{$r['id']}' $sel>{$r['nombre']}</option>";
    }

    $html .= "
                </select>
            </div>
        </div>
    ";

    return $html;
}

function select_centro_costos($conn, $selected) {
    $html = "
        <div class='row mb-3'>
            <label class='col-md-4 fw-bold'>Centro de costos</label>
            <div class='col-md-8'>
                <select name='centro_costo_id' class='form-select'>
                    <option value=''>Seleccione...</option>
    ";

    $q = $conn->query("SELECT id, nombre FROM centro_costos ORDER BY nombre");
    while ($r = $q->fetch_assoc()) {
        $sel = ($selected == $r['id']) ? "selected" : "";
        $html .= "<option value='{$r['id']}' $sel>{$r['nombre']}</option>";
    }

    $html .= "
                </select>
            </div>
        </div>
    ";

    return $html;
}

?>

<form id="formEditarConfig">

    <input type="hidden" name="id" value="<?php echo $data['id']; ?>">

    <div class="container-fluid">

        <h6 class="fw-bold mt-2">Configuración Operativa</h6>
        <hr>

        <?php
            echo select_estado($conn, $data['estado_id']);
            echo input_text("Kilometraje actual", "kilometraje", $data['kilometraje']);
            echo input_text("Horas de motor", "horas_motor", $data['horas_motor']);
            echo input_text("Próximo mantenimiento (km)", "prox_mantenimiento", $data['prox_mantenimiento']);
            echo select_centro_costos($conn, $data['centro_costo_id']);
            echo textarea("Observaciones operativas", "observaciones", $data['observaciones']);
        ?>

        <div class="text-end mt-4">
            <button type="submit" class="btn btn-primary">Guardar configuración</button>
        </div>

    </div>

</form>

<script>
$("#formEditarConfig").on("submit", function(e){
    e.preventDefault();

    $.post("/modulos/vehiculos/acciones/guardar_configuracion.php",
        $(this).serialize(),
        function(r){

            if (r.ok) {
                notifySuccess("Configuración actualizada", "Los datos operativos fueron guardados correctamente.");
            } else {
                notifyError("Error", r.msg || "No se pudo guardar.");
            }

        },
    "json");
});
</script>
