<?php
$basePath = 'http://localhost:8090/reconocimiento/';

$GLOBALS['basePath'] = "http://localhost:8090/reconocimiento/";
?>
<!-- <script src="https://cdn.tailwindcss.com"></script> -->

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="<?= $basePath ?>assets/js/jquery-3.7.1.min.js"></script>
<script src="<?= $basePath ?>src/model/recognition.model.js"></script>
<script src="<?= $basePath ?>assets/fontawesome-6.7.2/js/all.js"></script>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventos</title>
</head>

<body>
    <link rel="stylesheet" href="assets/css/global.css">
    <div class="ue_container_web">
        <?php include_once __DIR__ . '/src/components/verticalMenu.php' ?>
        <div class="body">
            <?php require_once __DIR__ . "/src/index.php"; ?>
        </div>
    </div>
</body>

</html>