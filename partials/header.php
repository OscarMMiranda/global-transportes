<?php
// archivo : header.php – Encabezado visual y navegación principal

$paginaActual = basename($_SERVER['PHP_SELF']);

$menu = array(
    array('Inicio',        'index.php',        'fa-home'),
    array('Quiénes Somos', 'nosotros.php',     'fa-book-open'),
    array('Servicios',     'servicios.php',    'fa-truck'),
    array('Contacto',      'contacto.php',     'fa-address-card'),
    array('Correo',        'https://correo.globaltransportes.com', 'fa-envelope', true),
    array('Sistema',       'login.php',        'fa-lock')
);
?>

<header role="banner" class="bg-primary-subtle py-3 border-bottom">
    <nav role="navigation" class="navbar navbar-expand-lg bg-global fixed-top">
        <div class="container-fluid px-2">

            <!-- Logo y nombre de empresa -->
            <a href="index.php" class="navbar-brand d-flex align-items-center gap-2 text-decoration-none" aria-label="Inicio Global Transportes">
                <img src="/img/logo.png" alt="Logo Global Transportes" class="img-fluid" style="max-height: 50px;" />
                <span class="fw-bold fs-5 mb-0 text-empresa">Global Transportes S.A.C.</span>
            </a>

            <!-- Botón responsive -->
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTop" aria-controls="navbarTop" aria-expanded="false" aria-label="Menú">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Menú principal -->
            <div class="collapse navbar-collapse navbar-dark" id="navbarTop">
                <ul class="navbar-nav ms-auto mb-2 mb-md-0 gap-2">
                    <?php
                    foreach ($menu as $item) {
                        $label   = $item[0];
                        $link    = $item[1];
                        $icon    = $item[2];
                        $target  = isset($item[3]) && $item[3] ? ' target="_blank"' : '';

                        // Excluir la página actual
                        if ($paginaActual === basename($link)) {
                            continue;
                        }

                        echo '<li class="nav-item">';
                        echo '<a href="' . $link . '" class="nav-link btn btn-global px-4 py-2 text-white text-center d-flex align-items-center gap-2"' . $target . '>';
                        echo '<i class="fas ' . $icon . '"></i>' . $label;
                        echo '</a>';
                        echo '</li>';
                    }
                    ?>
                </ul>
            </div>

        </div>
    </nav>
</header>
