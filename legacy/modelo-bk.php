<?php
// modelo.php
require_once __DIR__ . '/funciones.php';

/**
 * getEstadoId
 *
 * Devuelve los datos completos de un estado según su ID.
 */
function getEstadoId($id)
{
    $db = getDbConnection();
    $sql = 'SELECT * FROM estados WHERE id = :id';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

/**
 * obtenerAsignaciones
 *
 * Recupera asignaciones según filtros: usuario, estado, rango de fechas.
 */
function obtenerAsignaciones(array $filtros = [])
{
    $db  = getDbConnection();
    $sql = '
        SELECT a.id, u.nombre AS usuario, e.nombre AS estado, a.fecha
        FROM asignaciones a
        JOIN usuarios u ON u.id = a.usuario_id
        JOIN estados  e ON e.id = a.estado_id
        WHERE 1=1
    ';

    // Construir cláusulas dinámicas
    if (!empty($filtros['usuario_id'])) {
        $sql .= ' AND a.usuario_id = :usuario_id';
    }
    if (!empty($filtros['estado_id'])) {
        $sql .= ' AND a.estado_id = :estado_id';
    }
    if (!empty($filtros['fecha_ini'])) {
        $sql .= ' AND a.fecha >= :fecha_ini';
    }
    if (!empty($filtros['fecha_fin'])) {
        $sql .= ' AND a.fecha <= :fecha_fin';
    }

    $stmt = $db->prepare($sql);

    // Bind de filtros
    foreach ($filtros as $key => $val) {
        $stmt->bindValue(":{$key}", $val);
    }

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * finalizarAsignacion
 *
 * Marca una asignación como finalizada y registra trazabilidad.
 */
function finalizarAsignacion($asignacionId, $userId)
{
    $db = getDbConnection();
    try {
        $db->beginTransaction();

        // Actualizar estado a 'Finalizado'
        $update = '
            UPDATE asignaciones
            SET estado_id = (
                SELECT id FROM estados WHERE LOWER(nombre) = "finalizado" LIMIT 1
            )
            WHERE id = :id
        ';
        $stmt = $db->prepare($update);
        $stmt->bindValue(':id', $asignacionId, PDO::PARAM_INT);
        $stmt->execute();

        // Insertar registro de trazabilidad
        $insert = '
            INSERT INTO asignaciones_historial
                (asignacion_id, usuario_id, accion, fecha)
            VALUES
                (:id, :usuario_id, "Finalizado", NOW())
        ';
        $stmt2 = $db->prepare($insert);
        $stmt2->bindValue(':id', $asignacionId, PDO::PARAM_INT);
        $stmt2->bindValue(':usuario_id', $userId, PDO::PARAM_INT);
        $stmt2->execute();

        $db->commit();
        return true;
    } catch (Exception $e) {
        $db->rollBack();
        error_log($e->getMessage());
        return false;
    }
}
