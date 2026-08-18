<?php
// archivo: /modulos/papeletas/componentes/header.php

// Valores por defecto para evitar warnings
if (!isset($tituloPagina))        $tituloPagina = "";
if (!isset($subtituloPagina))     $subtituloPagina = "";
if (!isset($botonPrincipal))      $botonPrincipal = "";
if (!isset($accionesSecundarias)) $accionesSecundarias = "";
if (!isset($breadcrumbs))         $breadcrumbs = [];

// Botón corporativo para volver al Dashboard
$botonVolverDashboard = '
<a href="/paneles/admin/controladores/dashboard_controlador.php" 
   class="btn btn-outline-secondary ms-2">
    <i class="fa fa-arrow-left"></i> Dashboard
</a>';
?>

<div class="container-fluid mt-4">

    <!-- Breadcrumbs corporativos -->
    <?php if (!empty($breadcrumbs)): ?>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <?php foreach ($breadcrumbs as $item): ?>
                    <li class="breadcrumb-item"><?php echo $item; ?></li>
                <?php endforeach; ?>
            </ol>
        </nav>
    <?php endif; ?>

    <div class="row mb-3 align-items-center">

        <div class="col-md-8">
            <h3 class="mb-0"><?php echo $tituloPagina; ?></h3>

            <?php if ($subtituloPagina != ""): ?>
                <small class="text-muted"><?php echo $subtituloPagina; ?></small>
            <?php endif; ?>
        </div>

        <div class="col-md-4 text-end">
            <?php 
                echo $botonPrincipal; 
                echo $accionesSecundarias;
                echo $botonVolverDashboard;
            ?>
        </div>

    </div>

</div>
