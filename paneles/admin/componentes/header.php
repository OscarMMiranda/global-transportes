<?php
// archivo: /paneles/admin/componentes/header.php

// Seguridad mínima: evitar acceso directo
if (!isset($_SESSION)) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin – Global Transportes</title>

    <!-- Bootstrap 5 -->
    <link 
        rel="stylesheet" 
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    >

    <!-- Font Awesome -->
    <link 
        rel="stylesheet" 
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    >

    <!-- Estilos globales del sistema -->
    <link rel="stylesheet" href="/css/styles.css">

    <!-- Estilos específicos del panel admin -->
    <link rel="stylesheet" href="/paneles/admin/css/panel_admin.css">

    <!-- JS del panel admin -->
    <script src="/paneles/admin/js/panel_admin.js" defer></script>

</head>
<body class="bg-light"></body>