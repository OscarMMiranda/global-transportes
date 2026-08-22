<?php
/**
 * @var array $semanas
 * @var string $semana_sel
 */
// archivo: /modulos/orden_trabajo/componentes/filtros.php
?>

<div class="card mb-2 shadow-sm">
    <div class="card-body py-2">

        <div class="d-flex align-items-center flex-wrap" style="gap:12px;">

            <!-- Filtro Semana -->
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

        </div>

    </div>
</div>
