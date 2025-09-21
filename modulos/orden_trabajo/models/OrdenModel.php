<?php
    //  archivos    :   /modulos/orden_trabajo/models/OrdenModel.php

class OrdenModel {
    private $conn;
    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function obtenerPorEstado($estadoID) {
        $sql = "SELECT ot.id, ot.numero_ot, ot.fecha, ot.oc_cliente,
                       c.nombre AS cliente_nombre,
                       tot.nombre AS tipo_ot_nombre,
                       e.razon_social AS empresa_nombre,
                       eo.nombre AS estado_nombre,
                       ot.tipo_ot_id
                FROM ordenes_trabajo ot
                LEFT JOIN clientes c ON ot.cliente_id = c.id
                LEFT JOIN tipo_ot tot ON ot.tipo_ot_id = tot.id
                LEFT JOIN empresa e ON ot.empresa_id = e.id
                LEFT JOIN estado_orden_trabajo eo ON ot.estado_ot = eo.id
                WHERE eo.id = ?
                ORDER BY CAST(SUBSTRING_INDEX(numero_ot, '-', -1) AS UNSIGNED) DESC,
                         CAST(SUBSTRING_INDEX(numero_ot, '-', 1) AS UNSIGNED) DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $estadoID);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerActivas() {
        $sql = "SELECT ot.id, ot.numero_ot, ot.fecha, ot.oc_cliente, ot.tipo_ot_id,
                       c.nombre AS cliente_nombre,
                       tot.nombre AS tipo_ot_nombre,
                       e.razon_social AS empresa_nombre,
                       eo.nombre AS estado_nombre
                FROM ordenes_trabajo ot
                LEFT JOIN clientes c ON ot.cliente_id = c.id
                LEFT JOIN tipo_ot tot ON ot.tipo_ot_id = tot.id
                LEFT JOIN empresa e ON ot.empresa_id = e.id
                LEFT JOIN estado_orden_trabajo eo ON ot.estado_ot = eo.id
                WHERE eo.id NOT IN (7,8)
                ORDER BY CAST(SUBSTRING_INDEX(numero_ot, '-', -1) AS UNSIGNED) DESC,
                         CAST(SUBSTRING_INDEX(numero_ot, '-', 1) AS UNSIGNED) DESC";
        return $this->conn->query($sql)->fetch_all(MYSQLI_ASSOC);
    }
}