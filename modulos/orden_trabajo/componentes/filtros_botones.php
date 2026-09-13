<?php
/**
 * archivo: /modulos/orden_trabajo/componentes/filtros_botones.php
 *
 * @var array  $semanas
 * @var string $semana_sel
 */
?>

<div class="card card-corp mb-2">
    <div class="card-body py-2">

        <div class="d-flex justify-content-between align-items-center flex-wrap">

            <!-- 🔵 Filtro Semana -->
            <div class="d-flex align-items-center me-3">
                <label class="form-label fw-bold mb-0 me-2">Semana</label>

                <select id="filtro_semana" class="form-select form-select-sm filtro-corp">
                    <option value="">-- Todas --</option>

                    <?php if (!empty($semanas)): ?>
                        <?php foreach ($semanas as $s): ?>
                            <?php 
                                $sem = $s['semana'];
                                $sel = ($semana_sel == $sem) ? 'selected' : '';
                            ?>
                            <option value="<?php echo htmlspecialchars($sem); ?>" <?php echo $sel; ?>>
                                <?php echo htmlspecialchars($sem); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <!-- 🔵 Botones Corporativos -->
            <div class="d-flex align-items-center" style="gap:10px;">

                <!-- CREAR -->
                <button class="btn btn-corp-primary btn-sm"
                        onclick="abrirModalCrear()"
                        title="Crear nueva Orden de Trabajo">
                    <i class="fa-solid fa-plus fa-sm fa-fw"></i> Crear
                </button>

                <!-- ANULAR -->
                <button class="btn btn-corp-warning btn-sm"
                        onclick="abrirModalAnular()"
                        title="Anular Orden de Trabajo">
                    <i class="fa-solid fa-ban fa-sm fa-fw"></i> Anular
                </button>

                <!-- ELIMINAR -->
                <button class="btn btn-corp-danger btn-sm"
                        onclick="abrirModalEliminar()"
                        title="Eliminar Orden de Trabajo">
                    <i class="fa-solid fa-trash fa-sm fa-fw"></i> Eliminar
                </button>

                <!-- 🔙 VOLVER AL DASHBOARD -->
                <button class="btn btn-corp-secondary btn-sm"
                        onclick="window.location.href='/paneles/admin/controladores/dashboard_controlador.php'"
                        title="Volver al Dashboard">
                    <i class="fa-solid fa-arrow-left fa-sm fa-fw"></i> Dashboard
                </button>

            </div>

        </div>

    </div>
</div>
