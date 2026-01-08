<?php
// archivo: /modulos/usuarios/controllers/usuarios_controller.php
// --------------------------------------------------------------
// Controlador del módulo Usuarios
// Acciones completas:
// - Listar (con botones según estado)
// - Crear
// - Editar
// - Soft Delete (desactivar)
// - Restaurar
// - Hard Delete (solo roles altos)
// - Roles dinámicos
// --------------------------------------------------------------


// --------------------------------------------------------------
// LISTAR USUARIOS (acciones dinámicas según estado)
// --------------------------------------------------------------
function listarUsuarios($conn, $estado)
{
    $sql = "SELECT 
                u.id,
                u.nombre,
                u.apellido,
                u.usuario,
                u.correo,
                u.rol,
                u.eliminado,
                u.creado_en,
                r.nombre AS rol_nombre
            FROM usuarios u
            LEFT JOIN roles r ON r.id = u.rol
            WHERE u.eliminado = ?
            ORDER BY u.id ASC";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log('❌ listarUsuarios() prepare error: ' . $conn->error);
        return [];
    }

    $stmt->bind_param("i", $estado);
    $stmt->execute();
    $result = $stmt->get_result();

    $rows = [];

    // Solo roles altos pueden hacer hard delete
    $puedeHardDelete = (
        isset($_SESSION['rol']) &&
        in_array($_SESSION['rol'], ['Admin', 'SuperAdmin', 'Root'])
    );

    while ($row = $result->fetch_assoc()) {

        // ------------------------------------------------------
        // BOTONES

        if ($row['eliminado'] == 0) {

            $acciones =
                '<button class="btn btn-sm btn-outline-info me-1 ver-usuario" data-id="' . $row['id'] . '">
                    <i class="fa fa-eye"></i>
                </button>' .

                '<button class="btn btn-sm btn-outline-primary me-1 btn-editar" data-id="' . $row['id'] . '">
                    <i class="fa fa-pencil-alt"></i>
                </button>' .

                '<a href="/modulos/usuarios/acciones/desactivar.php?id=' . $row['id'] . '" 
                    onclick="return confirm(\'¿Desactivar este usuario?\')" 
                    class="btn btn-sm btn-outline-warning">
                    <i class="fa fa-ban"></i>
                </a>';

        } else {

            $acciones =
                '<button class="btn btn-sm btn-outline-info me-1 ver-usuario" data-id="' . $row['id'] . '">
                    <i class="fa fa-eye"></i>
                </button>' .

                '<a href="/modulos/usuarios/acciones/restaurar.php?id=' . $row['id'] . '" 
                    onclick="return confirm(\'¿Restaurar este usuario?\')" 
                    class="btn btn-sm btn-outline-success me-1">
                    <i class="fa fa-undo"></i>
                </a>';

            if ($puedeHardDelete) {
                $acciones .=
                    '<a href="/modulos/usuarios/acciones/eliminar_definitivo.php?id=' . $row['id'] . '" 
                        onclick="return confirm(\'¿ELIMINAR DEFINITIVAMENTE? Esta acción no se puede deshacer.\')" 
                        class="btn btn-sm btn-danger">
                        <i class="fa fa-skull-crossbones"></i>
                    </a>';
            }
        }

        $row['acciones'] = $acciones;
        $rows[] = $row;
    }

    return $rows;
}

// OBTENER UN USUARIO POR ID
function obtenerUsuario($conn, $id)
{
    $sql = "SELECT id, nombre, apellido, usuario, correo, rol, eliminado, creado_en
            FROM usuarios
            WHERE id = ?
            LIMIT 1";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log("❌ obtenerUsuario() prepare error: " . $conn->error);
        return null;
    }

    $stmt->bind_param("i", $id);
    $stmt->execute();

    return $stmt->get_result()->fetch_assoc();
}


// CREAR USUARIO
function crearUsuario($conn, $nombre, $apellido, $usuario, $correo, $rol, $password)
{
    // Validar duplicado de usuario
    $sqlCheckUser = "SELECT id FROM usuarios WHERE usuario = ? LIMIT 1";
    $stmt = $conn->prepare($sqlCheckUser);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $stmt->store_result(); // ← NECESARIO EN PHP 5.6 SIN mysqlnd
    if ($stmt->num_rows > 0) {
        return false;
    }

    // Validar duplicado de correo
    $sqlCheckMail = "SELECT id FROM usuarios WHERE correo = ? LIMIT 1";
    $stmt = $conn->prepare($sqlCheckMail);
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        return false;
    }

    // Insertar usuario
    $sql = "INSERT INTO usuarios 
            (nombre, apellido, usuario, correo, rol, contrasena, eliminado, creado_en)
            VALUES (?, ?, ?, ?, ?, ?, 0, NOW())";

    $stmt = $conn->prepare($sql);
    if (!$stmt) return false;

    $passwordHash = password_hash($password, PASSWORD_BCRYPT);

    // TIPOS CORRECTOS: s s s s i s
    $stmt->bind_param("ssssis",
        $nombre,
        $apellido,
        $usuario,
        $correo,
        $rol,
        $passwordHash
    );

    if (!$stmt->execute()) {
        return false;
    }

    return $stmt->insert_id; // ← DEVUELVE EL ID REAL
}

function editarUsuario($conn, $id, $nombre, $apellido, $usuario, $correo, $rol)
{
    $sql = "UPDATE usuarios
            SET nombre=?, apellido=?, usuario=?, correo=?, rol=?
            WHERE id=?";

    $stmt = $conn->prepare($sql);
    if (!$stmt) throw new Exception("Error prepare: " . $conn->error);

    if (!$stmt->bind_param("sssssi", $nombre, $apellido, $usuario, $correo, $rol, $id)) {
        throw new Exception("Error bind: " . $stmt->error);
    }

    if (!$stmt->execute()) {
        throw new Exception("Error execute: " . $stmt->error);
    }

    return true;
}

// SOFT DELETE (desactivar)
function cambiarEstadoUsuario($conn, $id, $estado)
{
    $sql = "UPDATE usuarios SET eliminado = ? WHERE id = ?";

    $stmt = $conn->prepare($sql);
    if (!$stmt) return false;

    $stmt->bind_param("ii", $estado, $id);

    return $stmt->execute();
}


// --------------------------------------------------------------
// HARD DELETE (DELETE real)
// --------------------------------------------------------------
function eliminarUsuario($conn, $id)
{
    $sql = "DELETE FROM usuarios WHERE id = ?";

    $stmt = $conn->prepare($sql);
    if (!$stmt) return false;

    $stmt->bind_param("i", $id);

    return $stmt->execute();
}


// --------------------------------------------------------------
// OBTENER ROLES
// --------------------------------------------------------------
function obtenerRoles($conn)
{
    $sql = "SELECT id, nombre FROM roles ORDER BY id ASC";
    $result = $conn->query($sql);

    $roles = [];
    while ($row = $result->fetch_assoc()) {
        $roles[] = $row;
    }

    return $roles;
}


function eliminarUsuarioDefinitivo($conn, $id) {

    $sql = "DELETE FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        throw new Exception("Error en prepare(): " . $conn->error);
    }

    $stmt->bind_param("i", $id);

    if (!$stmt->execute()) {
        throw new Exception("Error al ejecutar DELETE: " . $stmt->error);
    }

    return true;
}