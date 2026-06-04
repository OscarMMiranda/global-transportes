<?php
// archivo: /modulos/infracciones/modales/modal_crear.php

/** @var array $entidades */
?>

<div class="modal fade" id="modalCrear" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Nueva Infracción</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="formCrearInfraccion">

                <div class="modal-body">

                    <div class="row">

                        <div class="col-md-4 mb-3">
                            <label>Código</label>
                            <input type="text" name="codigo" class="form-control">
                        </div>

                        <div class="col-md-8 mb-3">
                            <label>Descripción</label>
                            <input type="text" name="descripcion" class="form-control">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Gravedad</label>
                            <select name="gravedad" class="form-control">
                                <option value="">Seleccione</option>
                                <option value="Muy grave">Muy grave</option>
                                <option value="Grave">Grave</option>
                                <option value="Leve">Leve</option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Puntos</label>
                            <input type="number" name="puntos" class="form-control">
                        </div>

                        <!-- CAMBIO IMPORTANTE: % UIT -->
                        <div class="col-md-4 mb-3">
                            <label>% UIT</label>
                            <input type="number" step="0.01" name="porcentaje_uit" class="form-control">
                        </div>

                        <div class="col-md-12 mb-3">
                            <label>Entidad Emisora</label>
                            <select name="entidad_emisora_id" class="form-control">
                                <option value="">Seleccione</option>
                                <?php foreach($entidades as $e){ ?>
                                    <option value="<?php echo $e['id']; ?>">
                                        <?php echo $e['nombre']; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>

            </form>

        </div>
    </div>
</div>
