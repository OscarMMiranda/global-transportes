<?php
    // public_html/includes/footer.php
?>
<!-- <footer class="footer bg-dark text-white py-3 mt-4"> -->
<footer class="footer mt-auto py-3 bg-light">
    <div class="container text-center">
        <p>&copy; <?= date("Y") ?> ERP GLOBAL TRANSPORTES - Todos los derechos reservados</p>
        <nav>
            <a href="../../index.php" class="text-white me-3">Inicio</a>
            <a href="../../nosotros.php" class="text-white me-3">Nosotros</a>
            <a href="../../servicios.php" class="text-white me-3">Servicios</a>
            <a href="../../contacto.php" class="text-white">Contacto</a>
        </nav>
    </div>
</footer>

<!-- Enlace a Bootstrap (si lo usas) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

<!-- Bootstrap JS Bundle (Popper incluido) -->
  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
  ></script>

  <!-- Tus scripts específicos -->
  	<script
    	src="<?= BASE_URL ?>assets/js/clientes.js"
    	defer
  	>
	</script>

	<?php if (defined('MODULE_JS')): ?>
  	<script src="<?= BASE_URL ?>assets/js/<?= MODULE_JS ?>" defer></script>
	<?php endif; ?>

	<?php if (defined('MODULE_JS')): ?>
  <script src="<?= BASE_URL ?>assets/js/<?= MODULE_JS ?>" defer></script>
<?php endif; ?>


<!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    <!-- Bootstrap JS (incluye Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- URL de la API para tu módulo -->
    <script>
      window.ASIGNACIONES_API_URL = '/modulos/asignaciones/api.php';
      console.log('API URL:', window.ASIGNACIONES_API_URL);
    </script>

    <!-- Tu script de Asignaciones -->
    <script src="/modulos/asignaciones/js/asignaciones.js" defer></script>

</body>
</html>