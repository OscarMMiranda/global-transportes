<?php
// 1) Mostrar errores (solo en desarrollo)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 2) Sesión y conexión
require_once __DIR__ . '/../../../includes/conexion.php';
require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

// 3) Cargar modelo
require_once __DIR__ . '/modelo/TipoVehiculoModel.php';

class TipoVehiculoController {
    private $conn;
    private $modelo;

    public function __construct($conn) {
        $this->conn = $conn;

        $auditLogger = function($tipoId, $usuarioId, $cambio) use ($conn) {
            $sql  = "INSERT INTO tipo_vehiculo_historial
                     (tipo_id, usuario_id, cambio, fecha)
                     VALUES (?, ?, ?, NOW())";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iis", $tipoId, $usuarioId, $cambio);
            $stmt->execute();
            $stmt->close();
        };

        $this->modelo = new TipoVehiculoModel($conn, $auditLogger);
    }

    // 🔍 Listado principal
    public function index() {
        $tipos_activos = $this->modelo->obtenerPorEstado(0);
        $tipos_eliminados = $this->modelo->obtenerPorEstado(1);

        $msg = isset($_GET['msg']) ? $_GET['msg'] : '';
        $error = isset($_GET['error']) ? $_GET['error'] : '';

        include __DIR__ . '/view.php';
    }

    // 🆕 Crear nuevo
    public function create() {
        require_once __DIR__ . '/modelo/CategoriaModel.php';
        $categoriaModel = new CategoriaModel($this->conn);
        $categorias = $categoriaModel->listar();

        include __DIR__ . '/form_create.php';
    }


/**
 * 💾 Procesa y guarda un nuevo tipo de vehículo
 */
	public function store($data)
	{
		// DEBUG: ver qué llega desde el form
		error_log('STORE DATA → ' . print_r($data, true));

    	// 1) Extraer y sanear campos
    	$nombre      = isset($data['nombre'])      ? trim($data['nombre'])      : '';
    	$descripcion = isset($data['descripcion']) ? trim($data['descripcion']) : '';
    	$categoriaId = isset($data['categoria_id']) ? (int) $data['categoria_id'] : 0;

    	// 2) Validación básica
    	if ($nombre === '' || $categoriaId <= 0) {
    	    $error = 'Nombre y categoría son obligatorios';
    	    header('Location: index.php?error=' . urlencode($error));
    	    exit;
    		}

    	// 3) Usuario actual
    	$usuarioId = $this->getUsuarioId();

    	// 4) ¿Existe un eliminado con ese nombre?
    	$eliminado = $this->modelo->verificarEliminadoPorNombre($nombre);
    	if ($eliminado) {
    	    // Guardamos en sesión y vamos al prompt de reactivación
    	    $_SESSION['reactivar_id']           = $eliminado['id'];
    	    $_SESSION['reactivar_nombre']       = $nombre;
    	    $_SESSION['reactivar_descripcion']  = $descripcion;
    	    $_SESSION['reactivar_categoria_id'] = $categoriaId;

    	    header('Location: index.php?action=reactivar_prompt');
    	    exit;
    		}

    	// 5) Creamos en la base de datos
    	$this->modelo->crear($nombre, $descripcion, $categoriaId, $usuarioId);

    	// 6) Redirigimos con mensaje de éxito
    	header('Location: index.php?msg=agregado');
    	exit;
	}



    // ✏️ Editar existente
    public function edit($id) {
        $registro = $this->modelo->obtenerPorId($id);

        require_once __DIR__ . '/modelo/CategoriaModel.php';
        $categoriaModel = new CategoriaModel($this->conn);
        $categorias = $categoriaModel->listar();

        include __DIR__ . '/form_edit.php';
    }

/**
 * 🔄 Procesa la actualización de un tipo existente
 */
	public function update($data)
	{
    	// 1) Extraer y sanear campos
    	$id          = isset($_GET['id']) ? (int) $_GET['id'] : 0;
    	$nombre      = isset($data['nombre'])      ? trim($data['nombre'])      : '';
    	$descripcion = isset($data['descripcion']) ? trim($data['descripcion']) : '';
    	$categoriaId = isset($data['categoria_id']) ? (int) $data['categoria_id'] : 0;

    	// 2) Validación básica
    	if ($id <= 0 || $nombre === '' || $categoriaId <= 0) {
    	    $error = 'ID, nombre y categoría son obligatorios';
    	    header('Location: index.php?error=' . urlencode($error));
    	    exit;
    		}

    	// 3) Usuario actual para trazabilidad
    	$usuarioId = $this->getUsuarioId();

    	// 4) Verificar si otro registro eliminado ya usa ese nombre
    	$eliminado = $this->modelo->verificarEliminadoPorNombre($nombre);
    	if ($eliminado && $eliminado['id'] != $id) {
    	    $_SESSION['reactivar_id']           = $eliminado['id'];
    	    $_SESSION['reactivar_nombre']       = $nombre;
    	    $_SESSION['reactivar_descripcion']  = $descripcion;
    	    $_SESSION['reactivar_categoria_id'] = $categoriaId;

        	header('Location: index.php?action=reactivar_prompt');
        	exit;
    		}
		
		try {
    		// 5) Ejecutar la actualización
    		$this->modelo->actualizar($id, $nombre, $descripcion, $categoriaId, $usuarioId);
    		// 6) Redirigir con mensaje de éxito
    		header('Location: index.php?msg=actualizado');
    		} 
		catch (Exception $e) {
        	// Redirigir con el mensaje de error
        	header('Location: index.php?action=edit&id=' . $id . '&error=' . urlencode($e->getMessage()));
    		}
		exit;

		}

    // 🗑️ Eliminar (soft delete)
    public function delete($id) 
		{
		$usuarioId = $this->getUsuarioId();
        $this->modelo->eliminar($id, $usuarioId);
        header('Location: index.php?msg=eliminado');
        exit;
    	}

    // 🔁 Reactivar registro eliminado
	public function reactivar()
		{
    	$usuarioId = $this->getUsuarioId();

    	// Detectar ID desde POST, SESSION o GET
    	$id = 0;
    	if (isset($_POST['id'])) {
    	    $id = (int) $_POST['id'];
    	} elseif (isset($_SESSION['reactivar_id'])) {
    	    $id = (int) $_SESSION['reactivar_id'];
    	} elseif (isset($_GET['id'])) {
    	    $id = (int) $_GET['id'];
    	}

    	if ($id <= 0) {
    	    header('Location: index.php?error=' . urlencode('ID inválido para reactivación'));
    	    exit;
    		}

    	// Ejecutar reactivación
    	$this->modelo->reactivar($id, $usuarioId);

    	// Limpiar sesión si venía desde el flujo de alta
    	unset(
    	    $_SESSION['reactivar_id'],
    	    $_SESSION['reactivar_nombre'],
    	    $_SESSION['reactivar_descripcion'],
    	    $_SESSION['reactivar_categoria_id']
    		);
    	session_write_close();

    	header("Location: index.php?msg=reactivado");
    	exit;
		}


    // 👁️ Mostrar prompt de reactivación
    public function reactivar_prompt() {
        $nombre = $_SESSION['reactivar_nombre'];
        include __DIR__ . '/reactivar_prompt.php';
    }

    // 🔐 Validación de sesión
    private function getUsuarioId() {
        if (!isset($_SESSION['usuario_id'])) {
            throw new Exception("usuario_id no definido en la sesión.");
        }
        return $_SESSION['usuario_id'];
    }
}
