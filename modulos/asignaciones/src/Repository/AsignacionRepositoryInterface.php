<?php
	namespace Modules\Asignaciones\Repository;

	use Modules\Asignaciones\Model\Asignacion;

	interface AsignacionRepositoryInterface
	{
    	/**
    	 * Devuelve todas las asignaciones.
    	 * @return Asignacion[]
    	 */
    	public function findAll(): array;

    	/**
    	 * Busca una asignación por su ID.
    	 * @param int $id
    	 * @return Asignacion|null
    	 */
    	public function findById(int $id);

    	/**
    	 * Persiste una nueva asignación.
    	 * @param Asignacion $asignacion
    	 * @return int Nuevo ID
    	 */
    	public function save(Asignacion $asignacion): int;

    	/**
    	 * Actualiza una asignación existente.
    	 * @param Asignacion $asignacion
    	 * @return void
    	 */
    	public function update(Asignacion $asignacion): void;

    	/**
    	 * Elimina (o marca) una asignación.
    	 * @param int $id
    	 * @return void
    	 */
    	public function delete(int $id): void;
	}
