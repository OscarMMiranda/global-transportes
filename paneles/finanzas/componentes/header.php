<?php
if (!isset($_SESSION)) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Finanzas – Global Transportes</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- CSS global -->
    <link rel="stylesheet" href="/css/styles.css">

    <!-- CSS del panel finanzas -->
    <link rel="stylesheet" href="/paneles/finanzas/css/panel_finanzas.css">

    <!-- JS del panel finanzas -->
    <script src="/paneles/finanzas/js/panel_finanzas.js" defer></script>
</head>
<body class="bg-light"></body>