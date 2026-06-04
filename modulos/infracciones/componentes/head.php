<?php
// archivo: /modulos/infracciones/componentes/head.php

if (!isset($titulo))    $titulo    = 'Módulo';
if (!isset($subtitulo)) $subtitulo = '';
if (!isset($icono))     $icono     = 'fa-solid fa-circle-info';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title><?php echo $titulo; ?> | ERP Global Transportes</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <!-- Estilos globales -->
    <link rel="stylesheet" href="/includes/css/global.css">

    <!-- Estilos del módulo -->
    <link rel="stylesheet" href="/modulos/infracciones/css/estilos.css">
</head>
