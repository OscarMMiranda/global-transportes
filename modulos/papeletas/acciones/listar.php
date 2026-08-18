<?php
// archivo: /modulos/papeletas/acciones/listar.php

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
$conn = getConnection();

ini_set('display_errors', 0);

$vehiculo  = isset($_POST['vehiculo']) ? trim($_POST['vehiculo']) : '';
$conductor = isset($_POST['conductor']) ? trim($_POST['conductor']) : '';
$estado    = isset($_POST['estado']) ? trim($_POST['estado']) : '';

$sql = "
    SELECT 
        p.id,
        v.placa,
        CONCAT(c.nombres, ' ', c.apellidos) AS conductor,
        p.fecha_infraccion,
        p.monto,
        p.estado_id,
        ep.nombre AS estado_nombre
    FROM papeletas p
    INNER JOIN vehiculos v ON v.id = p.vehiculo_id
    LEFT JOIN conductores c ON c.id = p.conductor_id
    INNER JOIN papeleta_estado ep ON ep.id = p.estado_id
    WHERE p.eliminado = 0
";

if ($vehiculo != '') {
    $vehiculo = mysqli_real_escape_string($conn, $vehiculo);
    $sql .= " AND v.placa LIKE '%$vehiculo%' ";
}

if ($conductor != '') {
    $conductor = mysqli_real_escape_string($conn, $conductor);
    $sql .= " AND CONCAT(c.nombres, ' ', c.apellidos) LIKE '%$conductor%' ";
}

if ($estado != '') {
    $estado = intval($estado);
    $sql .= " AND p.estado_id = $estado ";
}

$sql .= " ORDER BY p.fecha_infraccion DESC ";

$res = mysqli_query($conn, $sql);

if (!$res) {
    echo "<div class='text-danger text-center py-4'>Error SQL: " . mysqli_error($conn) . "</div>";
    exit;
}

$html = "";
$html .= "<table id='tablaPapeletas' class='table table-striped table-hover table-bordered'>";
$html .= "<thead class='table-dark'>";
$html .= "<tr>";
$html .= "<th>ID</th>";
$html .= "<th>Placa</th>";
$html .= "<th>Conductor</th>";
$html .= "<th>Fecha</th>";
$html .= "<th>Monto</th>";
$html .= "<th>Estado</th>";
$html .= "<th>Acciones</th>";
$html .= "</tr>";
$html .= "</thead>";
$html .= "<tbody>";

if (mysqli_num_rows($res) == 0) {

    // Fila vacía con 7 columnas para evitar error DataTables
    $html .= "<tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
             </tr>";

    // Mensaje informativo
    $html .= "<tr><td colspan='7' class='text-center text-muted py-4'>No se encontraron papeletas</td></tr>";

} else {

    while ($row = mysqli_fetch_assoc($res)) {

        $placa      = htmlspecialchars($row['placa'], ENT_QUOTES, 'UTF-8');
        $conductor  = $row['conductor'] ? htmlspecialchars($row['conductor'], ENT_QUOTES, 'UTF-8') : "<span class='text-muted'>Sin conductor</span>";

        // Estado seguro
        $estadoNom = $row['estado_nombre'] 
            ? htmlspecialchars($row['estado_nombre'], ENT_QUOTES, 'UTF-8') 
            : "Sin estado";

        // CSS seguro para estado
        $estadoCss = $row['estado_nombre'] ? $row['estado_nombre'] : "sin-estado";
        $estadoCss = iconv('UTF-8', 'ASCII//TRANSLIT', $estadoCss);
        if ($estadoCss === false) { $estadoCss = "sin-estado"; }
        $estadoCss = strtolower($estadoCss);
        $estadoCss = str_replace(" ", "-", $estadoCss);
        $estadoCss = preg_replace('/[^a-z0-9\-]/', '', $estadoCss);
        $estadoCss = "estado-" . $estadoCss;

        $html .= "<tr>";
        $html .= "<td>" . $row['id'] . "</td>";
        $html .= "<td>$placa</td>";
        $html .= "<td>$conductor</td>";
        $html .= "<td>" . $row['fecha_infraccion'] . "</td>";
        $html .= "<td>S/ " . number_format($row['monto'], 2) . "</td>";
        $html .= "<td><span class='$estadoCss'>$estadoNom</span></td>";

        // Botones de acción
        $html .= "<td>";

        // EDITAR
        $html .= "<button class='btn btn-sm btn-primary btnEditarPapeleta' 
                    data-id='" . $row['id'] . "'>
                    <i class='fa fa-edit'></i>
                  </button> ";

        // PAGO
        $html .= "<button class='btn btn-sm btn-success btnRegistrarPago' 
                    data-id='" . $row['id'] . "'
                    data-estado='" . $row['estado_id'] . "'
                    data-estado-nombre='" . $estadoNom . "'>
                    <i class='fa fa-money-bill'></i>
                  </button> ";

        // DESCUENTO
        $html .= "<button class='btn btn-sm btn-warning btnRegistrarDescuento' 
                    data-id='" . $row['id'] . "' 
                    data-estado='" . $row['estado_id'] . "'
                    data-estado-nombre='" . $estadoNom . "'>
                    <i class='fa fa-percentage'></i>
                  </button> ";

        // HISTORIAL
        $html .= "<button class='btn btn-sm btn-info btnVerHistorial' 
                    data-id='" . $row['id'] . "'>
                    <i class='fa fa-history'></i>
                  </button> ";

        // ARCHIVOS
        $html .= "<button class='btn btn-sm btn-secondary btnVerArchivos' 
                    data-id='" . $row['id'] . "'>
                    <i class='fa fa-file'></i>
                  </button> ";

        // VER PAPELETA
        $html .= "<button class='btn btn-sm btn-dark btnVerPapeleta' 
                    data-id='" . $row['id'] . "'>
                    <i class='fa fa-eye'></i>
                  </button> ";

        $html .= "</td>";

        $html .= "</tr>";
    }
}

$html .= "</tbody>";
$html .= "</table>";

echo $html;
exit;
?>
