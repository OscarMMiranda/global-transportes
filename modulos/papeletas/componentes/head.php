<?php
// archivo: /modulos/papeletas/componentes/head.php
?>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>
    <?php 
        echo isset($tituloPagina) ? $tituloPagina : (isset($titulo) ? $titulo : "ERP Global Transportes");
    ?>
</title>

<!-- Bootstrap -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

<!-- FontAwesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<!-- CSS del módulo -->
<?php
if (isset($css) && is_array($css)) {
    foreach ($css as $c) {
        echo '<link rel="stylesheet" href="' . $c . '">' . "\n";
    }
}
?>

