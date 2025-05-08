<?php
require_once '../../includes/conexion.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int) $_GET['id'];

    // Eliminación lógica: solo cambiamos el estado
    $query = "UPDATE clientes SET estado = 'Inactivo' WHERE id = $id";

    if (mysqli_query($conn, $query)) {
        header("Location: listar_clientes.php?mensaje=Cliente eliminado correctamente");
        exit;
    } else {
        echo "Error al eliminar: " . mysqli_error($conn);
    }

    mysqli_close($conn);
} else {
    echo "ID no válido.";
}
