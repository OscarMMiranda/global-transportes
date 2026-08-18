<?php

/**
 * Modelo de Órdenes de Trabajo
 * @author Global
 */
class OrdenModel {

    /**
     * @var mysqli
     */
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // ============================================================
    //  BASE REUTILIZABLE
    // ============================================================
    private function obtenerBase($where = "", $params = array(), $types = "") {

        $sql = "SELECT 
                    ot.id,
                    ot.numero_ot,
                    ot.fecha,
                    ot.oc_cliente,
                    ot.tipo_ot_id,
                    c.nombre AS cliente_nombre,
                    tot.nombre AS tipo_ot_nombre,
                    e.razon_social AS empresa_nombre,
                    eo.nombre AS estado_nombre
                FROM ordenes_trabajo ot
                LEFT JOIN clientes c ON ot.cliente_id = c.id
                LEFT JOIN tipo_ot tot ON ot.tipo_ot_id = tot.id
                LEFT JOIN empresa e ON ot.empresa_id = e.id
                LEFT JOIN estado_orden_trabajo eo ON ot.estado_ot = eo.id
                $where
                ORDER BY ot.fecha DESC, ot.id DESC";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return array();

        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        if (!$stmt->execute()) return array();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // ============================================================
    //  ACTIVAS POR SEMANA
    // ============================================================
    public function obtenerActivasPorSemana($semanaISO = "") {

        $where = "WHERE eo.id NOT IN (7,8)";
        $params = array();
        $types  = "";

        if ($semanaISO !== "") {

            list($anio, $semana) = explode("-W", $semanaISO);

            $where .= " AND YEAR(ot.fecha) = ? AND ot.semana_ot = ?";
            $params[] = intval($anio);
            $params[] = intval($semana);
            $types   .= "ii";
        }

        return $this->obtenerBase($where, $params, $types);
    }

    // ============================================================
    //  POR ESTADO Y SEMANA
    // ============================================================
    public function obtenerPorEstadoYSemana($estadoID, $semanaISO = "") {

        $where  = "WHERE eo.id = ?";
        $params = array($estadoID);
        $types  = "i";

        if ($semanaISO !== "") {

            list($anio, $semana) = explode("-W", $semanaISO);

            $where .= " AND YEAR(ot.fecha) = ? AND ot.semana_ot = ?";
            $params[] = intval($anio);
            $params[] = intval($semana);
            $types   .= "ii";
        }

        return $this->obtenerBase($where, $params, $types);
    }

    // ============================================================
    //  OBTENER SEMANAS
    // ============================================================
    public function obtenerSemanas() {

        $sql = "
            SELECT DISTINCT 
                CONCAT(YEAR(ot.fecha), '-W', LPAD(ot.semana_ot, 2, '0')) AS semana
            FROM ordenes_trabajo ot
            WHERE ot.fecha IS NOT NULL
            ORDER BY semana DESC
        ";

        $res = $this->conn->query($sql);
        return $res ? $res->fetch_all(MYSQLI_ASSOC) : array();
    }

    // ============================================================
    //  OBTENER POR ID (FALTABA)
    // ============================================================
    /**
     * Obtiene una OT por su ID
     * @param int $id
     * @return array|null
     */
    public function obtenerPorId($id) {

        $sql = "SELECT 
                    ot.id,
                    ot.numero_ot,
                    ot.fecha,
                    ot.oc_cliente,
                    ot.tipo_ot_id,
                    c.nombre AS cliente_nombre,
                    tot.nombre AS tipo_ot_nombre,
                    e.razon_social AS empresa_nombre,
                    eo.nombre AS estado_nombre
                FROM ordenes_trabajo ot
                LEFT JOIN clientes c ON ot.cliente_id = c.id
                LEFT JOIN tipo_ot tot ON ot.tipo_ot_id = tot.id
                LEFT JOIN empresa e ON ot.empresa_id = e.id
                LEFT JOIN estado_orden_trabajo eo ON ot.estado_ot = eo.id
                WHERE ot.id = ?
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return null;

        $stmt->bind_param("i", $id);

        if (!$stmt->execute()) return null;

        $res = $stmt->get_result();
        return ($res->num_rows > 0) ? $res->fetch_assoc() : null;
    }
}
