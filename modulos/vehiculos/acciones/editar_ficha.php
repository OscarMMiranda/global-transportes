<?php
    // archivo: modulos/vehiculos/acciones/editar_ficha.php

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
$conn = getConnection();

$id = intval($_GET['id']);

// CONSULTA
$sql = "SELECT * FROM vehiculo_ficha_tecnica WHERE vehiculo_id = $id LIMIT 1";
$res = $conn->query($sql);
$data = $res ? $res->fetch_assoc() : null;

// Si no existe, crear registro vacío
if (!$data) {
    $conn->query("INSERT INTO vehiculo_ficha_tecnica (vehiculo_id) VALUES ($id)");
    $data = array();
}

// Función segura PHP 5.6
function v($arr, $key) {
    return isset($arr[$key]) ? htmlspecialchars($arr[$key]) : "";
}
?>

<form id="formFichaTecnica">

<input type="hidden" name="vehiculo_id" value="<?php echo $id; ?>">

<div class="container-fluid">

    <!-- MOTOR -->
    <h6 class="fw-bold mt-2">Motor</h6>
    <hr>

    <div class="row mb-1">
        <label class="col-md-3 fw-bold">Marca Motor</label>
        <div class="col-md-3">
            <input type="text" name="motor_marca" class="form-control" value="<?php echo v($data,'motor_marca'); ?>">
        </div>

        
    </div>

    <div class="row mb-1">
        <label class="col-md-3 fw-bold">Modelo Motor</label>
            <div class="col-md-3">
            <input type="text" name="motor_modelo" class="form-control" value="<?php echo v($data,'motor_modelo'); ?>">
        </div>
    </div>

    <div class="row mb-1">
        <label class="col-md-3 fw-bold">N° Serie Motor</label>
        <div class="col-md-3">
            <input type="text" name="motor_serie" class="form-control" value="<?php echo v($data,'motor_serie'); ?>">
        </div>
    </div>

    <div class="row mb-1">
        <label class="col-md-3 fw-bold">Cilindrada</label>
        <div class="col-md-3">
            <input type="text" name="cilindrada" class="form-control" value="<?php echo v($data,'cilindrada'); ?>">
        </div>

         <label class="col-md-3 fw-bold">Potencia (HP)</label>
        <div class="col-md-3">
            <input type="text" name="potencia_hp" class="form-control" value="<?php echo v($data,'potencia_hp'); ?>">
        </div>
    </div>

    <div class="row mb-1">
       
    </div>

    <!-- CHASIS -->
    <h6 class="fw-bold mt-2">Chasis</h6>
    <hr>

    <div class="row mb-1">
        <label class="col-md-3 fw-bold">Marca Chasis</label>
        <div class="col-md-3">
            <input type="text" name="chasis_marca" class="form-control" value="<?php echo v($data,'chasis_marca'); ?>">
        </div>
    </div>

    <div class="row mb-1">
        <label class="col-md-3 fw-bold">Modelo Chasis</label>
        <div class="col-md-3">
            <input type="text" name="chasis_modelo" class="form-control" value="<?php echo v($data,'chasis_modelo'); ?>">
        </div>
    </div>

    <div class="row mb-1">
        <label class="col-md-3 fw-bold">N° Serie Chasis</label>
        <div class="col-md-3">
            <input type="text" name="chasis_serie" class="form-control" value="<?php echo v($data,'chasis_serie'); ?>">
        </div>
    </div>

    <!-- DIMENSIONES -->
    <h6 class="fw-bold mt-2">Dimensiones</h6>
    <hr>

    <div class="row mb-2">
        <label class="col-md-3 fw-bold">Largo (m)</label>
        <div class="col-md-3">
            <input type="text" name="largo" class="form-control" value="<?php echo v($data,'largo'); ?>">
        </div>
    </div>

    <div class="row mb-2">
        <label class="col-md-3 fw-bold">Ancho (m)</label>
        <div class="col-md-3">
            <input type="text" name="ancho" class="form-control" value="<?php echo v($data,'ancho'); ?>">
        </div>
    </div>

    <div class="row mb-2">
        <label class="col-md-3 fw-bold">Alto (m)</label>
        <div class="col-md-3">
            <input type="text" name="alto" class="form-control" value="<?php echo v($data,'alto'); ?>">
        </div>
    </div>

    <!-- CAPACIDADES -->
    <h6 class="fw-bold mt-2">Capacidades</h6>
    <hr>

    <div class="row mb-1">
        <label class="col-md-3 fw-bold">Peso Bruto Vehicular (PBV)</label>
        <div class="col-md-3">
            <input type="text" name="pbv" class="form-control" value="<?php echo v($data,'pbv'); ?>">
        </div>
    </div>

    <div class="row mb-1">
        <label class="col-md-3 fw-bold">Carga Útil</label>
        <div class="col-md-3">
            <input type="text" name="carga_util" class="form-control" value="<?php echo v($data,'carga_util'); ?>">
        </div>
    </div>

    <div class="row mb-1">
        <label class="col-md-3 fw-bold">N° Ejes</label>
        <div class="col-md-3">
            <input type="text" name="ejes" class="form-control" value="<?php echo v($data,'ejes'); ?>">
        </div>
    </div>

    <div class="text-end mt-2">
        <button type="button" class="btn btn-primary" id="btnGuardarFichaTecnica">
            <i class="fa fa-save me-2"></i>Guardar Ficha Técnica
        </button>
    </div>

</div>

</form>

