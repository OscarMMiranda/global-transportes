<?php
// ======================================================
//  COMPONENTE: filtro_semana_component.php
//  MÓDULO: Órdenes de Trabajo (OT)
//  RESPONSABILIDAD: Filtro corporativo por semana
//  GLOBAL 2026 — Arquitectura Limpia (Optimizado Visual)
// ======================================================

/**
 * @var array $data
 * $data['semanas']
 * $data['semana_sel']
 */
?>

<div class="ot-filtro-semana d-flex align-items-center gap-2 mb-2"
     data-componente="filtro_semana"
     data-version="4.1">

    <!-- Etiqueta corporativa -->
    <label class="form-label fw-bold mb-0 text-corp-primary">
        <i class="fa-solid fa-calendar-week fa-fw me-1"></i>
        Semana
    </label>

    <!-- Select corporativo -->
    <select id="filtro_semana"
            class="form-select form-select-sm w-auto px-3 filtro-corp-select">

        <option value="">Todas</option>

        <?php foreach ($data['semanas'] as $s): ?>
            <option value="<?php echo htmlspecialchars($s['semana']); ?>"
                <?php echo ($data['semana_sel'] == $s['semana']) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($s['semana']); ?>
            </option>
        <?php endforeach; ?>

    </select>

</div>
