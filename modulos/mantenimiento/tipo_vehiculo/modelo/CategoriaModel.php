<?php
// archivo: CategoriaModel.php

class CategoriaModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    /**
     * Listar todas las categorías no eliminadas
     *
     * @return array
     */
    public function listar() {
        $sql = "
            SELECT id, nombre 
            FROM categoria_vehiculo 
            WHERE fecha_borrado IS NULL 
            ORDER BY nombre ASC
        ";

        $result = $this->conn->query($sql);
        if (!$result) {
            error_log("Error al listar categorías: " . $this->conn->error);
            return array(); // fallback defensivo
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Obtener una categoría por ID
     *
     * @param int $id
     * @return array|null
     */
    public function obtenerPorId($id) {
        $sql = "
            SELECT id, nombre, descripcion 
            FROM categoria_vehiculo 
            WHERE id = ? AND fecha_borrado IS NULL
        ";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log("Error al preparar obtenerPorId: " . $this->conn->error);
            return null;
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $categoria = $res->num_rows ? $res->fetch_assoc() : null;
        $stmt->close();

        return $categoria;
    }
}