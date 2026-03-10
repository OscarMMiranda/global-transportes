<?php
/**
 * Header corporativo del módulo CLIENTES
 * Carga Bootstrap, FontAwesome, DataTables y CSS del módulo
 * Estructura HTML completa y correcta
 */

if (!defined('GT_APP')) {
    define('GT_APP', true);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Módulo Clientes</title>

    <!-- ========================= -->
    <!--  ESTILOS CORPORATIVOS     -->
    <!-- ========================= -->

    <!-- Bootstrap -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <!-- FontAwesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- DataTables -->
    <link rel="stylesheet"
          href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <!-- CSS del módulo -->
    <link rel="stylesheet" href="css/modulo_clientes.css">

</head>

<body class="bg-light">

<!-- ========================= -->
<!--  TÍTULO DEL MÓDULO        -->
<!-- ========================= -->

<div class="modulo-header py-3 px-4 mb-3 bg-white shadow-sm border-bottom">
    <h1 class="titulo-modulo h3 mb-1">
        <i class="fa-solid fa-address-book text-primary me-2"></i>
        Clientes 2.0
    </h1>
    <p class="descripcion-modulo text-muted mb-0">
        Gestión corporativa de clientes
    </p>
</div>
