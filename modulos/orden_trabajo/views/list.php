<?php
/**
 * Variables recibidas desde ListController.php
 *
 * @var array  $data
 * @var array  $ordenesActivas
 * @var array  $ordenesAnuladas
 * @var array  $ordenesEliminadas
 * @var array  $semanas
 * @var string $semanaSeleccionada
 */

$ordenesActivas       = $data['activas'];
$ordenesAnuladas      = $data['anuladas'];
$ordenesEliminadas    = $data['eliminadas'];

$semanas              = $data['semanas'];
$semanaSeleccionada   = $data['semana_sel'];

$pageTitle = '📋 Listado de Órdenes de Trabajo';
?>

<!DOCTYPE html>
<html lang="es">

<?php include __DIR__ . '/../componentes/head.php'; ?>

<body class="bg-light">

    <?php include __DIR__ . '/../componentes/header.php'; ?>

    <div class="container-fluid mt-3 px-3">

        <!-- TÍTULO -->
        <h4 class="text-center text-primary mb-3"><?= $pageTitle ?></h4>

        <!-- FILTROS + BOTONES (compactado) -->
        <?php include __DIR__ . '/../componentes/filtros.php'; ?>

        <!-- TABS -->
        <?php include __DIR__ . '/../componentes/tabs.php'; ?>

        <!-- CONTENIDO DE TABS -->
        <div class="tab-content border rounded shadow-sm p-2 bg-white">

            <div class="tab-pane fade show active" id="activas">
                <?php include __DIR__ . '/partials/tabla_activa.php'; ?>
            </div>

            <div class="tab-pane fade" id="anuladas">
                <?php include __DIR__ . '/partials/tabla_anulada.php'; ?>
            </div>

            <div class="tab-pane fade" id="eliminadas">
                <?php include __DIR__ . '/partials/tabla_eliminada.php'; ?>
            </div>

        </div>

        <!-- MODALES -->
        <?php include __DIR__ . '/../modales/modal_ver.php'; ?>
        <?php include __DIR__ . '/../modales/modal_editar.php'; ?>
        <?php include __DIR__ . '/../modales/modal_anular.php'; ?>
        <?php include __DIR__ . '/../modales/modal_eliminar.php'; ?>

    </div>

    <!-- SCRIPTS -->
    <?php include __DIR__ . '/../componentes/scripts_listado.php'; ?>
    <?php include __DIR__ . '/../componentes/footer.php'; ?>

</body>
</html>
