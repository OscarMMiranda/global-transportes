<?php
// archivo: /modulos/conductores/componentes/head.php

// Título del módulo
$titulo = isset($titulo) ? $titulo : 'Módulo Conductores';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- SEO / Seguridad -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="robots" content="noindex, nofollow">

    <title><?= htmlspecialchars($titulo) ?></title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/includes/img/favicon.png">

    <!-- Bootstrap 5.3 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <!-- Font Awesome 6.5 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">

    <!-- DataTables Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <!-- Estilos globales del ERP -->
    <link rel="stylesheet" href="/includes/css/global.css?v=1">

    <!-- Estilos específicos del módulo Conductores -->
    <link rel="stylesheet" href="/modulos/conductores/css/conductores.css?v=1">

    <!-- Variables globales del módulo (útiles para JS) -->
    <script>
        const MODULO = "conductores";
        const API_CONDUCTORES = "/modulos/conductores/controladores/";
    </script>
</head>