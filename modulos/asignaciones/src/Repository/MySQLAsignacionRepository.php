<?php
    namespace Modules\Asignaciones\Repository;

	use Modules\Asignaciones\Model\Asignacion;
	use mysqli;

	class MySQLAsignacionRepository implements AsignacionRepositoryInterface
		{
    	/** @var mysqli */
    	private $db;

    	public function __construct(mysqli $db)
    		{
        	$this->db = $db;
    		}

    	public function findAll(): array
    		{
        	$sql = "SELECT * FROM asignaciones ORDER BY fecha_asignacion DESC";
        	$res = $this->db->query($sql);
        	$list = [];
        	while ($row = $res->fetch_assoc()) {
        	    $list[] = Asignacion::fromArray($row);
        		}
        	return $list;
    		}

    	public function findById(int $id)
    		{
    	    $stmt = $this->db->prepare("SELECT * FROM asignaciones WHERE id = ? LIMIT 1");
        	$stmt->bind_param('i', $id);
        	$stmt->execute();
        	$row = $stmt->get_result()->fetch_assoc();
        	return $row ? Asignacion::fromArray($row) : null;
    		}

    	public function save(Asignacion $as)
    		{
        	$stmt = $this->db->prepare(
            	"INSERT INTO asignaciones (conductor_id, tracto_id, carreta_id, fecha_asignacion, estado)
            	VALUES (?, ?, ?, ?, ?)"
        		);
        	$stmt->bind_param(
            	'iiiss',
            	$as->getConductorId(),
            	$as->getTractoId(),
            	$as->getCarretaId(),
            	$as->getFechaAsignacion(),
            	$as->getEstado()
        		);
        	$stmt->execute();
        	return $this->db->insert_id;
    		}

    	public function update(Asignacion $asignacion): void
    		{
        	$stmt = $this->db->prepare(
        	    "UPDATE asignaciones
        	     SET conductor_id = ?, tracto_id = ?, carreta_id = ?, fecha_asignacion = ?, estado = ?
        	     WHERE id = ?"
        		);
        	$stmt->bind_param(
        	    'iiissi',
        	    $asignacion->getConductorId(),
        	    $asignacion->getTractoId(),
        	    $asignacion->getCarretaId(),
        	    $asignacion->getFechaAsignacion(),
        	    $asignacion->getEstado(),
        	    $asignacion->getId()
        		);
        	$stmt->execute();
    		}

    	public function delete(int $id): void
    		{
        	// Puede ser un DELETE físico o un UPDATE de estado
        	$stmt = $this->db->prepare("DELETE FROM asignaciones WHERE id = ?");
        	$stmt->bind_param('i', $id);
        	$stmt->execute();
    		}
	}
