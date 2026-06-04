<?php
// archivo: /modulos/orden_trabajo/componentes/filtros.php

// Variables esperadas:
// $semanas      → array de semanas disponibles
// $semana_sel   → semana seleccionada (string o vacío)
?>

<div class="card mb-3 shadow-sm">
    <div class="card-body">

        <div class="row">

            <!-- FILTRO POR SEMANA -->
            <div class="col-md-4 mb-2">
                <label class="form-label fw-bold">Semana</label>
                <select id="filtroSemana" class="form-select form-select-sm">
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

            <!-- ESPACIO PARA FUTUROS FILTROS -->
            <!--
            <div class="col-md-4 mb-2">
                <label class="form-label fw-bold">Cliente</label>
                <select id="filtroCliente" class="form-select form-select-sm">
                    <option value="">Todos</option>
                </select>
            </div>
            -->

        </div>

    </div>
</div>
