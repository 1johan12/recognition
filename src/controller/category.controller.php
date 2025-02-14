<?php
require_once __DIR__ . "/../service/category.service.php";
require_once __DIR__ . "/../../config/mysql.connection.php";

header("Content-Type: application/json");
error_reporting(E_ALL);
ini_set('display_errors', 1);

class CategoryController {
    private $service;

    public function __construct($conn) {
        $this->service = new CategoryService($conn);
    }

    public function handleRequest() {
        $method = $_SERVER["REQUEST_METHOD"];
        $data = json_decode(file_get_contents("php://input"), true);

        switch ($method) {
            case 'GET':
                $this->fetchCategory();
                break;
            case 'POST':
                $this->addCategory($data);
                break;
            case 'DELETE':
                $this->deleteCategory($data);
                break;
            default:
                http_response_code(405);
                echo json_encode(["error" => "Método no permitido"]);
                exit;
        }
    }

    private function fetchCategory() {
        echo json_encode($this->service->getAllCategory());
        exit;
    }

    private function addCategory($data) {
        if (!isset($data["title"])) {
            echo json_encode(["error" => "El campo 'title' es obligatorio"]);
            exit;
        }
        echo json_encode($this->service->createCategory($data["title"]));
        exit;
    }

    private function deleteCategory($data) {
        if (!isset($data["id"])) {
            echo json_encode(["error" => "El campo 'id' es obligatorio"]);
            exit;
        }
        echo json_encode($this->service->deleteCategory($data["id"]));
        exit;
    }
}

$controller = new CategoryController($conn);
$controller->handleRequest();
?>
