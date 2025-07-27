<!DOCTYPE html>
<html lang="es">
	<head>
		<!-- Define la codificación de caracteres -->
  		<meta charset="UTF-8" />
		
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	
		<?php
    	// Valores por defecto
    	$title         = isset($pageTitle)       ? $pageTitle       : 'Global Transportes';
    	$description   = isset($pageDescription) ? $pageDescription : 'Tu descripción por defecto';
    	$ogTitle       = isset($pageTitle)       ? $pageTitle       : 'Global Transportes S.A.C.';
    	$ogDescription = isset($pageDescription) ? $pageDescription : 'Logística y transporte de cargas a nivel nacional e internacional.';
  		?>

		<title><?php echo $title; ?></title>
		
		<meta name="description" content="<?php echo $description; ?>">

  		<!-- Open Graph (compartir en redes) -->
  		<meta property="og:type"        content="website" />
  		<meta property="og:title"       content="<?php echo $ogTitle; ?>" />
  		<meta property="og:description" content="<?php echo $ogDescription; ?>" />
  		<meta property="og:image"       content="https://globaltransportes.com/img/og.jpg" />

  		<!-- Twitter Card -->
  		<meta name="twitter:card" content="summary_large_image" />

  		<!-- Favicon -->
  		<link rel="icon" 		href="/img/favicon.ico" />
  		<link rel="icon" 		type="image/png" sizes="32x32" href="/img/favicon-32x32.png" />
  		<link rel="icon" 		type="image/png" sizes="16x16" href="/img/favicon-16x16.png" />
  		<link rel="manifest" 	href="/manifest.json" />

  		<!-- Estilos -->
  		<link rel="stylesheet" 	href="/css/variables.css" />
		<link rel="stylesheet" 	href="/css/reset.css">
		<link rel="stylesheet" 	href="/css/typography.css">
		<link rel="stylesheet" 	href="/css/navbar.css">
		<link rel="stylesheet" 	href="css/grid.css">
		<link rel="stylesheet" 	href="css/sidebar.css">
		<link rel="stylesheet" 	href="css/footer.css"> 
  		<link rel="preload" 	href="/css/main.min.css" as="style" />
  		<link rel="stylesheet" 	href="/css/main.min.css" />
		<link rel="stylesheet" 	href="/css/main.css" />

  		<!-- Bootstrap (desde CDN) -->
  		<link
    		rel="stylesheet"
    		href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    		integrity="sha384-..."
    		crossorigin="anonymous"
  		/>

  		<!-- Tipografía personalizada (opcional) -->
  		<link rel="stylesheet" 	href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" />
	
		<!-- CDN de Font Awesome -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

	</head>
