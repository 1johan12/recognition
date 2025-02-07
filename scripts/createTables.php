<?php
$conn = require_once __DIR__ . '/../config/mysql.connection.php';

$tables = ['event_title', 'event_edition', 'recognition', 'category'];
$missingTables = [];

foreach ($tables as $table) {
    $query = "SHOW TABLES LIKE '$table'";
    $result = $conn->query($query);
    if ($result->num_rows == 0) {
        $missingTables[] = $table;
    }
}

if (!empty($missingTables)) {
    $sql_file = __DIR__ . "/script.sql";

    if (file_exists($sql_file)) {
        $sql_script = file_get_contents($sql_file);
        
        if ($conn->multi_query($sql_script)) {
            echo "Las siguientes tablas fueron creadas: " . implode(", ", $missingTables);
        } else {
            echo "Error al ejecutar el script: " . $conn->error;
        }
    } else {
        echo "El archivo SQL no existe.";
    }
} else {
    echo "Todas las tablas ya existen. No se ejecutó el script.";
}

$conn->close();
?>
