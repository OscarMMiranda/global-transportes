<?php
// archivo: /modulos/seguridad/index.php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';

// Validación mínima de sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: /login.php");
    exit;
}

include $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php';

/**
 * Definición modular de las secciones del módulo Seguridad.
 * Para agregar un nuevo submódulo, solo agrega un elemento al array.
 */
$modulos_seguridad = array(
    array(
        'id'          => 'usuarios',
        'ruta'        => 'usuarios/',
        'icono'       => 'bi-people-fill',
        'titulo'      => 'Usuarios',
        'descripcion' => 'Administración de usuarios del sistema'
    ),
    array(
        'id'          => 'roles',
        'ruta'        => 'roles/',
        'icono'       => 'bi-shield-lock-fill',
        'titulo'      => 'Roles',
        'descripcion' => 'Control de roles y permisos'
    ),
    array(
        'id'          => 'auditoria',
        'ruta'        => 'auditoria/',
        'icono'       => 'bi-clipboard-data-fill',
        'titulo'      => 'Auditoría',
        'descripcion' => 'Registro de acciones del sistema'
    ),
    array(
        'id'          => 'permisos',
        'ruta'        => 'permisos/',
        'icono'       => 'bi-key-fill',
        'titulo'      => 'Permisos',
        'descripcion' => 'Asignación de permisos por rol'
    )
);

// Si en el futuro quieres ocultar módulos según rol, aquí es el lugar:
// $modulos_seguridad = filtrar_modulos_por_permiso($modulos_seguridad, $_SESSION['rol_id']);
?>

<div class="container mt-4">

    <h2 class="mb-4">Módulo de Seguridad</h2>

    <div class="row">

        <?php foreach ($modulos_seguridad as $mod): ?>
            <div class="col-md-3 mb-4">
                <a href="<?php echo htmlspecialchars($mod['ruta']); ?>" class="text-decoration-none">
                    <div class="card shadow-sm h-100">
                        <div class="card-body text-center">
                            <i class="bi <?php echo htmlspecialchars($mod['icono']); ?>" style="font-size: 3rem;"></i>
                            <h5 class="mt-3"><?php echo htmlspecialchars($mod['titulo']); ?></h5>
                            <p class="text-muted"><?php echo htmlspecialchars($mod['descripcion']); ?></p>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>

    </div>

</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>