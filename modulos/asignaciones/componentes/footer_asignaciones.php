<?php
// archivo: /modulos/asignaciones/componentes/footer_asignaciones.php
?>

        </div>
    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap.min.js"></script>

<!-- Moment.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>

<!-- ============================================================
     ORDEN CORRECTO DE ARCHIVOS DEL MÓDULO ASIGNACIONES
     ============================================================ -->

<!-- 1. API y helpers -->
<script src="/modulos/asignaciones/js/asignaciones.api.js"></script>
<script src="/modulos/asignaciones/js/asignaciones.helpers.js"></script>

<!-- 2. Filtros (DEBE IR ANTES DEL INIT) -->
<script src="/modulos/asignaciones/js/asignaciones.filtros.js"></script>

<!-- 3. Tabla, modals, eventos -->
<script src="/modulos/asignaciones/js/asignaciones.table.js"></script>
<script src="/modulos/asignaciones/js/asignaciones.modals.js"></script>
<script src="/modulos/asignaciones/js/asignaciones.events.js"></script>

<!-- 4. Lógica general del módulo -->
<script src="/modulos/asignaciones/js/asignaciones.main.js"></script>

<!-- 5. INIT (DEBE SER EL ÚLTIMO SIEMPRE) -->
<script src="/modulos/asignaciones/js/asignaciones.init.js"></script>

</body>
</html>
