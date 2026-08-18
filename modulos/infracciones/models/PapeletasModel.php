<?php
// archivo: /modulos/papeletas/models/PapeletasModel.php

class PapeletasModel {

    private $db;

    public function __construct($db){
        $this->db = $db;
    }

    /* ============================================================
       OBTENER UIT POR AÑO
       ============================================================ */
    public function getUitPorAnio($anio)
    {
        $anio = intval($anio);

        $sql = "SELECT valor FROM uit WHERE anio = $anio LIMIT 1";
        $res = $this->db->query($sql);

        if ($res && $res->num_rows > 0) {
            $row = $res->fetch_assoc();
            return floatval($row['valor']);
        }

        // Si no existe UIT del año, usar la vigente
        return $this->getUitVigente();
    }

    /* ============================================================
       OBTENER UIT VIGENTE
       ============================================================ */
    public function getUitVigente()
    {
        $sql = "SELECT valor FROM uit WHERE estado = 1 LIMIT 1";
        $res = $this->db->query($sql);

        if ($res && $res->num_rows > 0) {
            $row = $res->fetch_assoc();
            return floatval($row['valor']);
        }

        return 0;
    }

    /* ============================================================
       CALCULAR MONTO SEGÚN PORCENTAJE Y FECHA
       ============================================================ */
    public function calcularMontoPorFecha($porcentaje_uit, $fecha)
    {
        $anio = intval(date("Y", strtotime($fecha)));
        $uit  = $this->getUitPorAnio($anio);

        return ($porcentaje_uit / 100) * $uit;
    }

    /* ============================================================
       OBTENER INFRACCIÓN
       ============================================================ */
    public function obtenerInfraccion($id)
    {
        $id = intval($id);

        $sql = "SELECT * FROM infracciones WHERE id = $id LIMIT 1";
        $res = $this->db->query($sql);

        if ($res && $res->num_rows > 0) {
            return $res->fetch_assoc();
        }

        return false;
    }

    /* ============================================================
       LISTAR PAPELETAS
       ============================================================ */
    public function listar($filtros = [])
    {
        $sql = "SELECT p.*, i.codigo AS codigo_infraccion, i.descripcion AS infraccion_desc,
                       e.nombre AS entidad_nombre
                FROM papeletas p
                LEFT JOIN infracciones i ON i.id = p.infraccion_id
                LEFT JOIN entidad_emisora e ON e.id = p.entidad_emisora_id
                WHERE p.eliminado = 0";

        $res = $this->db->query($sql);
        $data = array();

        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $data[] = $row;
            }
        }

        return $data;
    }

    /* ============================================================
       REGISTRAR PAPELETA (CONGELANDO MONTO)
       ============================================================ */
    public function registrar($data)
    {
        $vehiculo   = intval($data['vehiculo_id']);
        $conductor  = intval($data['conductor_id']);
        $entidad    = intval($data['entidad_emisora_id']);
        $infraccion = intval($data['infraccion_id']);
        $fecha_inf  = $this->db->real_escape_string($data['fecha_infraccion']);
        $lugar      = $this->db->real_escape_string($data['lugar']);
        $desc       = $this->db->real_escape_string($data['descripcion']);

        // Obtener infracción
        $inf = $this->obtenerInfraccion($infraccion);
        $porc = floatval($inf['porcentaje_uit']);

        // Calcular monto según UIT del año de la infracción
        $monto = $this->calcularMontoPorFecha($porc, $fecha_inf);

        $sql = "INSERT INTO papeletas
                (vehiculo_id, conductor_id, entidad_emisora_id, infraccion_id,
                 fecha_infraccion, lugar, descripcion, monto, estado_id, eliminado)
                VALUES
                ($vehiculo, $conductor, $entidad, $infraccion,
                 '$fecha_inf', '$lugar', '$desc', $monto, 1, 0)";

        return $this->db->query($sql);
    }

    /* ============================================================
       REGISTRAR PAGO (CONGELANDO MONTO PAGADO)
       ============================================================ */
    public function registrarPago($data)
    {
        $papeleta_id = intval($data['papeleta_id']);
        $fecha_pago  = $this->db->real_escape_string($data['fecha_pago']);
        $metodo      = $this->db->real_escape_string($data['metodo']);
        $ref         = $this->db->real_escape_string($data['referencia']);

        // Obtener papeleta
        $sql = "SELECT p.*, i.porcentaje_uit
                FROM papeletas p
                LEFT JOIN infracciones i ON i.id = p.infraccion_id
                WHERE p.id = $papeleta_id LIMIT 1";

        $res = $this->db->query($sql);
        $p = $res->fetch_assoc();

        $porc = floatval($p['porcentaje_uit']);

        // Calcular monto pagado según UIT del año del pago
        $monto_pagado = $this->calcularMontoPorFecha($porc, $fecha_pago);

        // Registrar pago
        $sqlPago = "INSERT INTO papeleta_pagos
                    (papeleta_id, fecha, monto, metodo, referencia)
                    VALUES
                    ($papeleta_id, '$fecha_pago', $monto_pagado, '$metodo', '$ref')";
        $this->db->query($sqlPago);

        // Actualizar papeleta
        $sqlUp = "UPDATE papeletas SET
                    monto_pagado = $monto_pagado,
                    estado_id = 3,
                    updated_at = NOW()
                  WHERE id = $papeleta_id";

        return $this->db->query($sqlUp);
    }

    /* ============================================================
       REGISTRAR HISTORIAL
       ============================================================ */
    public function historial($papeleta_id, $estado_id, $comentario, $usuario_id)
    {
        $papeleta_id = intval($papeleta_id);
        $estado_id   = intval($estado_id);
        $usuario_id  = intval($usuario_id);
        $comentario  = $this->db->real_escape_string($comentario);

        $sql = "INSERT INTO papeleta_historial
                (papeleta_id, estado_id, comentario, fecha, usuario_id)
                VALUES
                ($papeleta_id, $estado_id, '$comentario', NOW(), $usuario_id)";

        return $this->db->query($sql);
    }
}
?>
