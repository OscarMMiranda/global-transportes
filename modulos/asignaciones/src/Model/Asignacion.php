<?php
namespace Modules\Asignaciones\Model;

class Asignacion
{
    private $id;
    private $conductorId;
    private $tractoId;
    private $carretaId;
    private $fechaAsignacion;
    private $estado;

    public function __construct(
        $id, $conductorId, $tractoId, $carretaId, $fechaAsignacion, $estado
    ) {
        $this->id              = $id;
        $this->conductorId     = $conductorId;
        $this->tractoId        = $tractoId;
        $this->carretaId       = $carretaId;
        $this->fechaAsignacion = $fechaAsignacion;
        $this->estado          = $estado;
    }

    public static function fromArray(array $data)
    {
        return new self(
            $data['id'],
            $data['conductor_id'],
            $data['tracto_id'],
            $data['carreta_id'],
            $data['fecha_asignacion'],
            $data['estado']
        );
    }

    // Getters y setters omitidos por brevedad...
}
