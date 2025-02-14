<?php
require_once __DIR__ . "/../service/eventTitle.service.php";
require_once __DIR__ . "/../../config/mysql.connection.php";

header("Content-Type: application/json");
error_reporting(E_ALL);
ini_set('display_errors', 1);

class EventTitleController {
    private $service;

    public function __construct($conn) {
        $this->service = new EventTitleService($conn);
    }

    public function handleRequest() {
        $method = $_SERVER["REQUEST_METHOD"];
        $data = json_decode(file_get_contents("php://input"), true);

        switch ($method) {
            case 'GET':
                $this->fetchEventTitle();
                break;
            case 'POST':
                $this->addEventTitle($data);
                break;
            case 'DELETE':
                $this->deleteEventTitle($data);
                break;
            default:
                http_response_code(405);
                echo json_encode(["error" => "Método no permitido"]);
                exit;
        }
    }

    private function fetchEventTitle() {
        echo json_encode($this->service->getAllEventTitle());
        exit;
    }

    private function addEventTitle($data) {
        if (!isset($data["title"])) {
            echo json_encode(["error" => "El campo 'title' es obligatorio"]);
            exit;
        }
        echo json_encode($this->service->createEventTitle($data["title"]));
        exit;
    }

    private function deleteEventTitle($data) {
        if (!isset($data["id"])) {
            echo json_encode(["error" => "El campo 'id' es obligatorio"]);
            exit;
        }
        echo json_encode($this->service->deleteEventTitle($data["id"]));
        exit;
    }
}

$controller = new EventTitleController($conn);
$controller->handleRequest();
?>
