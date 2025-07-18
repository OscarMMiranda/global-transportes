<?php
// public_html/modulos/clientes/models/Ubigeo.php

class Ubigeo
{
    /** @var mysqli */
    protected static $conn;

    public static function init($mysqli)
    {
        self::$conn = $mysqli;
    }

    /**
     * Devuelve todos los departamentos.
     * @return array  [ ['id'=>1,'nombre'=>'Lima'], ... ]
     */
    public static function getDepartamentos()
    {
        $rows = [];
        $sql = "SELECT id, nombre FROM departamentos ORDER BY nombre";
        if ($stmt = self::$conn->prepare($sql)) {
            $stmt->execute();
            $res = $stmt->get_result();
            while ($r = $res->fetch_assoc()) {
                $rows[] = $r;
            }
            $stmt->close();
        }
        return $rows;
    }

    /**
     * Devuelve las provincias de un departamento.
     * @param int $depId
     * @return array
     */
    public static function getProvincias($depId)
    {
        $rows = [];
        $sql = "SELECT id, nombre FROM provincias
                WHERE departamento_id = ?
                ORDER BY nombre";
        if ($stmt = self::$conn->prepare($sql)) {
            $stmt->bind_param('i', $depId);
            $stmt->execute();
            $res = $stmt->get_result();
            while ($r = $res->fetch_assoc()) {
                $rows[] = $r;
            }
            $stmt->close();
        }
        return $rows;
    }

    /**
     * Devuelve los distritos de una provincia.
     * @param int $provId
     * @return array
     */
    public static function getDistritos($provId)
    {
        $rows = [];
        $sql = "SELECT id, nombre FROM distritos
                WHERE provincia_id = ?
                ORDER BY nombre";
        if ($stmt = self::$conn->prepare($sql)) {
            $stmt->bind_param('i', $provId);
            $stmt->execute();
            $res = $stmt->get_result();
            while ($r = $res->fetch_assoc()) {
                $rows[] = $r;
            }
            $stmt->close();
        }
        return $rows;
    }
}
