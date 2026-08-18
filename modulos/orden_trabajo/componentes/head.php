<?php
// archivo: /modulos/orden_trabajo/componentes/head.php
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- TÍTULO CORPORATIVO -->
    <title>
        <?= isset($pageTitle) ? htmlspecialchars($pageTitle) : 'ERP Corporativo - Órdenes de Trabajo'; ?>
    </title>

    <!-- FAVICON ERP -->
    <link rel="icon" type="image/png" href="/assets/img/favicon.png">

    <!-- BOOTSTRAP 5 -->
    <link 
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        crossorigin="anonymous"
    >

    <!-- FONT AWESOME -->
    <link 
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        crossorigin="anonymous"
        referrerpolicy="no-referrer"
    >

    <!-- ANIMATE CSS -->
    <link 
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
    >

    <!-- GOOGLE FONTS (Inter corporativo) -->
    <link 
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap"
        rel="stylesheet"
    >

    <!-- ESTILOS GLOBALES ERP -->
    <link rel="stylesheet" href="/assets/css/erp_global.css">

    <!-- ESTILOS DEL MÓDULO OT -->
    <link rel="stylesheet" href="/modulos/orden_trabajo/css/orden_trabajo.css">
</head>
