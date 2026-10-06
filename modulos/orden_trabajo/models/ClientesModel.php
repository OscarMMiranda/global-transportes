<?php
// ======================================================
//  Archivo: /modulos/orden_trabajo/models/ClientesModel.php
//  MODELO: ClientesModel.php
//  MÓDULO: Clientes
//  RESPONSABILIDAD: Operaciones CRUD sobre clientes
//  GLOBAL 2026 — Arquitectura Limpia (Versión 1.0)
// ======================================================

class ClientesModel
{
    /** @var mysqli */
    private $conn;

    // ------------------------------
    // Constructor corporativo
    // ------------------------------
    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    // ======================================================
    //  MÉTODO: getClientesActivos()
    //  RESPONSABILIDAD: Obtener clientes activos para selects
    // ======================================================
    public function getClientesActivos()
    {
        $sql = "
            SELECT 
                id,
                nombre,
                ruc,
                estado
            FROM clientes
            WHERE estado = 'ACTIVO'
            ORDER BY nombre ASC
        ";

        $result = $this->conn->query($sql);

        if (!$result) {
            error_log("[ERP-CLIENTES] Error en getClientesActivos(): " . $this->conn->error);
            return [];
        }

        $clientes = [];

        while ($row = $result->fetch_assoc()) {
            $clientes[] = $row;
        }

        return $clientes;
    }

    // ======================================================
    //  MÉTODO: getClienteById()
    //  RESPONSABILIDAD: Obtener un cliente por ID
    // ======================================================
    public function getClienteById($id)
    {
        $sql = "
            SELECT 
                id,
                nombre,
                ruc,
                direccion,
                telefono,
                estado
            FROM clientes
            WHERE id = ?
            LIMIT 1
        ";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log("[ERP-CLIENTES] Error en prepare getClienteById(): " . $this->conn->error);
            return null;
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result ? $result->fetch_assoc() : null;
    }

    // ======================================================
    //  MÉTODO: listarClientes()
    //  RESPONSABILIDAD: Listado general (si se requiere)
    // ======================================================
    public function listarClientes()
    {
        $sql = "
            SELECT 
                id,
                nombre,
                ruc,
                estado
            FROM clientes
            ORDER BY nombre ASC
        ";

        $result = $this->conn->query($sql);

        if (!$result) {
            error_log("[ERP-CLIENTES] Error en listarClientes(): " . $this->conn->error);
            return [];
        }

        $clientes = [];

        while ($row = $result->fetch_assoc()) {
            $clientes[] = $row;
        }

        return $clientes;
    }
}
