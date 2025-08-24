<?php
// models/CategoriaModel.php

/**
 * Modelo para el módulo de Categorías.
 */
class CategoriaModel
{
    /** @var mysqli */
    protected $db;

    /**
     * @param mysqli $db
     */
    public function __construct(mysqli $db)
    {
        $this->db = $db;
    }

    /**
     * Obtiene todas las categorías activas (no borradas).
     *
     * @return array
     * @throws Exception
     */
    public function listar()
    {
        $sql  = "SELECT 
                    id, 
                    nombre
                 FROM categoria_vehiculo
                 WHERE fecha_borrado IS NULL
                 ORDER BY nombre";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error al preparar listado de categorías: " . $this->db->error);
        }
        $stmt->execute();
        $res         = $stmt->get_result();
        $categorias  = $res->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $categorias;
    }

    // Aquí puedes extender con métodos como crear(), actualizar(), eliminar()…
}
