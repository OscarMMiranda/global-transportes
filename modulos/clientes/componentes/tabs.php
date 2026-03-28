<?php
// archivo: /modulos/clientes/componentes/tabs.php

if (!isset($titulo))    $titulo    = "Módulo";
if (!isset($subtitulo)) $subtitulo = "";
if (!isset($icono))     $icono     = "fa-solid fa-circle-info";
?>

<div class="d-flex justify-content-between align-items-center mb-2">

    <!-- Tabs corporativas -->
    <ul class="nav nav-tabs" id="tabsClientes">
        <li class="nav-item">
            <a class="nav-link active" href="#" data-tab="activos">
                <i class="fa-solid fa-user-check"></i> Activos
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="#" data-tab="inactivos">
                <i class="fa-solid fa-user-xmark"></i> Inactivos
            </a>
        </li>
    </ul>

    <!-- Botón corporativo para crear nuevo cliente -->
    <button class="btn btn-primary" id="btnNuevoCliente">
        <i class="fa-solid fa-user-plus"></i> Nuevo Cliente
    </button>

</div>

<!-- Contenedor donde se cargan las vistas dinámicas -->
<div id="contenedor_tabs"></div>
