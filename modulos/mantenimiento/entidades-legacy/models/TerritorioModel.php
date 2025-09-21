<?php
// archivo : /modulos/mantenimiento/entidades/models/TerritorioModel.php

function getDepartamentos($conn) {
    $sql = "SELECT id, nombre FROM departamentos ORDER BY nombre ASC";
    $result = mysqli_query($conn, $sql);
    $departamentos = array();

    while ($row = mysqli_fetch_assoc($result)) {
        $departamentos[] = $row;
    }

    return $departamentos;
}

function getProvinciasPorDepartamento($conn, $departamento_id) {
    $sql = "SELECT id, nombre FROM provincias WHERE departamento_id = $departamento_id ORDER BY nombre ASC";
    $result = mysqli_query($conn, $sql);
    $provincias = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $provincias[] = $row;
    }
    return $provincias;
}

function getDistritosPorProvincia($conn, $provincia_id) {
    $sql = "SELECT id, nombre FROM distritos WHERE provincia_id = $provincia_id ORDER BY nombre ASC";
    $result = mysqli_query($conn, $sql);
    $distritos = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $distritos[] = $row;
    }
    return $distritos;
}



function getProvincias($conn, $departamentoId) {
    $provincias = [];

    if (!$conn || $departamentoId <= 0) {
        error_log("⚠️ getProvincias: conexión inválida o departamentoId vacío");
        return $provincias;
    }

    $sql = "SELECT id, nombre FROM provincias WHERE departamento_id = ? ORDER BY nombre ASC";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log("❌ getProvincias: error en prepare");
        return $provincias;
    }

    $stmt->bind_param("i", $departamentoId);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $provincias[] = $row;
    }

    $stmt->close();
    return $provincias;
}


function getDistritos($conn, $provinciaId) {
    $distritos = [];

    if (!$conn || $provinciaId <= 0) {
        error_log("⚠️ getDistritos: conexión inválida o provinciaId vacío");
        return $distritos;
    }

    $sql = "SELECT id, nombre FROM distritos WHERE provincia_id = ? ORDER BY nombre ASC";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log("❌ getDistritos: error en prepare");
        return $distritos;
    }

    $stmt->bind_param("i", $provinciaId);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $distritos[] = $row;
    }

    $stmt->close();
    return $distritos;
}
?>