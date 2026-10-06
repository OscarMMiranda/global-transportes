<?php
// ======================================================
//  MODELO: OrdenVehiculoModel.php
//  MÓDULO: Órdenes de Vehículo (OV)
//  RESPONSABILIDAD: Gestión de OV por OT
//  GLOBAL 2026 — Arquitectura Limpia
//  PHP 5.6 Compatible
// ======================================================

class OrdenVehiculoModel
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    // --------------------------------------------------
    // LISTAR TODOS LOS OV DE UNA OT
    // --------------------------------------------------
    public function getOVPorOT($ot_id)
    {
        $ot_id = intval($ot_id);

        $sql = "
            SELECT
                ov.id,
                ov.numero_ov,
                ov.orden_trabajo_id,
                ov.vehiculo_id,
                ov.conductor_id,
                ov.fecha_salida,
                ov.semana_viaje,
                ov.estado,
                ov.observaciones,
                v.placa,
                c.nombre AS conductor
            FROM ordenes_vehiculo ov
            LEFT JOIN vehiculos v
                ON v.id = ov.vehiculo_id
            LEFT JOIN conductores c
                ON c.id = ov.conductor_id
            WHERE ov.orden_trabajo_id = $ot_id
            ORDER BY ov.fecha_salida DESC
        ";

        $rs = $this->conn->query($sql);

        $data = array();

        if ($rs) {

            while ($row = $rs->fetch_assoc()) {
                $data[] = $row;
            }

        }

        return $data;
    }

    // --------------------------------------------------
    // OBTENER PRIMER OV DE UNA OT
    // --------------------------------------------------
    public function getOrdenVehiculoPorOT($ot_id)
    {
        $ot_id = intval($ot_id);

        $sql = "
            SELECT *
            FROM ordenes_vehiculo
            WHERE orden_trabajo_id = $ot_id
            LIMIT 1
        ";

        $rs = $this->conn->query($sql);

        if (
            $rs &&
            $rs->num_rows > 0
        ) {
            return $rs->fetch_assoc();
        }

        return null;
    }

    // --------------------------------------------------
    // CREAR OV
    // --------------------------------------------------
    public function crearOV($post)
    {
        $ot_id        = intval($post['ot_id']);
        $vehiculo_id  = intval($post['vehiculo_id']);
        $conductor_id = intval($post['conductor_id']);

        $fecha_salida = mysqli_real_escape_string(
            $this->conn,
            $post['fecha_salida']
        );

        $observaciones = mysqli_real_escape_string(
            $this->conn,
            isset($post['observaciones'])
                ? $post['observaciones']
                : ''
        );

        $sql = "
            INSERT INTO ordenes_vehiculo
            (
                orden_trabajo_id,
                vehiculo_id,
                conductor_id,
                fecha_salida,
                observaciones,
                creado_por,
                fecha_creacion
            )
            VALUES
            (
                $ot_id,
                $vehiculo_id,
                $conductor_id,
                '$fecha_salida',
                '$observaciones',
                '" . $_SESSION['usuario_id'] . "',
                NOW()
            )
        ";

        $ok = mysqli_query(
            $this->conn,
            $sql
        );

        if ($ok) {

            $this->conn->query("
                UPDATE ordenes_trabajo
                SET numero_viajes = numero_viajes + 1
                WHERE id = $ot_id
            ");

        }

        return $ok
            ? array(
                'success' => true
            )
            : array(
                'success' => false,
                'message' => mysqli_error($this->conn)
            );
    }

    // --------------------------------------------------
    // ELIMINAR OV
    // --------------------------------------------------
    public function eliminarOV($id)
    {
        $id = intval($id);

        $rs = $this->conn->query("
            SELECT orden_trabajo_id
            FROM ordenes_vehiculo
            WHERE id = $id
            LIMIT 1
        ");

        if (
            !$rs ||
            $rs->num_rows === 0
        ) {
            return array(
                'success' => false,
                'message' => 'OV no encontrada'
            );
        }

        $row   = $rs->fetch_assoc();
        $ot_id = intval(
            $row['orden_trabajo_id']
        );

        $ok = $this->conn->query("
            DELETE FROM ordenes_vehiculo
            WHERE id = $id
        ");

        if ($ok) {

            $this->conn->query("
                UPDATE ordenes_trabajo
                SET numero_viajes = numero_viajes - 1
                WHERE id = $ot_id
            ");

        }

        return $ok
            ? array(
                'success' => true
            )
            : array(
                'success' => false,
                'message' => $this->conn->error
            );
    }

    // --------------------------------------------------
    // OBTENER OV POR ID
    // --------------------------------------------------
    public function getOVById($id)
    {
        $id = intval($id);

        $sql = "
            SELECT
                ov.*,
                v.placa,
                c.nombre AS conductor
            FROM ordenes_vehiculo ov
            LEFT JOIN vehiculos v
                ON v.id = ov.vehiculo_id
            LEFT JOIN conductores c
                ON c.id = ov.conductor_id
            WHERE ov.id = $id
            LIMIT 1
        ";

        $rs = $this->conn->query($sql);

        if (
            $rs &&
            $rs->num_rows > 0
        ) {
            return $rs->fetch_assoc();
        }

        return null;
    }
}