<?php
// includes/funciones/clientes.php

function obtenerClientes($conn, $soloActivos = true) {
    $sql = "SELECT * FROM clientes";
    if ($soloActivos) {
        $sql .= " WHERE activo = 1";
    }
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function obtenerClientePorId($conn, $id) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM clientes WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result);
}

function registrarCliente($conn, $data) {
    $stmt = mysqli_prepare($conn, "INSERT INTO clientes (razon_social, ruc, direccion, telefono) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssss", $data['razon_social'], $data['ruc'], $data['direccion'], $data['telefono']);
    return mysqli_stmt_execute($stmt);
}

function actualizarCliente($conn, $id, $data) {
    $stmt = mysqli_prepare($conn, "UPDATE clientes SET razon_social = ?, ruc = ?, direccion = ?, telefono = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "ssssi", $data['razon_social'], $data['ruc'], $data['direccion'], $data['telefono'], $id);
    return mysqli_stmt_execute($stmt);
}

function eliminarCliente($conn, $id) {
    $stmt = mysqli_prepare($conn, "UPDATE clientes SET activo = 0 WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    return mysqli_stmt_execute($stmt);
}

function restaurarCliente($conn, $id) {
    $stmt = mysqli_prepare($conn, "UPDATE clientes SET activo = 1 WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    return mysqli_stmt_execute($stmt);
}


function formatearNombreCliente($cliente) {
    return strtoupper(trim($cliente['razon_social']));
}

function validarRUC($ruc) {
    return preg_match('/^\d{11}$/', $ruc); // Perú: 11 dígitos
}

function estadoClienteColor($activo) {
    return $activo ? 'success' : 'secondary';
}

function registrarHistorialCliente($conn, $usuario, $accion, $clienteId) {
    $sql = "INSERT INTO historial_clientes (usuario, accion, cliente_id, fecha) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $usuario, $accion, $clienteId);
    return $stmt->execute();
}
