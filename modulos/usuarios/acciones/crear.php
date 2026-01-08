    <?php
// archivo: /modulos/usuarios/acciones/crear.php
// --------------------------------------------------------------
// Formulario para crear un nuevo usuario
// Compatible con PHP 5.6 y hosting Ferozo
// --------------------------------------------------------------

require_once __DIR__ . '/../../../includes/config.php';
require_once INCLUDES_PATH . '/permisos.php';
require_once INCLUDES_PATH . '/funciones.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/auditoria.php';
require_once __DIR__ . '/../controllers/usuarios_controller.php';

session_start();
$conn = getConnection();

// Verificar permiso
requirePermiso('usuarios', 'crear');

// Inicializar variables
$errores = [];
$exito = false;

// --------------------------------------------------------------
// PROCESAR FORMULARIO
// --------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre   = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $usuario  = trim($_POST['usuario']);
    $correo   = trim($_POST['correo']);
    $rol      = trim($_POST['rol']);
    $password = trim($_POST['password']);

    // Validaciones simples
    if ($nombre === '')   $errores[] = "El nombre es obligatorio.";
    if ($apellido === '') $errores[] = "El apellido es obligatorio.";
    if ($usuario === '')  $errores[] = "El usuario es obligatorio.";
    if ($correo === '')   $errores[] = "El correo es obligatorio.";
    if ($password === '') $errores[] = "La contraseña es obligatoria.";

    if (empty($errores)) {
        $creado = crearUsuario($conn, $nombre, $apellido, $usuario, $correo, $rol, $password);

        if ($creado) {
            $exito = true;
        } else {
            $errores[] = "No se pudo crear el usuario. Intente nuevamente.";
        }
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/componentes/head.php'; ?>
</head>

<body class="bg-light">

    <?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/componentes/header_global.php'; ?>

    <div class="container mt-4">

        <h2 class="mb-3">
            <i class="fa fa-user-plus text-primary me-2"></i>
            Crear Nuevo Usuario
        </h2>

        <!-- Mensajes -->
        <?php if (!empty($errores)): ?>
            <div class="alert alert-danger">
                <strong>Errores encontrados:</strong>
                <ul class="mb-0">
                    <?php foreach ($errores as $e): ?>
                        <li><?php echo htmlspecialchars($e); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if ($exito): ?>
            <div class="alert alert-success">
                <i class="fa fa-check-circle me-1"></i>
                Usuario creado correctamente.
            </div>
        <?php endif; ?>

        <!-- Formulario -->
        <form method="POST" class="card shadow-sm p-4 bg-white border-0">

            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="nombre" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Apellido</label>
                    <input type="text" name="apellido" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Usuario</label>
                    <input type="text" name="usuario" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Correo electrónico</label>
                    <input type="email" name="correo" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Rol</label>
                    <select name="rol" class="form-select" required>
                        <option value="Admin">Administrador</option>
                        <option value="Operador">Operador</option>
                        <option value="Consulta">Consulta</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Contraseña</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

            </div>

            <div class="mt-4 d-flex justify-content-between">
                <a href="/modulos/usuarios/index.php" class="btn btn-secondary">
                    <i class="fa fa-arrow-left me-1"></i> Volver
                </a>

                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save me-1"></i> Guardar Usuario
                </button>
            </div>

        </form>

    </div>

    <?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/componentes/footer_global.php'; ?>

</body>
</html>