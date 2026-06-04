<?php
// archivo: /modulos/orden_trabajo/views/create.php

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/conexion.php';
$conn = getConnection();

// ===============================
// 🔧 Generación de correlativo anual
// ===============================
$anioActual = date('y');

$sqlUltima = "
    SELECT numero_ot 
    FROM ordenes_trabajo 
    WHERE RIGHT(numero_ot, 2) = '$anioActual'
    ORDER BY id DESC 
    LIMIT 1
";

$res = $conn->query($sqlUltima);

if ($res && $res->num_rows > 0) {
    $ultimoOT = $res->fetch_assoc()['numero_ot'];
    $partes = explode('-', $ultimoOT);
    $correlativo = intval($partes[0]) + 1;
} else {
    $correlativo = 1;
}

$nuevoOT = str_pad($correlativo, 4, '0', STR_PAD_LEFT) . '-' . $anioActual;

// ===============================
// 🔧 Componentes corporativos
// ===============================
include __DIR__ . '/partials/head.php';
include __DIR__ . '/../componentes/header_erp.php';

$pageTitle = "➕ Crear Nueva Orden de Trabajo";
?>

<div class="container mt-4">

    <h2 class="text-center text-success mb-4">
        <?= $pageTitle ?>
    </h2>

    <form id="formCrearOT" class="border p-4 shadow-sm bg-light">

        <div class="row mb-3">

            <div class="col-md-4">
                <label class="form-label">Número OT</label>
                <input type="text" name="numero_ot" class="form-control"
                       value="<?= htmlspecialchars($nuevoOT) ?>" readonly>
            </div>

            <div class="col-md-4">
                <label class="form-label">Fecha</label>
                <input type="date" name="fecha" class="form-control" required>
            </div>

            <div class="col-md-4 position-relative">
                <label class="form-label fw-bold">Cliente</label>

                <!-- AUTOCOMPLETADO AJAX -->
                <input type="text" id="cliente_nombre" class="form-control" autocomplete="off"
                       placeholder="Buscar cliente...">

                <input type="hidden" id="cliente_id" name="cliente_id">

                <div id="listaClientes"
                     class="list-group position-absolute w-100"
                     style="z-index: 9999;"></div>

                <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="btnNuevoCliente">
                    + Registrar Nuevo Cliente
                </button>
            </div>

        </div>

        <div class="row mb-3">

            <div class="col-md-6">
                <label class="form-label fw-bold">Tipo de Orden</label>
                <select id="tipo_ot_id" name="tipo_ot_id" class="form-select" required>
                    <option value="">Cargando...</option>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold">Empresa</label>
                <select id="empresa_id" name="empresa_id" class="form-select" required>
                    <option value="">Cargando...</option>
                </select>
            </div>

        </div>

        <div class="text-center mt-4">
            <button type="submit" class="btn btn-success btn-lg">
                💾 Crear Orden de Trabajo
            </button>

            <a href="/modulos/orden_trabajo/index.php" class="btn btn-secondary btn-lg">
                ↩️ Volver al Listado
            </a>
        </div>

    </form>

</div>

<!-- MODAL NUEVO CLIENTE -->
<?php include __DIR__ . '/../modales/modal_nuevo_cliente.php'; ?>

<!-- SCRIPTS CORRECTOS PARA CREATE -->
<?php include __DIR__ . '/../componentes/scripts_create.php'; ?>

</body>
</html>
