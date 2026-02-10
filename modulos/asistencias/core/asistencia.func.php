<?php
// archivo: /modulos/asistencias/core/asistencia.func.php

// ... aquí pueden ir otras funciones de asistencia ...

function obtener_asistencias(mysqli $conn)
{
    $sql = "SELECT 
                ac.id,
                ac.fecha,
                ac.hora_entrada,
                ac.hora_salida,
                c.nombre_completo AS conductor,
                t.nombre AS tipo
            FROM asistencia_conductores ac
            INNER JOIN conductores c   ON c.id = ac.conductor_id
            INNER JOIN asistencia_tipos t ON t.id = ac.tipo_id
            ORDER BY ac.fecha DESC, c.nombre_completo ASC";

    $result = $conn->query($sql);

    if (!$result) {
        // Si falla, devolvemos arreglo vacío para no romper la vista
        return [];
    }

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    return $data;
}

function registrar_asistencia($conn, $data)
{
    $conductor_id = intval($data['conductor_id']);
    $fecha        = $data['fecha'];
    $codigo_tipo  = $data['codigo_tipo'];
    $hora_entrada = $data['hora_entrada'];
    $hora_salida  = $data['hora_salida'];
    $observacion  = $data['observacion'];

    $id_tipo = tipo_id_por_codigo($conn, $codigo_tipo);
    if ($id_tipo == 0) {
        return array('ok' => false, 'error' => 'Tipo de asistencia inválido');
    }

    // Validar duplicado
    $sqlCheck = "SELECT id FROM asistencia_conductores WHERE conductor_id = ? AND fecha = ? LIMIT 1";
    $stmt = $conn->prepare($sqlCheck);
    $stmt->bind_param("is", $conductor_id, $fecha);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        return array('ok' => false, 'error' => 'Ya existe asistencia para este día');
    }

    $stmt->close();

    // Insertar
    $sql = "INSERT INTO asistencia_conductores
            (conductor_id, fecha, tipo_id, hora_entrada, hora_salida, observacion)
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isisss",
        $conductor_id,
        $fecha,
        $id_tipo,
        $hora_entrada,
        $hora_salida,
        $observacion
    );

    if (!$stmt->execute()) {
        return array('ok' => false, 'error' => $stmt->error);
    }

    return array('ok' => true);
}

function registrar_dia_no_laborable($conn, $data)
{
    $fecha = $data['fecha'];
    $motivo = $data['motivo'];

    $sql = "INSERT INTO dias_no_laborables (fecha, motivo) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $fecha, $motivo);

    if (!$stmt->execute()) {
        return array('ok' => false, 'error' => $stmt->error);
    }

    return array('ok' => true);
}

function registrar_permiso($conn, $data)
{
    $conductor_id = intval($data['conductor_id']);
    $fecha        = $data['fecha'];
    $motivo       = $data['motivo'];

    $sql = "INSERT INTO permisos (conductor_id, fecha, motivo)
            VALUES (?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $conductor_id, $fecha, $motivo);

    if (!$stmt->execute()) {
        return array('ok' => false, 'error' => $stmt->error);
    }

    return array('ok' => true);
}

// Función para registrar vacaciones (rango de fechas)
function registrar_vacacion($conn, $data)
{
    $conductor_id = intval($data['conductor_id']);
    $desde        = $data['desde'];
    $hasta        = $data['hasta'];

    $sql = "INSERT INTO vacaciones (conductor_id, desde, hasta)
            VALUES (?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $conductor_id, $desde, $hasta);

    if (!$stmt->execute()) {
        return array('ok' => false, 'error' => $stmt->error);
    }

    return array('ok' => true);
}

// Función auxiliar para obtener el ID del tipo de asistencia a partir de su código
function tipo_id_por_codigo(mysqli $conn, $codigo)
{
    $sql = "SELECT id FROM asistencia_tipos WHERE codigo = ? LIMIT 1";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return 0;
    }

    $stmt->bind_param("s", $codigo);

    if (!$stmt->execute()) {
        $stmt->close();
        return 0;
    }

    // Declarar la variable ANTES para que Visual Studio no marque error
    $id = null;

    $stmt->bind_result($id);

    $found = $stmt->fetch();
    $stmt->close();

    if ($found) {
        return (int)$id;
    }

    return 0;
}

