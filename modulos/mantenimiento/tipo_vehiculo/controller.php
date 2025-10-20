<?php
	// archivo: /modulos/mantenimiento/tipo_vehiculo/controller.php

	// 🔍 Modo depuración (solo en desarrollo)	
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	ini_set('log_errors', 1);
	ini_set('error_log', __DIR__ . '/error_log.txt');

	require_once __DIR__ . '/modelo/TipoVehiculoModel.php';

	class TipoVehiculoController {
    	private $conn;
    	private $modelo;

    	public function __construct($conn) {
        	$this->conn = $conn;

        	// 🧠 Logger de auditoría incremental
        	$auditLogger = function($tipoId, $usuarioId, $cambio) use ($conn) {
            	$sql  = "INSERT INTO tipo_vehiculo_historial (tipo_id, usuario_id, cambio, fecha) VALUES (?, ?, ?, NOW())";
            	$stmt = $conn->prepare($sql);
            	$stmt->bind_param("iis", $tipoId, $usuarioId, $cambio);
            	$stmt->execute();
            	$stmt->close();
        		};

        	$this->modelo = new TipoVehiculoModel($conn, $auditLogger);
    		}

    	// 🧩 Vista principal con pestañas y modales
    	public function index() {
        	$tipos_activos    = $this->modelo->obtenerPorEstado(0);
        	$tipos_eliminados = $this->modelo->obtenerPorEstado(1);
        	$msg   = isset($_GET['msg'])   ? $_GET['msg']   : '';
        	$error = isset($_GET['error']) ? $_GET['error'] : '';
        	include __DIR__ . '/vistas/view.php';
    		}

    	// 🆕 Mostrar formulario de creación
    	public function create() {
        	require_once __DIR__ . '/modelo/CategoriaModel.php';
        	$categoriaModel = new CategoriaModel($this->conn);
        	$categorias = $categoriaModel->listar();

        	include __DIR__ . '/form_create.php';
    		}

    	// 💾 Guardar nuevo tipo
    	public function store($data) {
        	$nombre      = isset($data['nombre'])      ? trim($data['nombre'])      : '';
        	$descripcion = isset($data['descripcion']) ? trim($data['descripcion']) : '';
        	$categoriaId = isset($data['categoria_id']) ? (int) $data['categoria_id'] : 0;

        	if ($nombre === '' || $categoriaId <= 0) {
            	header('Location: index.php?error=' . urlencode('Nombre y categoría son obligatorios'));
            	exit;
        		}

        	$usuarioId = $this->getUsuarioId();

        	$eliminado = $this->modelo->verificarEliminadoPorNombre($nombre);
        	if ($eliminado) {
            	$_SESSION['reactivar_id']           = $eliminado['id'];
            	$_SESSION['reactivar_nombre']       = $nombre;
            	$_SESSION['reactivar_descripcion']  = $descripcion;
            	$_SESSION['reactivar_categoria_id'] = $categoriaId;

            	header('Location: index.php?action=reactivar_prompt');
            	exit;
        		}

        	$this->modelo->crear($nombre, $descripcion, $categoriaId, $usuarioId);
        	header('Location: index.php?msg=agregado');
        	exit;
    		}

    	// ✏️ Mostrar formulario de edición
		public function edit($id) {
    		error_log("📦 edit() llamado con ID: $id");

    		// Validación defensiva del ID
    		if ($id <= 0) {
        		error_log("⚠️ ID inválido recibido en edit(): $id");
        		echo '<div class="alert alert-danger">❌ ID inválido.</div>';
        		return;
    			}

    		$vehiculo = $this->modelo->obtenerPorId($id);
    		if (!$vehiculo) {
        		error_log("❌ No se encontró vehículo con ID: $id");
        		echo '<div class="alert alert-danger">❌ Vehículo no encontrado.</div>';
        		return;
    			}

    		require_once __DIR__ . '/modelo/CategoriaModel.php';
    		$categoriaModel = new CategoriaModel($this->conn);
    		$categorias = $categoriaModel->listar();

    		error_log("✅ Vehículo encontrado: " . print_r($vehiculo, true));
    		include __DIR__ . '/vistas/form_edit.php';
			}



    // 🔄 Actualizar tipo existente
    public function update($data) {
        $id          = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $nombre      = isset($data['nombre'])      ? trim($data['nombre'])      : '';
        $descripcion = isset($data['descripcion']) ? trim($data['descripcion']) : '';
        $categoriaId = isset($data['categoria_id']) ? (int) $data['categoria_id'] : 0;

        if ($id <= 0 || $nombre === '' || $categoriaId <= 0) {
            header('Location: index.php?error=' . urlencode('ID, nombre y categoría son obligatorios'));
            exit;
        }

        $usuarioId = $this->getUsuarioId();

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
            $this->modelo->actualizar($id, $nombre, $descripcion, $categoriaId, $usuarioId);
            header('Location: index.php?msg=actualizado');
        } catch (Exception $e) {
            header('Location: index.php?action=edit&id=' . $id . '&error=' . urlencode($e->getMessage()));
        }
        exit;
    }

    // 🗑️ Eliminar (soft delete)
    public function delete($id) {
        $usuarioId = $this->getUsuarioId();
        $this->modelo->eliminar($id, $usuarioId);
        header('Location: index.php?msg=eliminado');
        exit;
    }

    // 🔁 Reactivar eliminado
    public function reactivar() {
        $usuarioId = $this->getUsuarioId();
        $id = isset($_POST['id']) ? (int) $_POST['id'] :
              (isset($_SESSION['reactivar_id']) ? (int) $_SESSION['reactivar_id'] :
              (isset($_GET['id']) ? (int) $_GET['id'] : 0));

        if ($id <= 0) {
            header('Location: index.php?error=' . urlencode('ID inválido para reactivación'));
            exit;
        }

        $this->modelo->reactivar($id, $usuarioId);

        unset($_SESSION['reactivar_id'], $_SESSION['reactivar_nombre'], $_SESSION['reactivar_descripcion'], $_SESSION['reactivar_categoria_id']);
        session_write_close();

        header("Location: index.php?msg=reactivado");
        exit;
    }

    // 👁️ Mostrar prompt de reactivación
    public function reactivar_prompt() {
        $nombre = isset($_SESSION['reactivar_nombre']) ? $_SESSION['reactivar_nombre'] : '';
        include __DIR__ . '/reactivar_prompt.php';
    }

    // 🔐 Obtener ID de usuario actual
    private function getUsuarioId() {
        if (!isset($_SESSION['usuario_id'])) {
            throw new Exception("usuario_id no definido en la sesión.");
        }
        return $_SESSION['usuario_id'];
    }

    // 📄 Renderizar lista de activos (usado por AJAX)
    public function renderActivos() {
        $datos = $this->modelo->obtenerPorEstado(0); // 0 = activos
        include __DIR__ . '/vistas/lista_activos.php';
    }

    // 📄 Renderizar lista de inactivos (usado por AJAX)
    public function renderInactivos() {
        $datos = $this->modelo->obtenerPorEstado(1); // 1 = eliminados
        include __DIR__ . '/vistas/lista_inactivos.php';
    }

    // 🔍 Alias público para obtener vehículo por ID (opcional)
    public function buscarPorId($id) {
        return $this->modelo->obtenerPorId($id);
    }

	public function listarCategorias() {
    require_once __DIR__ . '/modelo/CategoriaModel.php';
    $categoriaModel = new CategoriaModel($this->conn);
    return $categoriaModel->listar(); // ✅ Devuelve array de categorías activas
}


// 👁️ Mostrar detalles del vehículo (usado por AJAX)
public function ver($id) {
    error_log("👁️ ver() llamado con ID: $id");

    if ($id <= 0) {
        echo '<div class="alert alert-danger text-center">❌ ID inválido.</div>';
        return;
    }

    $vehiculo = $this->modelo->obtenerPorId($id);
    if (!$vehiculo) {
        echo '<div class="alert alert-warning text-center">⚠️ Vehículo no encontrado.</div>';
        return;
    }

    include __DIR__ . '/vistas/form_view.php';
}


}