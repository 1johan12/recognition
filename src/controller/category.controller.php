<?php
require_once __DIR__ . "/../service/category.service.php";
header("Content-Type: application/json");
// echo json_encode(["categoria controller"]);
// exit;

$method = $_SERVER["REQUEST_METHOD"];
$service = new CategoryService($conn);
$data = json_decode(file_get_contents(filename: "php://input"),true);
// echo json_encode(["method",$method]);
// exit;
switch ($method) {
    case 'GET':
        fetchCategory();
        break;
    case 'POST':
        // echo json_encode(["Add Cateogyr"]);
        // exit;
        addCategory();
        break;
    case 'DELETE':
        deleteCategory();
        break;
}

function fetchCategory() {
    global $service;
    echo json_encode($service->getAllCategory());
    exit;
}

function addCategory(){
    global $service;
    global $data;
    echo json_encode($service-> createCategory($data["name"] ?? ""));
    exit;
}

function deleteCategory(){
    global $service;
    global $data;
    echo json_encode($service->deleteCategory($data["id"] ?? ""));
    exit;
}

// if ($_SERVER["REQUEST_METHOD"] === "POST") {
//     $data = json_decode(file_get_contents("php://input"), true);
//     echo json_encode($service->createCategoria($data["name"] ?? ""));
//     exit;
// }

// if ($_SERVER["REQUEST_METHOD"] === "DELETE") {
//     $data = json_decode(file_get_contents("php://input"), true);
//     echo json_encode($service->deleteCategoria($data["id"] ?? ""));
//     exit;
// }

http_response_code(405);
echo json_encode(["error" => "Método no permitido"]);
?>
