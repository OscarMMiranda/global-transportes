<?php
// /modulos/ubigeo/helpers/ubigeo_helper.php

require_once __DIR__ . '/../../../includes/config.php';

/**
 * Retorna conexión activa
 */
function ubigeoConn() {
    return getConnection();
}

/**
 * Obtener todos los departamentos
 */
function obtenerDepartamentos() {
    $conn = ubigeoConn();
    $sql = "SELECT id, nombre FROM departamentos ORDER BY nombre ASC";
    $res = $conn->query($sql);

    $data = array();
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $data[] = $row;
        }
    }
    return $data;
}

/**
 * Obtener provincias por departamento
 */
function obtenerProvincias($departamento_id) {
    $conn = ubigeoConn();

    $stmt = $conn->prepare("
        SELECT id, nombre 
        FROM provincias 
        WHERE departamento_id = ?
        ORDER BY nombre ASC
    ");
    $stmt->bind_param("i", $departamento_id);
    $stmt->execute();
    $res = $stmt->get_result();

    $data = array();
    while ($row = $res->fetch_assoc()) {
        $data[] = $row;
    }

    $stmt->close();
    return $data;
}

/**
 * Obtener distritos por provincia
 */
function obtenerDistritos($provincia_id) {
    $conn = ubigeoConn();

    $stmt = $conn->prepare("
        SELECT id, nombre 
        FROM distritos 
        WHERE provincia_id = ?
        ORDER BY nombre ASC
    ");
    $stmt->bind_param("i", $provincia_id);
    $stmt->execute();
    $res = $stmt->get_result();

    $data = array();
    while ($row = $res->fetch_assoc()) {
        $data[] = $row;
    }

    $stmt->close();
    return $data;
}

/**
 * Obtener nombre de departamento
 */
function obtenerNombreDepartamento($id) {
    if (!$id) return null;

    $conn = ubigeoConn();
    $stmt = $conn->prepare("SELECT nombre FROM departamentos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($nombre);
    $stmt->fetch();
    $stmt->close();

    return $nombre ?: null;
}

/**
 * Obtener nombre de provincia
 */
function obtenerNombreProvincia($id) {
    if (!$id) return null;

    $conn = ubigeoConn();
    $stmt = $conn->prepare("SELECT nombre FROM provincias WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($nombre);
    $stmt->fetch();
    $stmt->close();

    return $nombre ?: null;
}

/**
 * Obtener nombre de distrito
 */
function obtenerNombreDistrito($id) {
    if (!$id) return null;

    $conn = ubigeoConn();
    $stmt = $conn->prepare("SELECT nombre FROM distritos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($nombre);
    $stmt->fetch();
    $stmt->close();

    return $nombre ?: null;
}

/**
 * Obtener registro completo (útil para auditoría)
 */
function obtenerUbigeoCompleto($departamento_id, $provincia_id, $distrito_id) {
    return array(
        'departamento' => obtenerNombreDepartamento($departamento_id),
        'provincia'    => obtenerNombreProvincia($provincia_id),
        'distrito'     => obtenerNombreDistrito($distrito_id)
    );
}

