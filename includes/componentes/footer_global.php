<?php
	// archivo: /includes/componentes/footer_global.php
?>

	<!-- ✅ jQuery (siempre primero) -->
	<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

	<!-- ✅ DataTables (depende de jQuery) -->
	<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

	<!-- ✅ Bootstrap 5 (después de jQuery y DataTables) -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

	<!-- ✅ SweetAlert2 -->
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

	<!-- Otros scripts globales -->
	<?php
		// include_once __DIR__ . '/validaciones_globales.js';
		// include_once __DIR__ . '/modales_globales.js';
    ?>

</body>
</html>