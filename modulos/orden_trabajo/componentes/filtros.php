<?php
/**
 * @var array $semanas
 * @var string $semana_sel
 */
// archivo: /modulos/orden_trabajo/componentes/filtros.php
?>

<div class="card mb-2 shadow-sm">
    <div class="card-body py-2">

        <div class="d-flex align-items-center justify-content-between flex-wrap">

            <!-- Filtro Semana -->
            <div class="d-flex align-items-center" style="gap:8px;">
                <label class="form-label fw-bold mb-0">Semana</label>

                <select id="filtroSemana" class="form-select form-select-sm" style="width:130px;">
                    <option value="">Todas</option>

                    <?php if (!empty($semanas)): ?>
                        <?php foreach ($semanas as $s): ?>
                            <option value="<?php echo $s['semana']; ?>"
                                <?php echo ($semana_sel == $s['semana']) ? 'selected' : ''; ?>>
                                <?php echo $s['semana']; ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <!-- Botones superiores -->
            <div class="d-flex align-items-center" style="gap:6px;">

                <a href="/modulos/orden_trabajo/views/create.php"
                   class="btn btn-outline-primary btn-sm px-2 py-1">
                    <i class="fa-solid fa-plus fa-xs"></i> Crear
                </a>

                <button class="btn btn-outline-warning btn-sm px-2 py-1"
                        onclick="abrirModalAnular()">
                    <i class="fa-solid fa-ban fa-xs"></i> Anular
                </button>

                <button class="btn btn-outline-danger btn-sm px-2 py-1"
                        onclick="abrirModalEliminar()">
                    <i class="fa-solid fa-trash fa-xs"></i> Eliminar
                </button>

            </div>

        </div>

    </div>
</div>
