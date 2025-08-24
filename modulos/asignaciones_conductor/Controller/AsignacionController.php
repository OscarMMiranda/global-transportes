<?php namespace Controller;

use Model\AsignacionModel;
use Helper\RedirectHelper;
use Helper\FlashHelper;
use Helper\SessionHelper;

class AsignacionController
{
    protected $model;
    protected $conn;

    public function __construct($conn)
    {
        $this->conn  = $conn;
        $this->model = new AsignacionModel($conn);
    }

    public function list()
    {
        $act   = $this->model->getEstadoId('activo');
        $hist  = $this->model->getEstadoId('finalizado');

        $asigAct = $this->model->getAsignacionesActivas($act);
        $asigHis = $this->model->getHistorialAsignaciones($hist);

        include __DIR__ . '/../View/list.php';
    }

    public function form()
    {
        include __DIR__ . '/../View/form.php';
    }

    public function create()
    {
        $data = [
            'conductor_id'  => (int) $_POST['conductor_id'],
            'vehiculo_id'   => (int) $_POST['vehiculo_id'],
            'user_id'       => $_SESSION['id'],
            'observaciones' => $_POST['observaciones'] ?? ''
        ];

        if ($this->model->registrarAsignacion($data)) {
            FlashHelper::success('Asignación registrada.');
            RedirectHelper::to('index.php?action=list');
        } else {
            FlashHelper::danger('Error al crear asignación.');
            RedirectHelper::to('index.php?action=form');
        }
    }

    public function finalize()
    {
        $id = (int) $_GET['id'];
        if ($this->model->finalizarAsignacion($id, $_SESSION['id'])) {
            FlashHelper::success('Asignación finalizada.');
        } else {
            FlashHelper::danger('No se pudo finalizar.');
        }
        RedirectHelper::to('index.php?action=list');
    }
}
