<!-- archivo: /modulos/documentos/componentes/header.php -->
<header class="py-3 border-bottom mb-3">
    <div class="container d-flex justify-content-between align-items-center">
        <h2 class="m-0">📂 Gestión de Documentos</h2>
        <span class="text-muted">
            Usuario: <?php echo isset($_SESSION['usuario']) ? $_SESSION['usuario'] : 'Invitado'; ?>
        </span>
    </div>
</header>
