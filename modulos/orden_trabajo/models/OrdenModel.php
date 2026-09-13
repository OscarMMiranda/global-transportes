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

                    -- Estado corporativo
                    COALESCE(eo.nombre, 'PENDIENTE') AS estado,

                    COUNT(DISTINCT vo.id) AS numero_viajes

                FROM ordenes_trabajo ot
                LEFT JOIN clientes c ON ot.cliente_id = c.id
                LEFT JOIN empresa e ON ot.empresa_id = e.id
                LEFT JOIN tipo_ot tot ON ot.tipo_ot_id = tot.id
                LEFT JOIN estado_orden_trabajo eo ON ot.estado_ot = eo.id

                LEFT JOIN ordenes_vehiculo ov 
                    ON ov.orden_trabajo_id = ot.id

                LEFT JOIN viajes_orden vo
                    ON vo.orden_vehiculo_id = ov.id

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
    // LISTADO CORPORATIVO DINÁMICO (ESTADO + SEMANA)
    // ============================================================
    public function listarOT($estadoNombre, $semanaISO = "")
    {
        $where = "WHERE 1 = 1";
        $params = array();
        $types  = "";

        // ============================================================
        // 1. Resolver estado por nombre (si no es TODAS)
        // ============================================================
        if ($estadoNombre !== "TODAS") {

            $sqlEstado = "SELECT id FROM estado_orden_trabajo WHERE nombre = ?";
            $stmt = $this->conn->prepare($sqlEstado);
            $stmt->bind_param("s", $estadoNombre);
            $stmt->execute();
            $res = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            if ($res) {
                $estadoID = intval($res['id']);
                $where .= " AND ot.estado_ot = ?";
                $params[] = $estadoID;
                $types   .= "i";
            }
        }

        // ============================================================
        // 2. Filtro por semana (YYYY-Wxx)
        // ============================================================
        if ($semanaISO !== "") {
            list($anio, $semana) = explode("-W", $semanaISO);

            $where .= " AND YEAR(ot.fecha) = ? AND ot.semana_ot = ?";
            $params[] = intval($anio);
            $params[] = intval($semana);
            $types   .= "ii";
        }

        // ============================================================
        // 3. Ejecutar consulta base corporativa
        // ============================================================
        return $this->obtenerBase($where, $params, $types);
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
    // TODAS LAS ORDENES POR SEMANA
    // ============================================================
    public function obtenerTodasPorSemana($semanaISO = "") {

        $where = "WHERE 1 = 1";
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

                    COALESCE(eo.nombre, 'PENDIENTE') AS estado,

                    COUNT(DISTINCT vo.id) AS numero_viajes

                FROM ordenes_trabajo ot
                LEFT JOIN clientes c ON ot.cliente_id = c.id
                LEFT JOIN empresa e ON ot.empresa_id = e.id
                LEFT JOIN tipo_ot tot ON ot.tipo_ot_id = tot.id
                LEFT JOIN estado_orden_trabajo eo ON ot.estado_ot = eo.id

                LEFT JOIN ordenes_vehiculo ov 
                    ON ov.orden_trabajo_id = ot.id

                LEFT JOIN viajes_orden vo
                    ON vo.orden_vehiculo_id = ov.id

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

