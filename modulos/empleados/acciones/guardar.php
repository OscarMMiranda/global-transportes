<?php
	// archivo: /modulos/empleados/acciones/guardar.php

	header('Content-Type: application/json; charset=utf-8');

	require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
	$conn = getConnection();

	$id              = isset($_POST['e_id']) ? intval($_POST['e_id']) : 0;
	$nombres         = isset($_POST['e_nombres']) ? $_POST['e_nombres'] : '';
	$apellidos       = isset($_POST['e_apellidos']) ? $_POST['e_apellidos'] : '';
	$dni             = isset($_POST['e_dni']) ? $_POST['e_dni'] : '';
	$telefono        = isset($_POST['e_telefono']) ? $_POST['e_telefono'] : '';
	$correo          = isset($_POST['e_correo']) ? $_POST['e_correo'] : '';
	$direccion       = isset($_POST['e_direccion']) ? $_POST['e_direccion'] : '';
	$empresa_id      = isset($_POST['empresa_id']) ? intval($_POST['empresa_id']) : 0;
	$departamento_id = isset($_POST['departamento_id']) ? intval($_POST['departamento_id']) : 0;
	$provincia_id    = isset($_POST['provincia_id']) ? intval($_POST['provincia_id']) : 0;
	$distrito_id     = isset($_POST['distrito_id']) ? intval($_POST['distrito_id']) : 0;
	$fecha_ingreso   = isset($_POST['e_fecha_ingreso']) ? $_POST['e_fecha_ingreso'] : date('Y-m-d');
	$estado          = isset($_POST['e_estado']) ? $_POST['e_estado'] : 'Activo';

	// ============================================================
	// FOTO
	// ============================================================

$foto = null;

if (isset($_FILES['e_foto']) && $_FILES['e_foto']['error'] === 0) {

    $nombreOriginal = $_FILES['e_foto']['name'];
    $extension      = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
    $nuevoNombre    = 'emp_' . time() . '_' . rand(1000, 9999) . '.' . $extension;

    $rutaDestino = $_SERVER['DOCUMENT_ROOT'] . '/uploads/empleados/' . $nuevoNombre;

    if (move_uploaded_file($_FILES['e_foto']['tmp_name'], $rutaDestino)) {
        $foto = '/uploads/empleados/' . $nuevoNombre;
    }
}

if (!$foto && isset($_POST['foto_actual']) && $_POST['foto_actual'] != '') {
    $foto = $_POST['foto_actual'];
}

// ============================================================
// OBTENER DATOS ANTERIORES (ANTES DEL UPDATE)
// ============================================================

$old = null;

if ($id > 0) {
    $sql_old = "SELECT * FROM empleados WHERE id = $id LIMIT 1";
    $res_old = $conn->query($sql_old);
    if ($res_old) {
        $old = $res_old->fetch_assoc();
    }
}

// ============================================================
// INSERT / UPDATE EMPLEADO
// ============================================================

if ($id == 0) {

    $sql = "INSERT INTO empleados 
            (nombres, apellidos, dni, telefono, correo, direccion, empresa_id, distrito_id, provincia_id, departamento_id, fecha_ingreso, estado, foto)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "ssssssiiiisss",
        $nombres,
        $apellidos,
        $dni,
        $telefono,
        $correo,
        $direccion,
        $empresa_id,
        $distrito_id,
        $provincia_id,
        $departamento_id,
        $fecha_ingreso,
        $estado,
        $foto
    );
    $stmt->execute();

    $id = $stmt->insert_id;

} else {

    $sql = "UPDATE empleados SET 
                nombres=?,
                apellidos=?,
                dni=?,
                telefono=?,
                correo=?,
                direccion=?,
                empresa_id=?,
                distrito_id=?,
                provincia_id=?,
                departamento_id=?,
                fecha_ingreso=?,
                estado=?,
                foto=?
            WHERE id=?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "ssssssiiiisssi",
        $nombres,
        $apellidos,
        $dni,
        $telefono,
        $correo,
        $direccion,
        $empresa_id,
        $distrito_id,
        $provincia_id,
        $departamento_id,
        $fecha_ingreso,
        $estado,
        $foto,
        $id
    );
    $stmt->execute();
}

// ============================================================
// GUARDAR ROLES
// ============================================================

$roles = isset($_POST['roles']) ? $_POST['roles'] : array();

$conn->query("DELETE FROM empleado_rol WHERE empleado_id = " . intval($id));

if (is_array($roles)) {
    foreach ($roles as $rol_id) {
        $rol_id = intval($rol_id);
        if ($rol_id > 0) {
            $conn->query("
                INSERT INTO empleado_rol (empleado_id, rol_id, fecha_inicio)
                VALUES ($id, $rol_id, CURDATE())
            ");
        }
    }
}

// ============================================================
// HISTORIAL GENERAL (ERP CORPORATIVO)
// ============================================================

if ($id > 0 && $old) {

    $cambios = [];

    // Campos a comparar
    $campos = [
        "nombres" => $nombres,
        "apellidos" => $apellidos,
        "dni" => $dni,
        "telefono" => $telefono,
        "correo" => $correo,
        "direccion" => $direccion,
        "empresa_id" => $empresa_id,
        "distrito_id" => $distrito_id,
        "provincia_id" => $provincia_id,
        "departamento_id" => $departamento_id,
        "fecha_ingreso" => $fecha_ingreso,
        "estado" => $estado,
        "foto" => $foto
    ];

    foreach ($campos as $campo => $nuevo_valor) {
        $valor_anterior = isset($old[$campo]) ? $old[$campo] : null;

        if ($valor_anterior != $nuevo_valor) {
            $cambios[$campo] = [
                "antes" => $valor_anterior,
                "despues" => $nuevo_valor
            ];
        }
    }

    // ============================================================
    // ROLES (AUDITORÍA)
    // ============================================================

    $roles_antes = [];
    $res_roles = $conn->query("SELECT rol_id FROM empleado_rol WHERE empleado_id = $id");
    while ($r = $res_roles->fetch_assoc()) {
        $roles_antes[] = intval($r['rol_id']);
    }

    $roles_despues = array_map('intval', $roles);

    if ($roles_antes != $roles_despues) {
        $cambios['roles'] = [
            "antes" => $roles_antes,
            "despues" => $roles_despues
        ];
    }

    // ============================================================
    // INSERTAR EN HISTORIAL GENERAL
    // ============================================================

    if (!empty($cambios)) {

        $json = $conn->real_escape_string(json_encode($cambios, JSON_UNESCAPED_UNICODE));

        $usuario_id = isset($_SESSION['usuario_id']) ? intval($_SESSION['usuario_id']) : 0;
        $ip = $_SERVER['REMOTE_ADDR'];

        $sql_hist = "
            INSERT INTO historial 
            (tabla, registro_id, accion, cambios_json, usuario_id, ip, fecha)
            VALUES ('empleados', $id, 'editar', '$json', $usuario_id, '$ip', NOW())
        ";

        $conn->query($sql_hist);
    }
}


// ============================================================
// RESPUESTA FINAL
// ============================================================

echo json_encode(["success" => true]);
