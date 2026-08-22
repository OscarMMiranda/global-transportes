<?php
/**
 * @var array $semanas
 * @var string $semana_sel
 */
// archivo: /modulos/orden_trabajo/componentes/filtros_botones.php
?>

<div class="card mb-2 shadow-sm">
    <div class="card-body py-2">

        <div class="d-flex justify-content-between align-items-center flex-wrap" style="gap:12px;">

            <!-- 🔵 Filtro Semana (IZQUIERDA) -->
            <div class="d-flex align-items-center" style="gap:8px;">
                <label class="form-label fw-bold mb-0">Semana</label>

                <select id="filtroSemana" class="form-select form-select-sm" style="width:130px;">
                    <option value="">Todas</option>

                    <?php if (!empty($semanas)): ?>
                        <?php foreach ($semanas as $s): ?>
                            <option value="<?= htmlspecialchars($s['semana']); ?>"
                                <?= ($semana_sel == $s['semana']) ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($s['semana']); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <!-- 🔵 Botones (DERECHA) -->
            <div class="d-flex align-items-center" style="gap:8px;">

                <!-- CREAR -->
                <a href="/modulos/orden_trabajo/views/create.php" 
                   class="btn btn-outline-primary btn-sm px-3 py-1">
                    <i class="fa-solid fa-plus"></i> Crear
                </a>

                <!-- ANULAR -->
                <button class="btn btn-outline-warning btn-sm px-3 py-1"
                        onclick="abrirModalAnular()">
                    <i class="fa-solid fa-ban"></i> Anular
                </button>

                <!-- ELIMINAR -->
                <button class="btn btn-outline-danger btn-sm px-3 py-1"
                        onclick="abrirModalEliminar()">
                    <i class="fa-solid fa-trash"></i> Eliminar
                </button>

            </div>

        </div>

    </div>
</div>
