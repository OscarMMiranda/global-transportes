<?php
/**
 * Archivo: /modulos/orden_trabajo/componentes/filtro_semana.php
 * Componente corporativo: Filtro de Semana
 * Recibe: $semanas, $semana_sel
 */

if (!function_exists('renderFiltroSemana')) {

    function renderFiltroSemana($semanas, $semana_sel) {
?>
        <div class="filtro-corp d-flex align-items-center">

            <!-- Ícono corporativo -->
            <i class="fa-solid fa-calendar-week fa-sm text-primary me-2" 
               title="Filtrar por semana"></i>

            <!-- Etiqueta -->
            <label class="form-label fw-bold mb-0 me-2">Semana</label>

            <!-- Select corporativo -->
            <select id="filtro_semana" 
                    class="form-select form-select-sm filtro-corp-select">
                <option value="">Todas</option>

                <?php foreach ($semanas as $s): ?>
                    <option value="<?= htmlspecialchars($s['semana']); ?>"
                        <?= ($semana_sel == $s['semana']) ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($s['semana']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

        </div>
<?php
    }

}
?>

