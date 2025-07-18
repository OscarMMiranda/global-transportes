<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="UTF-8" />
  		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	
  		<title><?= $pageTitle ?? 'Global Transportes S.A.C.' ?></title>
  		<meta name="description" content="<?= $pageDescription ?? 'Logística y transporte de cargas a nivel nacional e internacional.' ?>" />

  		<!-- Open Graph (compartir en redes) -->
  		<meta property="og:type" content="website" />
  		<meta property="og:title" content="<?= $pageTitle ?? 'Global Transportes S.A.C.' ?>" />
  		<meta property="og:description" content="<?= $pageDescription ?? 'Logística y transporte de cargas a nivel nacional e internacional.' ?>" />
  		<meta property="og:image" content="https://globaltransportes.com/img/og.jpg" />

  		<!-- Twitter Card -->
  		<meta name="twitter:card" content="summary_large_image" />

  		<!-- Favicon -->
  		<link rel="icon" href="/img/favicon.ico" />
  		<link rel="icon" type="image/png" sizes="32x32" href="/img/favicon-32x32.png" />
  		<link rel="icon" type="image/png" sizes="16x16" href="/img/favicon-16x16.png" />
  		<link rel="manifest" href="/manifest.json" />

  		<!-- Estilos -->
  		<link rel="stylesheet" href="/css/variables.css" />
  		<link rel="preload" href="/css/main.min.css" as="style" />
  		<link rel="stylesheet" href="/css/main.min.css" />

  		<!-- Bootstrap (desde CDN) -->
  		<link
    		rel="stylesheet"
    		href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    		integrity="sha384-..."
    		crossorigin="anonymous"
  		/>

  		<!-- Tipografía personalizada (opcional) -->
  		<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" />
	</head>
