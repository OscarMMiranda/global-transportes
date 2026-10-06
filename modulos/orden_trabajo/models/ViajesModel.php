<?php
// ======================================================
//  MODELO: ViajesModel.php
//  MÓDULO: Viajes por Orden Vehículo (OV)
//  RESPONSABILIDAD: CRUD + consultas corporativas
//  GLOBAL 2026 — Arquitectura Limpia (v5.0)
// ======================================================

class ViajesModel
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

// --------------------------------------------------
// Obtener viajes para modal VER OT
// --------------------------------------------------
public function getViajesPorOrdenVehiculo($ov_id)
{
    $ov_id = intval($ov_id);

    $sql = "
        SELECT
            vo.id AS numero_viaje,
            ov.fecha_salida AS fecha,
            ov.numero_ov,
            v.placa AS vehiculo,
            CONCAT(LEFT(c.nombres,1),
			'. ',
			SUBSTRING_INDEX(c.apellidos,' ',1)
			) AS conductor

        FROM viajes_orden vo
        INNER JOIN ordenes_vehiculo ov
            ON ov.id = vo.orden_vehiculo_id
        LEFT JOIN vehiculos v
            ON v.id = ov.vehiculo_id

        LEFT JOIN conductores c
            ON c.id = vo.conductor_id

        WHERE vo.orden_vehiculo_id = ?

        ORDER BY vo.id ASC
    ";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {

        error_log(
            '[ViajesModel] ERROR: ' .
            $this->conn->error
        );

        return array();
    }

    $stmt->bind_param(
        "i",
        $ov_id
    );

    $stmt->execute();

    $res = $stmt->get_result();

    $data = array();

    while ($row = $res->fetch_assoc()) {

        $data[] = array(

            'id'         => $row['numero_viaje'],
            'fecha'      => $row['fecha'],
            'numero_ov'  => $row['numero_ov'],
            'vehiculo'   => $row['vehiculo'],
            'conductor'  => $row['conductor'],

            // obligatorios para JS actual
            'semana'     => '',
            'origen'     => '',
            'destino'    => '',
            'mercaderia' => '',
            'estado'     => ''

        );
    }

    return $data;
}

    // --------------------------------------------------
    // Obtener viaje por ID
    // --------------------------------------------------
    public function getViajeById($id)
    {
        $id = intval($id);

        $sql = "
            SELECT *
            FROM viajes_orden
            WHERE id = ?
            LIMIT 1
        ";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return null;

        $stmt->bind_param("i", $id);
        $stmt->execute();

        $res = $stmt->get_result();
        if ($res && $res->num_rows > 0) {
            return $res->fetch_assoc();
        }

        return null;
    }

    // --------------------------------------------------
    // Registrar viaje
    // --------------------------------------------------
    public function registrarViaje($data)
    {
        $sql = "
            INSERT INTO viajes_orden (
                orden_vehiculo_id,
                numero_viaje,
                fecha,
                semana,
                origen,
                destino,
                tipo_mercaderia,
                vehiculo,
                conductor,
                estado
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return false;

        $stmt->bind_param(
            "iissssssss",
            $data['orden_vehiculo_id'],
            $data['numero_viaje'],
            $data['fecha'],
            $data['semana'],
            $data['origen'],
            $data['destino'],
            $data['tipo_mercaderia'],
            $data['vehiculo'],
            $data['conductor'],
            $data['estado']
        );

        return $stmt->execute();
    }

    // --------------------------------------------------
    // Actualizar viaje
    // --------------------------------------------------
    public function actualizarViaje($id, $data)
    {
        $id = intval($id);

        $sql = "
            UPDATE viajes_orden SET
                fecha = ?,
                semana = ?,
                origen = ?,
                destino = ?,
                tipo_mercaderia = ?,
                vehiculo = ?,
                conductor = ?,
                estado = ?
            WHERE id = ?
        ";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return false;

        $stmt->bind_param(
            "ssssssssi",
            $data['fecha'],
            $data['semana'],
            $data['origen'],
            $data['destino'],
            $data['tipo_mercaderia'],
            $data['vehiculo'],
            $data['conductor'],
            $data['estado'],
            $id
        );

        return $stmt->execute();
    }

    // --------------------------------------------------
    // Eliminar viaje
    // --------------------------------------------------
    public function eliminarViaje($id)
    {
        $id = intval($id);

        $sql = "DELETE FROM viajes_orden WHERE id = ?";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return false;

        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    // --------------------------------------------------
    // Contar viajes por OV
    // --------------------------------------------------
    public function contarViajesPorOV($ov_id)
    {
        $ov_id = intval($ov_id);

        $sql = "
            SELECT COUNT(id) AS total
            FROM viajes_orden
            WHERE orden_vehiculo_id = ?
        ";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return 0;

        $stmt->bind_param("i", $ov_id);
        $stmt->execute();

        $res = $stmt->get_result();
        if ($res && $res->num_rows > 0) {
            $row = $res->fetch_assoc();
            return intval($row['total']);
        }

        return 0;
    }
}
