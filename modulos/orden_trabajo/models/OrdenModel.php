<?php

// archivo: /modulos/orden_trabajo/models/OrdenModel.php

class OrdenModel {

    /** @var mysqli */
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // ============================================================
    // BASE REUTILIZABLE (LISTADO CORPORATIVO)
    // ============================================================
    private function obtenerBase($where = "", $params = array(), $types = "") {

        $sql = "SELECT 
                    ot.id,

                    -- Formato corporativo 0000-YYYY
                    CONCAT(
                        LPAD(SUBSTRING_INDEX(ot.numero_ot, '-', 1), 4, '0'),
                        '-',
                        SUBSTRING_INDEX(ot.numero_ot, '-', -1)
                    ) AS numero_ot,

                    ot.fecha,
                    CONCAT(YEAR(ot.fecha), '-W', LPAD(ot.semana_ot, 2, '0')) AS semana,

                    -- Cliente corporativo
                    COALESCE(c.nombre_comercial, c.nombre) AS cliente,

                    ot.oc_cliente,
                    tot.nombre AS tipo_ot,

                    -- Empresa corporativa
                    COALESCE(e.nombre_comercial, e.razon_social) AS empresa,

                    -- Estado corporativo (evita NULL)
                    COALESCE(eo.nombre, 'Pendiente') AS estado,

                    COUNT(DISTINCT ov.id) AS numero_viajes

                FROM ordenes_trabajo ot
                LEFT JOIN clientes c ON ot.cliente_id = c.id
                LEFT JOIN empresa e ON ot.empresa_id = e.id
                LEFT JOIN tipo_ot tot ON ot.tipo_ot_id = tot.id
                LEFT JOIN estado_orden_trabajo eo ON ot.estado_ot = eo.id

                LEFT JOIN ordenes_viaje ov 
                    ON ov.numero_ot = ot.numero_ot

                $where

                GROUP BY ot.id
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

        // ACTIVA = Pendiente (1) + En proceso (2)
        $where = "WHERE ot.estado_ot IN (1,2)";
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
    // DETALLE CORPORATIVO
    // ============================================================
    public function obtenerPorId($id) {

        $sql = "SELECT 
                    ot.id,

                    -- Formato corporativo 0000-YYYY
                    CONCAT(
                        LPAD(SUBSTRING_INDEX(ot.numero_ot, '-', 1), 4, '0'),
                        '-',
                        SUBSTRING_INDEX(ot.numero_ot, '-', -1)
                    ) AS numero_ot,

                    ot.fecha,
                    CONCAT(YEAR(ot.fecha), '-W', LPAD(ot.semana_ot, 2, '0')) AS semana,

                    COALESCE(c.nombre_comercial, c.nombre) AS cliente,
                    COALESCE(e.nombre_comercial, e.razon_social) AS empresa,

                    ot.oc_cliente,
                    tot.nombre AS tipo_ot,

                    -- Estado corporativo
                    COALESCE(eo.nombre, 'Pendiente') AS estado,

                    COUNT(DISTINCT ov.id) AS numero_viajes

                FROM ordenes_trabajo ot
                LEFT JOIN clientes c ON ot.cliente_id = c.id
                LEFT JOIN empresa e ON ot.empresa_id = e.id
                LEFT JOIN tipo_ot tot ON ot.tipo_ot_id = tot.id
                LEFT JOIN estado_orden_trabajo eo ON ot.estado_ot = eo.id

                LEFT JOIN ordenes_viaje ov 
                    ON ov.numero_ot = ot.numero_ot

                WHERE ot.id = ?
                GROUP BY ot.id
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

