<?php
namespace Modules\Asignaciones\Controller;

use Modules\Asignaciones\Service\AsignacionService;
use Modules\Asignaciones\DTO\AsignacionDTO;

class AsignacionesController
{
    /** @var AsignacionService */
    private $service;

    public function __construct(AsignacionService $service)
    {
        $this->service = $service;
    }

    // Listar todas las asignaciones
    public function index(): void
    {
        $asignaciones = $this->service->listAll();
        include __DIR__ . '/../View/index.php';
    }

    // Mostrar formulario de creación
    public function create(): void
    {
        $recursos = $this->service->getRecursosDisponibles();
        include __DIR__ . '/../View/create.php';
    }

    // Procesar creación
    public function store(): void
    {
        try {
            $dto = new AsignacionDTO(
                $_POST['conductor_id'],
                $_POST['tracto_id'],
                $_POST['carreta_id'],
                $_POST['fecha_asignacion'],
                'pendiente'
            );
            $newId = $this->service->create($dto);
            header("Location: /asignaciones");
        } catch (\Exception $e) {
            $error = $e->getMessage();
            $recursos = $this->service->getRecursosDisponibles();
            include __DIR__ . '/../View/create.php';
        }
    }

    // Mostrar formulario de edición
    public function edit(int $id): void
    {
        $asig       = $this->service->findById($id);
        $recursos   = $this->service->getRecursosDisponibles();
        include __DIR__ . '/../View/edit.php';
    }

    // Procesar actualización
    public function update(int $id): void
    {
        try {
            $dto = new AsignacionDTO(
                $_POST['conductor_id'],
                $_POST['tracto_id'],
                $_POST['carreta_id'],
                $_POST['fecha_asignacion'],
                $_POST['estado'] ?? 'pendiente'
            );
            $this->service->update($id, $dto);
            header("Location: /asignaciones");
        } catch (\Exception $e) {
            $error = $e->getMessage();
            $asig     = $this->service->findById($id);
            $recursos = $this->service->getRecursosDisponibles();
            include __DIR__ . '/../View/edit.php';
        }
    }

    // Eliminar asignación
    public function delete(int $id): void
    {
        try {
            $this->service->delete($id);
            header("Location: /asignaciones");
        } catch (\Exception $e) {
            $error = $e->getMessage();
            $asignaciones = $this->service->listAll();
            include __DIR__ . '/../View/index.php';
        }
    }
}
