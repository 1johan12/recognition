<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="assets/js/jquery-3.7.1.min.js"></script>
<script src="src/model/recognition.model.js" ></script>
<?php
echo "<h3>Ejecutando script SQL...</h3>";
require_once __DIR__ . "/scripts/createTables.php";
require_once __DIR__ . "/src/importData.php";
?>
<!-- <button onclick="addCategory()">Agregar Categoria</button> -->
<!-- <script>
    
</script> -->