<?php
require_once __DIR__ . "/../service/recognition.service.php";
require_once __DIR__ . "/../../config/mysql.connection.php";

header("Content-Type: application/json");
error_reporting(E_ALL);
ini_set('display_errors', 1);

class RecognitionController
{
    private $service;

    public function __construct($conn)
    {
        $this->service = new RecognitionService($conn);
    }

    public function handleRequest()
    {
        $method = $_SERVER["REQUEST_METHOD"];
        $data = json_decode(file_get_contents("php://input"), true);

        switch ($method) {
            case 'GET':
                $this->fetchRecognition();
                break;
            case 'POST':
                $this->addRecognition($data);
                break;
            default:
                http_response_code(405);
                echo json_encode(["error" => "Método no permitido"]);
                exit;
        }
    }

    private function fetchRecognition()
    {
        $page = $_GET['page'];
        $perPage = $_GET['perPage'];
        $filterByName = $_GET['filterByName'];
        $filterByEventId = $_GET['filterByEventId'];
        $filterByEditionId = $_GET['filterByEditionId'];
        $filterByCategoryId = $_GET['filterByCategoryId'];
        echo json_encode($this->service->getAllRecognition($page, $perPage, $filterByName, $filterByEventId, $filterByEditionId, $filterByCategoryId));
        exit;
    }

    private function addRecognition($data)
    {

        if (!isset($data["recognitions"])) {
            echo json_encode(["error" => "Datos no recibidos correctamente"]);
            http_response_code(400);
            exit;
        }

        $recognitions = $data["recognitions"];
        $result = $this->service->createRecognition($recognitions);
        echo json_encode(["success" => $result]);
    }
}

$controller = new RecognitionController($conn);
$controller->handleRequest();
