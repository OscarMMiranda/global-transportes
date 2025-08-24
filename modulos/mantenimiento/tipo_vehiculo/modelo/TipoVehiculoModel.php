<?php
// models/TipoVehiculoModel.php

class TipoVehiculoModel
{
    /** @var mysqli */
    protected $db;

    /** @var callable|null */
    protected $auditLogger;

    /**
     * Constructor del modelo
     *
     * @param mysqli        $db
     * @param callable|null $auditLogger
     */
    public function __construct($db, $auditLogger = null)
    {
        $this->db          = $db;
        $this->auditLogger = $auditLogger;
    }

    /**
     * Listado completo de tipos no eliminados
     *
     * @return array
     * @throws Exception
     */
    public function listar()
    {
        $sql = "
            SELECT 
                tv.id, 
                tv.nombre, 
                tv.descripcion,
                cv.nombre AS categoria,
                tv.fecha_modificacion,
                tv.fecha_creado
            FROM tipo_vehiculo tv
            LEFT JOIN categoria_vehiculo cv ON tv.categoria_id = cv.id
            WHERE tv.fecha_borrado IS NULL
            ORDER BY tv.id DESC
        ";

        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error al preparar listado: " . $this->db->error);
        }

        $stmt->execute();
        $res   = $stmt->get_result();
        $tipos = $res->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $tipos;
    }

    /**
     * Obtiene tipos por estado (0 = activos, 1 = eliminados)
     *
     * @param int $estado
     * @return array
     * @throws Exception
     */
    public function obtenerPorEstado($estado = 0)
    {
        $where = ($estado == 0)
            ? "tv.fecha_borrado IS NULL"
            : "tv.fecha_borrado IS NOT NULL";

        $sql = "
            SELECT 
                tv.id,
                tv.nombre,
                tv.descripcion,
                cv.nombre AS categoria,
                tv.fecha_creado,
                tv.fecha_modificacion,
                tv.fecha_borrado
            FROM tipo_vehiculo tv
            LEFT JOIN categoria_vehiculo cv ON tv.categoria_id = cv.id
            WHERE {$where}
            ORDER BY tv.id DESC
        ";

        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error al preparar obtenerPorEstado: " . $this->db->error);
        }

        $stmt->execute();
        $res   = $stmt->get_result();
        $tipos = $res->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $tipos;
    }

    /**
     * Obtiene un tipo por ID si no está borrado
     *
     * @param int $id
     * @return array|null
     * @throws Exception
     */
    public function obtenerPorId($id)
    {
        $sql = "
            SELECT 
                id, 
                nombre, 
                descripcion,
                categoria_id,
                fecha_modificacion
            FROM tipo_vehiculo
            WHERE id = ? AND fecha_borrado IS NULL
        ";

        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error al preparar obtenerPorId: " . $this->db->error);
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res  = $stmt->get_result();
        $tipo = $res->num_rows ? $res->fetch_assoc() : null;
        $stmt->close();

        return $tipo;
    }

    /**
     * Verifica si existe un tipo eliminado con ese nombre
     *
     * @param string $nombre
     * @return array|null
     */
    public function verificarEliminadoPorNombre($nombre)
    {
        $sql = "
            SELECT id, descripcion, categoria_id 
            FROM tipo_vehiculo 
            WHERE nombre = ? 
              AND fecha_borrado IS NOT NULL 
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $nombre);
        $stmt->execute();
        $res  = $stmt->get_result();
        $tipo = $res->num_rows ? $res->fetch_assoc() : null;
        $stmt->close();

        return $tipo;
    }

    /**
     * Inserta un nuevo tipo
     *
     * @param string $nombre
     * @param string $descripcion
     * @param int    $categoriaId
     * @param int    $usuarioId
     * @return int
     * @throws Exception
     */
    public function crear($nombre, $descripcion, $categoriaId, $usuarioId)
    {
        if (!$this->esNombreUnico($nombre)) {
            throw new Exception("Ya existe un tipo con el nombre '{$nombre}'.");
        }

        $sql = "
            INSERT INTO tipo_vehiculo 
                (nombre, descripcion, categoria_id, creado_por, fecha_creado)
            VALUES (?, ?, ?, ?, NOW())
        ";

        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error al preparar INSERT: " . $this->db->error);
        }

        $stmt->bind_param("ssii", $nombre, $descripcion, $categoriaId, $usuarioId);
        $stmt->execute();

        $id = $stmt->insert_id;
        $stmt->close();

        $this->logCambio(
            $id, 
            $usuarioId, 
            "Creado con nombre='{$nombre}', descripción='{$descripcion}'"
        );

        return $id;
    }

    /**
     * Actualiza un tipo existente
     *
     * @param int    $id
     * @param string $nombre
     * @param string $descripcion
     * @param int    $categoriaId
     * @param int    $usuarioId
     * @throws Exception
     */
    public function actualizar($id, $nombre, $descripcion, $categoriaId, $usuarioId)
    {
        if (!$this->esNombreUnico($nombre, $id)) {
            throw new Exception("Ya existe un tipo con el nombre '{$nombre}'.");
        }

        $anterior = $this->obtenerPorId($id);
        if (!$anterior) {
            throw new Exception("El tipo con ID {$id} no existe.");
        }

        $sql = "
            UPDATE tipo_vehiculo
            SET 
                nombre            = ?, 
                descripcion       = ?, 
                categoria_id      = ?, 
                fecha_modificacion = NOW()
            WHERE id = ?
        ";

        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error al preparar UPDATE: " . $this->db->error);
        }

        $stmt->bind_param("ssii", $nombre, $descripcion, $categoriaId, $id);
        $stmt->execute();
        $stmt->close();

        $this->logCambio(
            $id, 
            $usuarioId, 
            sprintf(
                "Actualizado: nombre '%s'→'%s', descripción '%s'→'%s', categoría '%s'→'%s'",
                $anterior['nombre'],      $nombre,
                $anterior['descripcion'], $descripcion,
                $anterior['categoria_id'], $categoriaId
            )
        );
    }

    /**
     * Elimina (soft delete) un tipo
     *
     * @param int $id
     * @param int $usuarioId
     */
    public function eliminar($id, $usuarioId)
    {
        $sql = "
            UPDATE tipo_vehiculo
            SET fecha_borrado = NOW()
            WHERE id = ? 
              AND fecha_borrado IS NULL
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

        $this->logCambio($id, $usuarioId, "Marcado como borrado");
    }

    /**
     * Reactiva un tipo eliminado
     *
     * @param int $id
     * @param int $usuarioId
     * @throws Exception
     */
    public function reactivar($id, $usuarioId)
    {
        $sql = "
            UPDATE tipo_vehiculo
            SET 
                fecha_borrado      = NULL,
                fecha_modificacion = NOW()
            WHERE id = ?
        ";

        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error al preparar reactivar(): " . $this->db->error);
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

        $this->logCambio($id, $usuarioId, "Reactivado desde estado eliminado");
    }

    /**
     * Valida que el nombre sea único (excluyendo opcionalmente un ID)
     *
     * @param string   $nombre
     * @param int|null $ignoreId
     * @return bool
     * @throws Exception
     */
    protected function esNombreUnico($nombre, $ignoreId = null)
    {
        $sql = "
            SELECT COUNT(*) 
            FROM tipo_vehiculo 
            WHERE nombre = ? 
              AND fecha_borrado IS NULL
        ";

        if ($ignoreId !== null) {
            $sql .= " AND id <> ?";
        }

        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error al preparar validación nombre: " . $this->db->error);
        }

        if ($ignoreId !== null) {
            $stmt->bind_param("si", $nombre, $ignoreId);
        } else {
            $stmt->bind_param("s", $nombre);
        }

        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        return ($count === 0);
    }

    /**
     * Registra un cambio en el historial si el logger está definido
     *
     * @param int    $tipoId
     * @param int    $usuarioId
     * @param string $cambio
     */
    protected function logCambio($tipoId, $usuarioId, $cambio)
    {
        if (is_callable($this->auditLogger)) {
            call_user_func($this->auditLogger, $tipoId, $usuarioId, $cambio);
        }
    }

}  // Fin de la clase TipoVehiculoModel
