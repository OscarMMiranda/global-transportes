<?php
    // public_html/includes/footer.php
?>
<footer class="footer mt-auto py-3 bg-light">
    <div class="container text-center">
        <p>&copy; <?= date("Y") ?> ERP GLOBAL TRANSPORTES - Todos los derechos reservados</p>
        <nav>
            <a href="../../index.html" class="text-white me-3">Inicio</a>
            <a href="../../nosotros.html" class="text-white me-3">Nosotros</a>
            <a href="../../servicios.html" class="text-white me-3">Servicios</a>
            <a href="../../contacto.html" class="text-white">Contacto</a>
        </nav>
    </div>
</footer>

<!-- Bootstrap 4.6 JS (el correcto para tus modales) -->
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script> -->

<!-- Tus scripts específicos -->
<script src="<?= BASE_URL ?>assets/js/clientes.js" defer></script>

<?php if (defined('MODULE_JS')): ?>
<script src="<?= BASE_URL ?>assets/js/<?= MODULE_JS ?>" defer></script>
<?php endif; ?>

</body>
</html>
