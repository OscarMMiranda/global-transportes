<?php

// ======================================================
//  MODELO: OrdenModel.php
//  RESPONSABILIDAD: Consultas corporativas del módulo OT
// ======================================================

class OrdenModel {

    /** @var mysqli */
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // ============================================================
    // ABREVIAR TIPO DE ORDEN (EXP, IMP, NAC)
    // ============================================================
    private function abreviarTipoOT($tipo) {
        $tipo = strtoupper(trim($tipo));

        if ($tipo === "EXPORTACION" || $tipo === "EXPORTACIÓN") return "EXP";
        if ($tipo === "IMPORTACION" || $tipo === "IMPORTACIÓN") return "IMP";
        if ($tipo === "NACIONAL") return "NAC";

        return $tipo;
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
                LEFT JOIN estado_orden_trabajo eo ON ot.estado_id = eo.id

                LEFT JOIN ordenes_vehiculo ov 
                    ON ov.orden_trabajo_id = ot.id

                LEFT JOIN viajes_orden vo
                    ON vo.orden_vehiculo_id = ov.id

                $where

                GROUP BY ot.id
                ORDER BY ot.fecha DESC, ot.id DESC";

        // Preparar
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log("[ERP-OT] Error preparar obtenerBase: " . $this->conn->error);
            return array();
        }

        // Bind dinámico
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        // Ejecutar
        if (!$stmt->execute()) {
            error_log("[ERP-OT] Error ejecutar obtenerBase: " . $stmt->error);
            return array();
        }

        // Resultado
        $result = $stmt->get_result();
        $rows   = $result ? $result->fetch_all(MYSQLI_ASSOC) : array();

        // Abreviar tipo OT
        foreach ($rows as &$r) {
            $r["tipo_ot_abreviado"] = $this->abreviarTipoOT($r["tipo_ot"]);
        }

        return $rows;
    }

    // ============================================================
    // LISTADO CORPORATIVO DINÁMICO (ESTADO + SEMANA)
    // ============================================================
    public function listarOT($estadoNombre, $semanaISO = "")
    {
        $where = "WHERE 1 = 1";
        $params = array();
        $types  = "";

        // 1. Filtro por estado (si no es TODAS)
        if ($estadoNombre !== "TODAS") {
            $where .= " AND eo.nombre = ?";
            $params[] = $estadoNombre;
            $types   .= "s";
        }

        // 2. Filtro por semana (YYYY-Wxx)
        if ($semanaISO !== "") {
            list($anio, $semana) = explode("-W", $semanaISO);

            $where .= " AND YEAR(ot.fecha) = ? AND ot.semana_ot = ?";
            $params[] = intval($anio);
            $params[] = intval($semana);
            $types   .= "ii";
        }

        // 3. Ejecutar consulta base corporativa
        return $this->obtenerBase($where, $params, $types);
    }

    // ============================================================
    // ESTADOS CORPORATIVOS (DINÁMICOS)
    // ============================================================
    public function obtenerEstadosOT() {

        $sql = "SELECT id, nombre, descripcion
                FROM estado_orden_trabajo
                ORDER BY id ASC";

        $rs = $this->conn->query($sql);

        if (!$rs) {
            error_log("[ERP-OT] Error SQL obtenerEstadosOT: " . $this->conn->error);
            return array();
        }

        return $rs->fetch_all(MYSQLI_ASSOC);
    }

    // ============================================================
    // ACTIVAS POR SEMANA (Pendiente + En proceso)
    // ============================================================
    public function obtenerActivasPorSemana($semanaISO = "") {

        $where = "WHERE eo.nombre IN ('PENDIENTE','EN PROCESO')";
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
    // POR ESTADO Y SEMANA (ID)
    // ============================================================
    public function obtenerPorEstadoYSemana($estadoID, $semanaISO = "") {

        $where  = "WHERE ot.estado_id = ?";
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

        if (!$res) {
            error_log("[ERP-OT] Error SQL obtenerSemanas: " . $this->conn->error);
            return array();
        }

        return $res->fetch_all(MYSQLI_ASSOC);
    }

    // ============================================================
    // DETALLE CORPORATIVO POR ID
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
                LEFT JOIN estado_orden_trabajo eo ON ot.estado_id = eo.id

                LEFT JOIN ordenes_vehiculo ov 
                    ON ov.orden_trabajo_id = ot.id

                LEFT JOIN viajes_orden vo
                    ON vo.orden_vehiculo_id = ov.id

                WHERE ot.id = ?
                GROUP BY ot.id
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log("[ERP-OT] Error preparar obtenerPorId: " . $this->conn->error);
            return null;
        }

        $stmt->bind_param("i", $id);

        if (!$stmt->execute()) {
            error_log("[ERP-OT] Error ejecutar obtenerPorId: " . $stmt->error);
            return null;
        }

        $res = $stmt->get_result();
        $row = ($res->num_rows > 0) ? $res->fetch_assoc() : null;

        if ($row) {
            $row["tipo_ot_abreviado"] = $this->abreviarTipoOT($row["tipo_ot"]);
        }

        return $row;
    }
}

?>

