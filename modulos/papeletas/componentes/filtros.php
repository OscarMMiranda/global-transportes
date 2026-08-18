<?php
/** @var mysqli $conn */
// archivo: /modulos/papeletas/componentes/filtros.php
?>
<div class="card mb-3">
    <div class="card-body">

        <div class="row">

            <div class="col-md-3">
                <label>Vehículo</label>
                <input type="text" id="filtroVehiculo" class="form-control" placeholder="Placa">
            </div>

            <div class="col-md-3">
                <label>Conductor</label>
                <input type="text" id="filtroConductor" class="form-control" placeholder="Nombre">
            </div>

            <div class="col-md-3">
                <label>Estado</label>
                <select id="filtroEstado" class="form-control">
                    <option value="">Todos</option>

                    <?php
                    // Carga dinámica de estados desde la BD (ERP corporativo)
                    $sqlEstados = "SELECT id, nombre FROM papeleta_estado ORDER BY id ASC";
                    $resEstados = mysqli_query($conn, $sqlEstados);

                    if ($resEstados) {
                        while ($e = mysqli_fetch_assoc($resEstados)) {
                            $idEstado = intval($e['id']);
                            $nomEstado = htmlspecialchars($e['nombre'], ENT_QUOTES, 'UTF-8');
                            echo "<option value='{$idEstado}'>{$nomEstado}</option>";
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="col-md-3">
                <label>&nbsp;</label>
                <button class="btn btn-dark w-100" id="btnBuscar">
                    <i class="fa fa-search"></i> Buscar
                </button>
            </div>

        </div>

    </div>
</div>
