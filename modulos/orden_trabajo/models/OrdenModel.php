<?php

class OrdenModel {

    /** @var mysqli */
    private $conn;

    /** @param mysqli $conn */
    public function __construct($conn) {
        $this->conn = $conn;
    }

    // ============================================================
    // BASE REUTILIZABLE
    // ============================================================
    private function obtenerBase($where = "", $params = array(), $types = "") {

        $sql = "SELECT 
                    ot.id,
                    ot.numero_ot,
                    ot.fecha,
                    CONCAT(YEAR(ot.fecha), '-W', LPAD(ot.semana_ot, 2, '0')) AS semana,

                    -- Cliente
                    c.nombre AS cliente,

                    -- Empresa
                    e.razon_social AS empresa,

                    -- Orden de compra del cliente
                    ot.oc_cliente,

                    -- Tipo de mercadería (vehículo en tu interfaz)
                    COALESCE(tm.nombre, 'Sin vehículo') AS vehiculo,

                    -- Tipo OT
                    tot.nombre AS tipo_ot,

                    -- Estado
                    eo.nombre AS estado

                FROM ordenes_trabajo ot
                LEFT JOIN clientes c ON ot.cliente_id = c.id
                LEFT JOIN empresa e ON ot.empresa_id = e.id
                LEFT JOIN tipos_mercaderia tm ON ot.tipo_mercaderia_id = tm.id
                LEFT JOIN tipo_ot tot ON ot.tipo_ot_id = tot.id
                LEFT JOIN estado_orden_trabajo eo ON ot.estado_ot = eo.id

                $where
                ORDER BY ot.fecha DESC, ot.id DESC";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return array();

        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        if (!$stmt->execute()) return array();

        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : array();
    }

    // ============================================================
    // ACTIVAS POR SEMANA
    // ============================================================
    public function obtenerActivasPorSemana($semanaISO = "") {

        $where = "WHERE ot.estado_ot NOT IN (7,8)";
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
    // POR ESTADO Y SEMANA
    // ============================================================
    public function obtenerPorEstadoYSemana($estadoID, $semanaISO = "") {

        $where  = "WHERE ot.estado_ot = ?";
        $params = array(intval($estadoID));
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
    // SEMANAS DISPONIBLES
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
    // OBTENER POR ID (NECESARIO PARA EDITAR)
    // ============================================================
    public function obtenerPorId($id) {

        $sql = "SELECT 
                    ot.id,
                    ot.numero_ot,
                    ot.fecha,
                    CONCAT(YEAR(ot.fecha), '-W', LPAD(ot.semana_ot, 2, '0')) AS semana,
                    ot.oc_cliente,

                    c.nombre AS cliente,
                    e.razon_social AS empresa,
                    COALESCE(tm.nombre, 'Sin vehículo') AS vehiculo,
                    tot.nombre AS tipo_ot,
                    eo.nombre AS estado

                FROM ordenes_trabajo ot
                LEFT JOIN clientes c ON ot.cliente_id = c.id
                LEFT JOIN empresa e ON ot.empresa_id = e.id
                LEFT JOIN tipos_mercaderia tm ON ot.tipo_mercaderia_id = tm.id
                LEFT JOIN tipo_ot tot ON ot.tipo_ot_id = tot.id
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
?>
