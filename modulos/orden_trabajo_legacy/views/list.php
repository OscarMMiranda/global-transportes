<?php
/**
 * archivo : modulos/orden_trabajo/views/list.php
 *
 * @var array $data
 * @var array $ordenesActivas
 * @var array $ordenesAnuladas
 * @var array $ordenesEliminadas
 * @var array $semanas
 * @var string $semana_sel
 */

$ordenesActivas     = isset($data['activas']) ? $data['activas'] : [];
$ordenesAnuladas    = isset($data['anuladas']) ? $data['anuladas'] : [];
$ordenesEliminadas  = isset($data['eliminadas']) ? $data['eliminadas'] : [];

$semanas            = isset($data['semanas']) ? $data['semanas'] : [];
$semana_sel         = isset($data['semana_sel']) ? $data['semana_sel'] : '';

$pageTitle = '📋 Listado de Órdenes de Trabajo';
?>

<!DOCTYPE html>
<html lang="es">

<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/componentes/head.php'; ?>

<body class="bg-light">

<div class="container-fluid mt-3 px-3">

    <!-- BARRA SUPERIOR CORPORATIVA -->
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">

        <!-- TÍTULO -->
        <h3 class="fw-bold text-primary mb-2 mb-md-0">
            <?= $pageTitle ?>
        </h3>

        <!-- BOTONES -->
        <div class="d-flex align-items-center" style="gap:10px;">

            <a href="/modulos/orden_trabajo/views/create.php"
               class="btn btn-primary btn-sm px-3">
                <i class="fa-solid fa-plus me-1"></i> Crear
            </a>

            <button class="btn btn-warning btn-sm px-3"
                    onclick="abrirModalAnular()">
                <i class="fa-solid fa-ban me-1"></i> Anular
            </button>

            <button class="btn btn-danger btn-sm px-3"
                    onclick="abrirModalEliminar()">
                <i class="fa-solid fa-trash me-1"></i> Eliminar
            </button>

        </div>

    </div>

    <!-- BARRA DE FILTROS -->
    <div class="card shadow-sm mb-3">
        <div class="card-body py-2">

            <div class="d-flex align-items-center flex-wrap" style="gap:12px;">

                <!-- Filtro Semana -->
                <div class="d-flex align-items-center" style="gap:8px;">
                    <label class="fw-semibold mb-0">Semana</label>

                    <select id="filtroSemana" class="form-select form-select-sm" style="width:150px;">
                        <option value="">Todas</option>

                        <?php foreach ($semanas as $s): ?>
                            <option value="<?= htmlspecialchars($s['semana']); ?>"
                                <?= ($semana_sel == $s['semana']) ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($s['semana']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

            </div>

        </div>
    </div>

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

<!-- FOOTER GLOBAL ERP -->
<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/componentes/footer_global.php'; ?>

<!-- SCRIPTS DEL MÓDULO OT -->
<?php include __DIR__ . '/../componentes/scripts_listado.php'; ?>

</body>
</html>
