<?php
// archivo: /includes/db/prep.php

function prep($conn, $sql) {
    if (!$conn || !($conn instanceof mysqli)) {
        throw new Exception("Conexión inválida en prep(): " . $sql);
    }

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        throw new Exception("Error en prepare(): (" . $conn->errno . ") " . $conn->error . "\nSQL: " . $sql);
    }

    return $stmt;
}
