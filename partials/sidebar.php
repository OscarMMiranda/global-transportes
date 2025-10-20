<?php
// archivo : sidebar.php – Menú lateral dinámico y trazable

// Detectar página actual
$paginaActual = basename($_SERVER['PHP_SELF']);

// Menú completo
$menu = array(
    array('Inicio',        'index.php',        'fa-home'),
    array('Quiénes Somos', 'nosotros.php',     'fa-book-open'),
    array('Servicios',     'servicios.php',    'fa-truck'),
    array('Contacto',      'contacto.php',     'fa-address-card'),
    array('Correo',        'correo.php',       'fa-envelope', true),
    array('Sistema',       'login.php',        'fa-lock')
);
?>

<aside
    id="sidebarMenu"
    role="complementary"
    aria-labelledby="titulo-menu"
    class="sidebar bg-light shadow-sm h-100 px-3 py-4 rounded"
    aria-label="Menú lateral"
>
    <section class="py-4 px-2 rounded shadow-sm h-100">
        <h5 id="titulo-menu" class="text-dark fw-bold fs-5 mb-4">
            <i class="fas fa-globe me-2"></i> OPCIONES
        </h5>
        <ul class="nav flex-column gap-2">
            <?php
            foreach ($menu as $item) {
                $label   = $item[0];
                $link    = $item[1];
                $icon    = $item[2];
                $target  = isset($item[3]) && $item[3] ? ' target="_blank"' : '';

                // Excluir la página actual
                if ($paginaActual === $link) {
                    continue;
                }

                echo '<li class="nav-item">';
                echo '<a href="' . $link . '" class="nav-link btn btn-global w-100 text-start d-flex align-items-center px-3 py-2"' . $target . '>';
                echo '<i class="fas ' . $icon . ' me-2"></i>' . $label;
                echo '</a>';
                echo '</li>';
            }
            ?>
        </ul>
    </section>
</aside>