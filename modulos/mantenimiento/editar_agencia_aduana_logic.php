<?php
	// 1) Mostrar errores para depuración (desactivar en producción)
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	session_start();
	require_once __DIR__ . '/../../includes/conexion.php';

	// 2) Verificar conexión y permisos
	if (!$conn) {
	    die("Error en la conexión: " . mysqli_connect_error());
		}
	if (empty($_SESSION['usuario']) || $_SESSION['rol_nombre'] !== 'admin') {
	    header('Location: ../sistema/login.php');
	    exit;
		}

	// 3) Inicializar variables
	$error    = '';
	
	// 3) Asegúrate de que $registro siempre sea un array
	$registro = [];


	// 0) Cargar listas de geografía
    $stmtDep = $conn->prepare("SELECT id, nombre FROM departamentos ORDER BY nombre");
    $stmtDep->execute();
	$departamentos = $stmtDep->get_result()->fetch_all(MYSQLI_ASSOC);
	$stmtDep->close();

	$stmtProv = $conn->prepare("SELECT id, nombre, departamento_id FROM provincias ORDER BY nombre");
	$stmtProv->execute();
	$provincias = $stmtProv->get_result()->fetch_all(MYSQLI_ASSOC);
	$stmtProv->close();

	$stmtDist = $conn->prepare("SELECT id, nombre, provincia_id FROM distritos ORDER BY nombre");
	$stmtDist->execute();
	$distritos = $stmtDist->get_result()->fetch_all(MYSQLI_ASSOC);
	$stmtDist->close();

	// 4) Cargar datos para edición (GET ?editar=ID)
	if (isset($_GET['editar'])) {
    	$idEdit = (int) $_GET['editar'];
    	$stmt   = $conn->prepare("SELECT * FROM agencias_aduanas WHERE id = ?");
    	 $stmt->bind_param("i", $idEdit);
		//$stmt->bind_param("i", $idEdit);
    	$stmt->execute();
    	$registro = $stmt->get_result()->fetch_assoc() ?: [];
    	$stmt->close();
		}

	// 5) Eliminación lógica (GET ?eliminar=ID)
	if (isset($_GET['eliminar'])) {
    	$idDel = (int) $_GET['eliminar'];
    	$stmt  = $conn->prepare("UPDATE agencias_aduanas SET estado = 0 WHERE id = ?");
    	$stmt->bind_param("i", $idDel);
    	$stmt->execute();
    	$stmt->close();
    	header("Location: editar_agencia_aduana.php?msg=eliminado");
    	exit;
		}

	// 6) Reactivar registro (GET ?reactivar=ID)
	if (isset($_GET['reactivar'])) {
    	$idReact = (int) $_GET['reactivar'];
    	$stmt     = $conn->prepare("UPDATE agencias_aduanas SET estado = 1 WHERE id = ?");
    	$stmt->bind_param("i", $idReact);
    	$stmt->execute();
    	$stmt->close();
    	header("Location: editar_agencia_aduana.php?msg=reactivado");
    	exit;
		}
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$id		          = trim($_POST['id']);
		$nombre           = trim($_POST['nombre']);
        $ruc              = trim($_POST['ruc']);
        $direccion        = trim($_POST['direccion']);
        $distrito_id      = trim($_POST['distrito_id']);
        $provincia_id     = trim($_POST['provincia_id']);
        $departamento_id  = trim($_POST['departamento_id']);
        $telefono         = trim($_POST['telefono']);
        $correo_general   = trim($_POST['correo_general']);
        $contacto         = trim($_POST['contacto']);

		// Validar campos obligatorios
    	if ($nombre === '' || $ruc === '') {
        	$error = 'Nombre y RUC son obligatorios.';
    		} 
		else {
        	// 7a) Si estamos editando un registro activo existente
        	if ($id > 0) {
            	$upd = $conn->prepare("
                	UPDATE agencias_aduanas 
                	SET nombre = ?, ruc = ?, direccion = ?, telefono = ?, correo_general = ?, contacto = ?
                	WHERE id = ?
            		");
            	$upd->bind_param(
                	"ssssssi",
                	$nombre, $ruc, $direccion,
                	$telefono, $correo, $contacto,
                	$id
            		);
            	$upd->execute();
            	$upd->close();
            	header("Location: editar_agencia_aduana.php?msg=actualizado");
            	exit;
        		}
			}
			// 7b) Nuevo registro o reactivación (si RUC existe en estado=0)
        	$chk = $conn->prepare("SELECT id FROM agencias_aduanas WHERE ruc = ? AND estado = 0");
        	$chk->bind_param("s", $ruc);
        	$chk->execute();
        	$res = $chk->get_result();

			if ($res->num_rows > 0) {
            	// Reactivar
            	$row         = $res->fetch_assoc();
            	$idReact     = $row['id'];
            	$react       = $conn->prepare("
            	    UPDATE agencias_aduanas
                	SET estado = 1, nombre = ?, direccion = ?, telefono = ?, correo_general = ?, contacto = ?
                	WHERE id = ?
            		");
            	$react->bind_param(
                	"sssssi",
                	$nombre, $direccion,
                	$telefono, $correo, $contacto,
                	$idReact
            		);
            	$react->execute();
            	$react->close();
            	header("Location: editar_agencia_aduana.php?msg=reactivado");
            	exit;
        		}
			else {
            	// Insertar nuevo
            	$ins = $conn->prepare("
            	    INSERT INTO agencias_aduanas
            	    (nombre, ruc, direccion, telefono, correo_general, contacto)
            	    VALUES (?, ?, ?, ?, ?, ?)
            		");
            	$ins->bind_param(
            	    "ssssss",
            	    $nombre, $ruc, $direccion,
            	    $telefono, $correo, $contacto
            		);
            	if (!$ins->execute()) {
                	die("Error al insertar: " . $ins->error);
            		}
            	$ins->close();
            	header("Location: editar_agencia_aduana.php?msg=agregado");
            	exit;
        		}
		}
		
	$stmtList = $conn->prepare("
    	SELECT 
      	id,
      	nombre,
      	ruc,
      	direccion,
      	distrito_id,
      	provincia_id,
      	departamento_id,
      	estado,
      	fecha_creacion
    	FROM agencias_aduanas
    	ORDER BY nombre
		");

	
	$stmtList->execute();
	$agencias = $stmtList->get_result()->fetch_all(MYSQLI_ASSOC);
	$stmtList->close();

	// 9) Cerrar conexión
	$conn->close();
?>