<?php
// ======================================================
//  ARCHIVO: /modulos/orden_trabajo/models/OrdenModel.php
//  MODELO: OrdenModel
// ======================================================

class OrdenModel {

    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // ------------------------------------------------------
    //  LISTAR POR ESTADO (NO SE USA EN EL CONTROLADOR)
    // ------------------------------------------------------
    public function listar($estado) {
        $estado = mysqli_real_escape_string($this->conn, $estado);

        $sql = "
            SELECT id, codigo, descripcion, estado, fecha, semana
            FROM orden_trabajo
            WHERE estado = '$estado'
            ORDER BY id DESC
        ";

        return mysqli_query($this->conn, $sql);
    }

    // ------------------------------------------------------
    //  OBTENER POR ID
    // ------------------------------------------------------
    public function obtener($id) {
        $id = intval($id);

        $sql = "
            SELECT id, codigo, descripcion, estado, fecha, semana
            FROM orden_trabajo
            WHERE id = $id
            LIMIT 1
        ";

        return mysqli_query($this->conn, $sql);
    }

    // ------------------------------------------------------
    //  CREAR ORDEN
    // ------------------------------------------------------
    public function crear($data) {

        $codigo      = mysqli_real_escape_string($this->conn, $data['codigo']);
        $descripcion = mysqli_real_escape_string($this->conn, $data['descripcion']);
        $semana      = intval($data['semana']);

        $sql = "
            INSERT INTO orden_trabajo (codigo, descripcion, estado, fecha, semana)
            VALUES ('$codigo', '$descripcion', 'ACTIVA', NOW(), $semana)
        ";

        return mysqli_query($this->conn, $sql);
    }

    // ------------------------------------------------------
    //  ACTUALIZAR ORDEN
    // ------------------------------------------------------
    public function actualizar($data) {

        $id          = intval($data['id']);
        $codigo      = mysqli_real_escape_string($this->conn, $data['codigo']);
        $descripcion = mysqli_real_escape_string($this->conn, $data['descripcion']);
        $semana      = intval($data['semana']);

        $sql = "
            UPDATE orden_trabajo
            SET codigo = '$codigo',
                descripcion = '$descripcion',
                semana = $semana
            WHERE id = $id
        ";

        return mysqli_query($this->conn, $sql);
    }

    // ------------------------------------------------------
    //  ANULAR ORDEN
    // ------------------------------------------------------
    public function anular($id) {
        $id = intval($id);

        $sql = "
            UPDATE orden_trabajo
            SET estado = 'ANULADA'
            WHERE id = $id
        ";

        return mysqli_query($this->conn, $sql);
    }

    // ------------------------------------------------------
    //  ELIMINAR ORDEN
    // ------------------------------------------------------
    public function eliminar($id) {
        $id = intval($id);

        $sql = "
            UPDATE orden_trabajo
            SET estado = 'ELIMINADA'
            WHERE id = $id
        ";

        return mysqli_query($this->conn, $sql);
    }

    // ------------------------------------------------------
    //  VALIDAR CÓDIGO DUPLICADO
    // ------------------------------------------------------
    public function existeCodigo($codigo, $id = 0) {

        $codigo = mysqli_real_escape_string($this->conn, $codigo);
        $id     = intval($id);

        $sql = "
            SELECT id
            FROM orden_trabajo
            WHERE codigo = '$codigo'
              AND id != $id
            LIMIT 1
        ";

        return mysqli_query($this->conn, $sql);
    }

    // ------------------------------------------------------
    //  🔵 MÉTODO QUE SÍ EXISTE PERO EL CONTROLADOR NO USA
    // ------------------------------------------------------
    public function getSemanas() {
        $sql = "
            SELECT DISTINCT semana
            FROM orden_trabajo
            ORDER BY semana DESC
        ";
        return mysqli_query($this->conn, $sql);
    }

    // ======================================================
    //  🔵 MÉTODOS QUE FALTABAN (LOS QUE EL CONTROLADOR USA)
    // ======================================================

    // ------------------------------------------------------
    //  ACTIVAS POR SEMANA
    // ------------------------------------------------------
    public function obtenerActivasPorSemana($semana) {

        $sql = "
            SELECT id, codigo, descripcion, estado, fecha, semana
            FROM orden_trabajo
            WHERE estado = 1
        ";

        if ($semana != "") {
            $semana = mysqli_real_escape_string($this->conn, $semana);
            $sql .= " AND semana = '$semana'";
        }

        $sql .= " ORDER BY id DESC";

        return mysqli_query($this->conn, $sql);
    }

    // ------------------------------------------------------
    //  POR ESTADO Y SEMANA (ANULADAS / ELIMINADAS)
    // ------------------------------------------------------
    public function obtenerPorEstadoYSemana($estado, $semana) {

        $estado = intval($estado);

        $sql = "
            SELECT id, codigo, descripcion, estado, fecha, semana
            FROM orden_trabajo
            WHERE estado = $estado
        ";

        if ($semana != "") {
            $semana = mysqli_real_escape_string($this->conn, $semana);
            $sql .= " AND semana = '$semana'";
        }

        $sql .= " ORDER BY id DESC";

        return mysqli_query($this->conn, $sql);
    }

    // ------------------------------------------------------
    //  SEMANAS DISPONIBLES (NOMBRE QUE EL CONTROLADOR USA)
    // ------------------------------------------------------
    public function obtenerSemanas() {
        return $this->getSemanas();
    }
}
?>
