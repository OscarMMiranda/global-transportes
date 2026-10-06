<?php
// ======================================================
//  VISTA: ot_nueva_form.php
//  MÓDULO: Órdenes de Trabajo (OT)
//  RESPONSABILIDAD: Formulario Nueva OT
//  GLOBAL 2026 — Arquitectura Limpia (Versión 2.0)
// ======================================================

/**
 * Evita warnings si la vista es abierta directamente.
 * En operación normal, estas variables llegan desde:
 * ot_nueva_form_api.php
 */

if (!isset($data)) {

    $data = array(
        'siguiente_ot' => '',
        'fecha_hoy'    => date('Y-m-d'),
        'clientes'     => array()
    );
}
?>

<form id="formNuevaOT">

    <!-- ============================================= -->
    <!-- CABECERA OT -->
    <!-- ============================================= -->

    <div class="row g-2">

        <div class="col-md-4">

            <label class="form-label fw-bold small">
                N° OT
            </label>

            <input
                type="text"
                id="numero_ot"
                name="numero_ot"
                class="form-control form-control-sm"
                value="<?php echo htmlspecialchars($data['siguiente_ot']); ?>"
                readonly>

        </div>

        <div class="col-md-4">

            <label class="form-label fw-bold small">
                Fecha
            </label>

            <input
                type="date"
                id="fecha_ot"
                name="fecha_ot"
                class="form-control form-control-sm"
                value="<?php echo htmlspecialchars($data['fecha_hoy']); ?>"
                required>

        </div>

        <div class="col-md-4">

            <label class="form-label fw-bold small">
                Semana
            </label>

            <input
                type="text"
                id="semana_ot"
                name="semana_ot"
                class="form-control form-control-sm"
                value="<?php echo date('Y-\WW'); ?>"
                readonly>

        </div>

    </div>

    <!-- ============================================= -->
    <!-- CLIENTE -->
    <!-- ============================================= -->

    <div class="row g-2 mt-2">

        <div class="col-md-12">

            <label class="form-label fw-bold small">
                Cliente
            </label>

            <select
                id="cliente_id"
                name="cliente_id"
                class="form-select form-select-sm"
                required>

                <option value="">
                    Seleccione...
                </option>

                <?php foreach ($data['clientes'] as $c): ?>

                    <option value="<?php echo $c['id']; ?>">

                        <?php
                        echo htmlspecialchars(
                            isset($c['nombre'])
                                ? $c['nombre']
                                : $c['nombre_comercial']
                        );
                        ?>

                    </option>

                <?php endforeach; ?>

            </select>

        </div>

    </div>

    <!-- ============================================= -->
    <!-- DESCRIPCIÓN -->
    <!-- ============================================= -->

    <div class="row g-2 mt-2">

        <div class="col-md-12">

            <label class="form-label fw-bold small">
                Descripción
            </label>

            <textarea
                id="descripcion"
                name="descripcion"
                class="form-control form-control-sm"
                rows="3"
                required></textarea>

        </div>

    </div>

</form>

<script>

// =============================================
// ENFOQUE AUTOMÁTICO
// =============================================

setTimeout(function () {

    $('#cliente_id').focus();

}, 300);

</script>