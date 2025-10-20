<?php
// archivo : head.php – Metadatos, estilos y recursos visuales

// Valores por defecto
$title         = isset($pageTitle)       ? $pageTitle       : 'Global Transportes';
$description   = isset($pageDescription) ? $pageDescription : 'Tu descripción por defecto';
$ogTitle       = isset($pageTitle)       ? $pageTitle       : 'Global Transportes S.A.C.';
$ogDescription = isset($pageDescription) ? $pageDescription : 'Logística y transporte de cargas a nivel nacional e internacional.';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="<?php echo $description; ?>" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="<?php echo $ogTitle; ?>" />
    <meta property="og:description" content="<?php echo $ogDescription; ?>" />
    <meta property="og:image" content="/img/og.jpg" />
    <meta name="twitter:card" content="summary_large_image" />

    <title><?php echo $title; ?></title>

    <!-- Favicon -->
    <link rel="icon" href="/img/favicon.ico" />
    <link rel="stylesheet" href="/css/main.min.css" />
    <link rel="stylesheet" href="/css/main.css" />

    <!-- Bootstrap y FontAwesome -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>