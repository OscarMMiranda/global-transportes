<?php
// archivo : hero.php – Sección destacada de bienvenida
?>

<section class="hero-section mt-1 mb-2">
    <div class="container-fluid px-0 py-0">
        <div class="row align-items-center gx-0">
            <!-- Texto principal -->
            <div class="col-lg-6 p-3 p-lg-5">
                <h1 class="titulo-principal fw-bold text-primary mb-3">
                    Bienvenidos a M&I Global Transportes
                </h1>
                <p class="lead text-dark mb-4">
                    Especialistas en logística y transporte de cargas a nivel nacional.
                </p>
                <a 
                    href="servicios.php" 
                    class="btn btn-global btn-lg"
                    aria-label="Conoce más sobre nuestros servicios de transporte"
                >
                    Conocé nuestros servicios
                </a>
            </div>

            <!-- Imagen lateral -->
            <div class="col-lg-6 d-none d-lg-block">
                <img
                    src="img/truck_image.jpg"
                    alt="Camión de carga de M&I Global Transportes en carretera"
                    class="img-fluid w-100 h-100 object-fit-cover rounded-start"
                    loading="lazy"
                />
            </div>
        </div>
    </div>
</section>
