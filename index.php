<?php
// Define variables para head.php
$pageTitle       = 'Inicio - Global Transportes';
$pageDescription = 'Especialistas en logística y transporte de cargas a nivel nacional.';
include 'partials/head.php';
?>

<body>
  <?php include 'partials/header.php'; ?>

  <div class="app-wrapper">
    <?php include 'partials/sidebar.php'; ?>

    <main id="mainContent" role="main" class="app-content">
      <!-- Hero Section -->
      <section class="hero-section mb-5">
        <div class="container-fluid px-0">
          <div class="row align-items-center gx-0">
            <div class="col-lg-6 p-5">
              <h1 class="display-4">Bienvenidos a Global Transportes</h1>
              <p class="lead">
                Especialistas en logística y transporte de cargas a nivel nacional.
              </p>
              <a href="servicios.php" class="btn btn-primary btn-lg">
                Conocé nuestros servicios
              </a>
            </div>
            <div class="col-lg-6 d-none d-lg-block">
              <img
                src="img/truck_image.jpg"
                alt="Transporte"
                class="img-fluid w-100 h-100 object-fit-cover"
                loading="lazy"
              />
            </div>
          </div>
        </div>
      </section>

      <!-- Ventajas Section -->
      <section class="ventajas py-2 bg-light">
        <h2 class="text-center mb-5">¿Por qué elegirnos?</h2>
        <div class="row g-4">
          <div class="col-sm-6 col-lg-3">
            <div class="card h-100 text-center shadow-sm p-3">
              <h5 class="card-title">🚛 Flota Moderna</h5>
              <p class="card-text">Vehículos equipados con tecnología de punta.</p>
            </div>
          </div>
          <div class="col-sm-6 col-lg-3">
            <div class="card h-100 text-center shadow-sm p-3">
              <h5 class="card-title">⏱ Seguimiento en Tiempo Real</h5>
              <p class="card-text">Estado y ubicación de tu envío en todo momento.</p>
            </div>
          </div>
          <div class="col-sm-6 col-lg-3">
            <div class="card h-100 text-center shadow-sm p-3">
              <h5 class="card-title">📦 Transporte Seguro</h5>
              <p class="card-text">Garantizamos un traslado eficiente y protegido.</p>
            </div>
          </div>
          <div class="col-sm-6 col-lg-3">
            <div class="card h-100 text-center shadow-sm p-3">
              <h5 class="card-title">🌍 Cobertura Nacional</h5>
              <p class="card-text">Llegamos a todos los rincones del país con puntualidad.</p>
            </div>
          </div>
        </div>
      </section>
    </main>
  </div>

  <?php include 'partials/footer.php'; ?>
