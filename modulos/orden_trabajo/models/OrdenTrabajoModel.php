<?php
// ======================================================
//  MODELO: OrdenTrabajoModel.php
//  MÓDULO: Órdenes de Trabajo (OT)
//  RESPONSABILIDAD: Operaciones SQL exclusivas de OT
//  GLOBAL 2026 — Arquitectura Limpia (PHP 5.6 compatible)
// ======================================================

class OrdenTrabajoModel
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    // --------------------------------------------------
    // Obtener semanas disponibles
    // --------------------------------------------------
    public function getSemanas()
    {
        $sql = "
            SELECT DISTINCT semana_ot AS semana
            FROM ordenes_trabajo
            WHERE semana_ot IS NOT NULL
            ORDER BY semana_ot DESC
        ";

        $rs = mysqli_query($this->conn, $sql);

        $data = array();

        if ($rs) {
            while ($row = mysqli_fetch_assoc($rs)) {
                $data[] = $row;
            }
        }

        return $data;
    }

    // --------------------------------------------------
    // Listado principal de OT
    // --------------------------------------------------
    public function getListadoOT($semana, $estado)
    {
        $sql = "
            SELECT
                ot.id,
                ot.numero_ot,
                SUBSTRING_INDEX(ot.numero_ot, '-', 1) AS numero_ot_numero,
                SUBSTRING_INDEX(ot.numero_ot, '-', -1) AS numero_ot_anio,
                ot.fecha,
                ot.oc_cliente,
                cli.nombre_comercial AS cliente,
                emp.nombre_comercial AS empresa,
                tipo.codigo AS tipo_ot,
                ot.semana_ot,
                est.nombre AS estado,
                IFNULL(ot.numero_viajes, 0) AS numero_viajes
            FROM ordenes_trabajo ot
            LEFT JOIN clientes cli
                ON cli.id = ot.cliente_id
            LEFT JOIN empresa emp
                ON emp.id = ot.empresa_id
            LEFT JOIN tipo_ot tipo
                ON tipo.id = ot.tipo_ot_id
            LEFT JOIN estado_orden_trabajo est
                ON est.id = ot.estado_id
            WHERE ot.deleted_at IS NULL
        ";

        if ($semana !== "") {
            $sql .= "
                AND ot.semana_ot = '" .
                $this->conn->real_escape_string($semana) .
                "'
            ";
        }

        if ($estado !== "TODAS") {

            if (!is_numeric($estado)) {
                return array();
            }

            $sql .= "
                AND ot.estado_id = " . intval($estado);
        }

        $sql .= "
            ORDER BY
                numero_ot_anio DESC,
                numero_ot_numero DESC
        ";

        $rs = $this->conn->query($sql);

        if (!$rs) {
            die(
                "SQL ERROR: " .
                $this->conn->error .
                " | SQL: " .
                $sql
            );
        }

        $data = array();

        while ($row = $rs->fetch_assoc()) {
            $data[] = $row;
        }

        return $data;
    }

    // --------------------------------------------------
    // Crear nueva OT
    // --------------------------------------------------
    public function crearOT($post)
    {
        $numero_ot  = mysqli_real_escape_string($this->conn, $post['numero_ot']);
        $fecha      = mysqli_real_escape_string($this->conn, $post['fecha']);
        $cliente_id = mysqli_real_escape_string($this->conn, $post['cliente_id']);
        $empresa_id = mysqli_real_escape_string($this->conn, $post['empresa_id']);
        $tipo_ot_id = mysqli_real_escape_string($this->conn, $post['tipo_ot_id']);
        $oc_cliente = mysqli_real_escape_string($this->conn, $post['oc_cliente']);

        $campo = isset($post['campo_dinamico'])
            ? mysqli_real_escape_string($this->conn, $post['campo_dinamico'])
            : '';

        $numero_dam     = ($tipo_ot_id == 2) ? $campo : '';
        $numero_booking = ($tipo_ot_id == 3) ? $campo : '';
        $otros          = ($tipo_ot_id == 1) ? $campo : '';

        $estado_id = 1;
        $semana_ot = date('Y') . '-W' . date('W', strtotime($fecha));

		

        $sql = "
            INSERT INTO ordenes_trabajo
            (
                numero_ot,
                fecha,
                cliente_id,
                empresa_id,
                tipo_ot_id,
                oc_cliente,
                semana_ot,
                estado_id,
                numero_dam,
                numero_booking,
                otros,
                numero_viajes,
                creado_por,
                created_at
            )
            VALUES
            (
                '$numero_ot',
                '$fecha',
                '$cliente_id',
                '$empresa_id',
                '$tipo_ot_id',
                '$oc_cliente',
                '$semana_ot',
                '$estado_id',
                '$numero_dam',
                '$numero_booking',
                '$otros',
                0,
                '" . $_SESSION['usuario_id'] . "',
                NOW()
            )
        ";

        $ok = mysqli_query($this->conn, $sql);

        return $ok
            ? array('success' => true)
            : array(
                'success' => false,
                'message' => mysqli_error($this->conn)
            );
    }

    // --------------------------------------------------
    // Obtener OT por ID
    // --------------------------------------------------
    public function getOTById($id)
    {
        $id = intval($id);

        $sql = "
            SELECT
                ot.*,
                cli.nombre_comercial AS cliente_nombre,
                emp.nombre_comercial AS empresa_nombre,
                tipo.nombre AS tipo_ot_nombre,
                est.nombre AS estado_nombre
            FROM ordenes_trabajo ot
            LEFT JOIN clientes cli
                ON cli.id = ot.cliente_id
            LEFT JOIN empresa emp
                ON emp.id = ot.empresa_id
            LEFT JOIN tipo_ot tipo
                ON tipo.id = ot.tipo_ot_id
            LEFT JOIN estado_orden_trabajo est
                ON est.id = ot.estado_id
            WHERE ot.id = ?
            LIMIT 1
        ";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return null;
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();

        $res = $stmt->get_result();

        if (!$res || $res->num_rows === 0) {
            return null;
        }

        return $res->fetch_assoc();
    }
}