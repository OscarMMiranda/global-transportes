<?php
namespace Modules\Asignaciones\Service;

use mysqli;
use Modules\Asignaciones\Repository\AsignacionRepositoryInterface;
use Modules\Asignaciones\Repository\MySQLAsignacionRepository;
use Modules\Asignaciones\Model\Asignacion;
use Modules\Asignaciones\DTO\AsignacionDTO;
use Modules\Asignaciones\Repository\ConductorRepositoryInterface;
use Modules\Asignaciones\Repository\TractoRepositoryInterface;
use Modules\Asignaciones\Repository\CarretaRepositoryInterface;

class AsignacionService
{
    /** @var mysqli */
    private $db;

    /** @var AsignacionRepositoryInterface */
    private $repo;

    /** @var ConductorRepositoryInterface */
    private $conductorRepo;

    /** @var TractoRepositoryInterface */
    private $tractoRepo;

    /** @var CarretaRepositoryInterface */
    private $carretaRepo;

    public function __construct(
        mysqli $db,
        AsignacionRepositoryInterface $repo,
        ConductorRepositoryInterface $conductorRepo,
        TractoRepositoryInterface $tractoRepo,
        CarretaRepositoryInterface $carretaRepo
    ) {
        $this->db           = $db;
        $this->repo         = $repo;
        $this->conductorRepo= $conductorRepo;
        $this->tractoRepo   = $tractoRepo;
        $this->carretaRepo  = $carretaRepo;
    }

    /**
     * Devuelve todas las asignaciones.
     * @return Asignacion[]
     */
    public function listAll(): array
    {
        return $this->repo->findAll();
    }

    /**
     * Obtiene recursos disponibles para el create().
     * @return array [ 'conductores'=>[], 'tractos'=>[], 'carretas'=>[] ]
     */
    public function getRecursosDisponibles(): array
    {
        return [
            'conductores' => $this->conductorRepo->findAvailable(),
            'tractos'     => $this->tractoRepo->findAvailable(),
            'carretas'    => $this->carretaRepo->findAvailable(),
        ];
    }

    /**
     * Crea una asignación y bloquea recursos en una transacción.
     * @param AsignacionDTO $dto
     * @return int ID de la nueva asignación
     * @throws \Exception
     */
    public function create(AsignacionDTO $dto): int
    {
        $this->db->begin_transaction();

        try {
            // 1. Validar disponibilidad
            if (! $this->conductorRepo->isAvailable($dto->conductorId)) {
                throw new \Exception('Conductor no disponible');
            }
            if (! $this->tractoRepo->isAvailable($dto->tractoId)) {
                throw new \Exception('Tracto no disponible');
            }
            if (! $this->carretaRepo->isAvailable($dto->carretaId)) {
                throw new \Exception('Carreta no disponible');
            }

            // 2. Insertar asignación
            $as = new Asignacion(
                null,
                $dto->conductorId,
                $dto->tractoId,
                $dto->carretaId,
                $dto->fechaAsignacion,
                $dto->estado
            );
            $newId = $this->repo->save($as);

            // 3. Marcar recursos como no disponibles
            $this->conductorRepo->markUnavailable($dto->conductorId);
            $this->tractoRepo->markUnavailable($dto->tractoId);
            $this->carretaRepo->markUnavailable($dto->carretaId);

            // 4. Registrar en historial
            $desc = sprintf(
                "Asignación #%d: conductor %d, tracto %d, carreta %d",
                $newId, $dto->conductorId, $dto->tractoId, $dto->carretaId
            );
            $this->repo->insertHistorial($newId, 'create', $desc);

            $this->db->commit();
            return $newId;
        }
        catch (\Exception $e) {
            $this->db->rollback();
            throw $e;
        }
    }

    // Métodos edit(), update(), delete() siguen patrón similar
}
