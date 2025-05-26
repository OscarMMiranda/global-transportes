<?php
require_once '../../includes/conexion.php';
require_once '../../includes/header_lugares.php';

// Verificar si el ID del lugar está presente
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("<div class='alert alert-danger'>❌ Error: ID de lugar no válido.</div>");
}

$id = intval($_GET['id']);

// Usar una consulta preparada para evitar inyecciones SQL
$stmt = $conn->prepare("SELECT * FROM lugares WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

// Verificar si el lugar existe
if ($result->num_rows === 0) {
    die("<div class='alert alert-danger'>❌ Error: Lugar no encontrado.</div>");
}

$lugar = $result->fetch_assoc();
$stmt->close();
?>

<div class="container mt-4">
    <h2 class="text-center text-warning mb-4">✏️ Editar Lugar</h2>

    <?php include 'form_lugares.php'; ?>

    <a href="lugares.php" class="btn btn-secondary mt-3">⬅️ Volver a la Lista</a>
</div>

<?php require_once '../../includes/footer_lugar.php'; ?>
