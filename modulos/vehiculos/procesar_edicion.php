<?php
// archivo: /vehiculos/procesar_edicion.php

require_once '../../includes/conexion.php';
require_once '../../includes/funciones.php';
session_start();

// Activar depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = getConnection();

// Validar método y ID
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST['id']) || !is_numeric($_POST['id'])) {
        die("<p style='color: red;'>❌ ID de vehículo no válido.</p>");
    }
    $vehiculo_id = intval($_POST['id']);

    // Validar campos obligatorios
    if (
        empty($_POST['placa']) || empty($_POST['tipo_id']) || empty($_POST['marca_id']) ||
        empty($_POST['modelo']) || empty($_POST['anio']) || empty($_POST['empresa_id']) ||
        empty($_POST['estado_id'])
    ) {
        die("<p style='color: red;'>❌ Todos los campos obligatorios deben estar completos.</p>");
    }

    // Convertir datos
    $placa            = strtoupper(trim($_POST['placa']));
    $tipo_id          = intval($_POST['tipo_id']);
    $marca_id         = intval($_POST['marca_id']);
    $estado_nuevo     = intval($_POST['estado_id']);
    $configuracion_id = 1;
    $modelo           = strtoupper(trim($_POST['modelo']));
    $anio             = intval($_POST['anio']);
    $empresa_id       = intval($_POST['empresa_id']);
    $observaciones    = trim($_POST['observaciones']);

    // Obtener estado anterior
    $sqlEstado = "SELECT estado_id FROM vehiculos WHERE id = ?";
    $stmtEstado = $conn->prepare($sqlEstado);
    if (!$stmtEstado) {
        die("❌ Error al preparar consulta de estado: " . $conn->error);
    }
    $stmtEstado->bind_param("i", $vehiculo_id);
    $stmtEstado->execute();
    $resultEstado = $stmtEstado->get_result();
    $estado_anterior = ($resultEstado->num_rows > 0) ? intval($resultEstado->fetch_assoc()['estado_id']) : null;

    // Actualizar vehículo
    $sql = "UPDATE vehiculos 
        SET placa = ?, tipo_id = ?, marca_id = ?, estado_id = ?, configuracion_id = ?, modelo = ?, anio = ?, empresa_id = ?, observaciones = ?, fecha_modificacion = NOW(), modificado_por = ? 
        WHERE id = ?";

    $usuario_id = isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : 0;
	$ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("❌ Error al preparar actualización: " . $conn->error);
    }
    $stmt->bind_param("siiississii", $placa, $tipo_id, $marca_id, $estado_nuevo, $configuracion_id, $modelo, $anio, $empresa_id, $observaciones, $usuario_id, $vehiculo_id);

    if ($stmt->execute()) {
        echo "<p style='color: green;'>✅ Vehículo actualizado correctamente.</p>";

        // Registrar en vehiculo_historial si cambió el estado
        if ($estado_anterior !== null && $estado_anterior !== $estado_nuevo) {
            $estado_txt_anterior = obtenerNombreEstado($conn, $estado_anterior);
            $estado_txt_nuevo    = obtenerNombreEstado($conn, $estado_nuevo);
            $motivo              = "Modificación desde formulario";
            $ip                  = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
            $userAgent           = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'desconocido';
            $fecha               = date('Y-m-d H:i:s');

            $sqlHist = "INSERT INTO vehiculo_historial 
                (vehiculo_id, estado_anterior, estado_nuevo, motivo, usuario_id, ip_origen, user_agent, fecha)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmtHist = $conn->prepare($sqlHist);
            if ($stmtHist) {
                $stmtHist->bind_param("isssisss", $vehiculo_id, $estado_txt_anterior, $estado_txt_nuevo, $motivo, $usuario_id, $ip, $userAgent, $fecha);
                $stmtHist->execute();
            }
        }

        // Registrar trazabilidad general
        $usuario = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : 'visitante';
        registrarEnHistorial($conn, $usuario, "Editó vehículo ID $vehiculo_id", "vehiculos", $ip);

		
		
		// Eliminar fotos marcadas
    if (!empty($_POST['eliminar_fotos']) && is_array($_POST['eliminar_fotos'])) {
        foreach ($_POST['eliminar_fotos'] as $id_foto) {
            $id_foto = intval($id_foto);

            // Obtener ruta del archivo
            $sqlRuta = "SELECT ruta_archivo FROM vehiculo_fotos WHERE id_foto = ? AND id_vehiculo = ?";
            $stmtRuta = $conn->prepare($sqlRuta);
            $stmtRuta->bind_param("ii", $id_foto, $vehiculo_id);
            $stmtRuta->execute();
            $resRuta = $stmtRuta->get_result();
            if ($resRuta && $resRuta->num_rows === 1) {
                $ruta = $resRuta->fetch_assoc()['ruta_archivo'];
                $archivo = $_SERVER['DOCUMENT_ROOT'] . $ruta;

                // Eliminar archivo físico
                if (file_exists($archivo)) {
                    unlink($archivo);
                }

                // Eliminar registro en BD
                $sqlDel = "DELETE FROM vehiculo_fotos WHERE id_foto = ?";
                $stmtDel = $conn->prepare($sqlDel);
                $stmtDel->bind_param("i", $id_foto);
                $stmtDel->execute();
            }
        }
    }


		
		// Subir nuevas fotos si existen
if (!empty($_FILES['fotos']['tmp_name'][0])) {
    foreach ($_FILES['fotos']['tmp_name'] as $i => $tmp) {
        $error = $_FILES['fotos']['error'][$i];
        $nombre = basename($_FILES['fotos']['name'][$i]);

        if ($error === UPLOAD_ERR_OK) {
            $destino_relativo = '/uploads/vehiculos/' . time() . '_' . $nombre;
            $destino_absoluto = $_SERVER['DOCUMENT_ROOT'] . $destino_relativo;

            // Validar que el directorio exista
            $directorio = dirname($destino_absoluto);
            if (!is_dir($directorio)) {
                mkdir($directorio, 0755, true);
            }

            // Mover archivo
            if (move_uploaded_file($tmp, $destino_absoluto)) {
                $sqlFoto = "INSERT INTO vehiculo_fotos (id_vehiculo, ruta_archivo, creado_por) VALUES (?, ?, ?)";
                $stmtFoto = $conn->prepare($sqlFoto);
                if ($stmtFoto) {
                    $stmtFoto->bind_param("isi", $vehiculo_id, $destino_relativo, $usuario_id);
                    $stmtFoto->execute();
                } else {
                    error_log("❌ Error preparando SQL de foto: " . $conn->error);
                }
            } else {
                error_log("❌ No se pudo mover el archivo: $nombre");
            }
        } else {
            error_log("❌ Error al subir archivo $nombre: código $error");
        }
    }
}

        header("Refresh:1; url=vehiculos.php");
        exit();
    } else {
        echo "<p style='color: red;'>❌ Error al actualizar el vehículo: " . $stmt->error . "</p>";
    }
}

// Función auxiliar compatible con PHP 5.6
function obtenerNombreEstado($conn, $estado_id) {
    $sql = "SELECT nombre FROM estado_vehiculo WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) return 'desconocido';
    $stmt->bind_param("i", $estado_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return ($result->num_rows > 0) ? $result->fetch_assoc()['nombre'] : 'desconocido';
}
?>