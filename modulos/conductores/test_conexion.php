<?php
$conn = new mysqli('localhost', 'wi010232_ommz', 'Samantha2304', 'wi010232_sistema');

if ($conn->connect_error) {
    die('❌ Falló la conexión: ' . $conn->connect_error);
}

echo '✅ Conexión exitosa';