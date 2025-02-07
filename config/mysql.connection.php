<?php
$env_path = __DIR__ . '/../.env';
if (file_exists($env_path)) {
    $env = parse_ini_file($env_path);
} else {
    die("Error: El archivo .env no existe.");
}


$conn = new mysqli(
    $env['DB_HOST'],
    $env['DB_USER'],
    $env['DB_PASS'],
    $env['DB_NAME']
);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

return $conn;
?>
