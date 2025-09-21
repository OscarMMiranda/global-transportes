<?php
// archivo: /modulos/orden_trabajo/views/partials/head.php
?>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'ERP - Órdenes de Trabajo'; ?></title>

  <!-- Bootstrap 5.3 CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

  <!-- DataTables CSS con Bootstrap -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

  <!-- FontAwesome 6.5 CSS (sin integrity para evitar bloqueos) -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <!-- Estilos personalizados -->
   <link rel="stylesheet" href="<?= BASE_URL ?>/css/estilos.css">
   
  <link rel="stylesheet" href="../../css/orden_trabajo.css">
</head>