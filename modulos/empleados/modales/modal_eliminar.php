<!-- archivo: /modulos/empleados/modales/modal_eliminar.php -->

<div class="modal fade" id="modalEliminarEmpleado" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- HEADER -->
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">
                    <i class="fa-solid fa-user-slash me-2"></i>
                    Desactivar empleado
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- BODY -->
            <div class="modal-body">

                <p class="mb-3">
                    ¿Está seguro que desea <strong>desactivar</strong> este empleado?  
                    El empleado no podrá operar y no aparecerá en los listados,  
                    pero su información se conservará.
                </p>

                <ul class="list-group mb-3">
                    <li class="list-group-item">
                        <strong>ID:</strong> <span id="elim_id"></span>
                    </li>
                    <li class="list-group-item">
                        <strong>Nombres:</strong> <span id="elim_nombres"></span>
                    </li>
                    <li class="list-group-item">
                        <strong>DNI:</strong> <span id="elim_dni"></span>
                    </li>
                </ul>

                <input type="hidden" id="elim_id_hidden">

            </div>

            <!-- FOOTER -->
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">
                    Cancelar
                </button>

                <button class="btn btn-warning" id="btnConfirmarEliminar">
                    <i class="fa-solid fa-ban me-1"></i>
                    Desactivar
                </button>
            </div>

        </div>
    </div>
</div>
