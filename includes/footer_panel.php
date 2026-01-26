<?php
// archivo: /includes/footer_panel.php
?>
<footer class="bg-white text-center py-3 mt-auto border-top">
    <div class="container">
        <small class="text-muted d-block mb-2">
            &copy; <?= date("Y") ?> Global Transportes. Todos los derechos reservados.
        </small>

        <a href="/index.php" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-home me-1"></i> Ir al sitio público
        </a>
    </div>
</footer>

<!-- jQuery (NECESARIO para DataTables y tu JS) -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<!-- Scripts globales del ERP -->
<script src="/js/global.js"></script>

<!-- Scripts específicos del módulo -->
<?php if (defined('MODULE_JS')): ?>
    <script src="/modulos/<?= MODULE_JS ?>?v=<?= time() ?>"></script>
<?php endif; ?>

</body>
</html>
