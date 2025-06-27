<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Gestión Agencias Aduanas</title>
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    rel="stylesheet"
  />
</head>
<body class="container mt-4">

  <!-- INYECTAMOS BASE_URL PARA JS -->
  <script>
    // Calcula la ruta al módulo (sin backslashes)
    window.BASE_URL = '<?= str_replace("\\", "/", dirname($_SERVER['SCRIPT_NAME'])) ?>';
    console.log('BASE_URL =', BASE_URL);
  </script>

  <h2 class="text-center mb-4">Gestión de Agencias Aduanas</h2>
  <div class="text-center mb-4">
    <a href="../../mantenimiento/mantenimiento.php" class="btn btn-outline-secondary">
      ← Volver a Mantenimiento
    </a>
  </div>
