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
    <title>Panel Gerencia – Global Transportes</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- CSS global -->
    <link rel="stylesheet" href="/css/styles.css">

    <!-- CSS del panel gerencia -->
    <link rel="stylesheet" href="/paneles/gerencia/css/panel_gerencia.css">

    <!-- JS del panel gerencia -->
    <script src="/paneles/gerencia/js/panel_gerencia.js" defer></script>
</head>
<body class="bg-light"></body>