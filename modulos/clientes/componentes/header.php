<?php
// archivo: /modulos/clientes/componentes/header.php

if (!isset($titulo))    $titulo    = "Módulo";
if (!isset($subtitulo)) $subtitulo = "";
if (!isset($icono))     $icono     = "fa-solid fa-circle-info";
?>

<div class="row mb-2">
    <div class="col-12">
        <h4 class="mb-0">
            <i class="<?php echo htmlspecialchars($icono); ?>"></i>
            <?php echo htmlspecialchars($titulo); ?>
        </h4>
        <small class="text-muted"><?php echo htmlspecialchars($subtitulo); ?></small>
    </div>
</div>
