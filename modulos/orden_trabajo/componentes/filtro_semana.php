<?php
/**
 * Componente corporativo: Filtro de Semana
 * Recibe: $semanas, $semana_sel
 */

if (!function_exists('renderFiltroSemana')) {

    function renderFiltroSemana($semanas, $semana_sel) {
?>
        <div class="d-flex align-items-center gap-2 ms-3">
            <label class="form-label mb-0">Semana</label>
            <select id="filtro_semana" class="form-select form-select-sm w-auto">
                <option value="">Todas</option>
                <?php foreach ($semanas as $s): ?>
                    <option value="<?= $s['semana']; ?>" <?= ($semana_sel == $s['semana']) ? 'selected' : ''; ?>>
                        <?= $s['semana']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
<?php
    }

}
