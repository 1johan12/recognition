<?php
require_once __DIR__ . "/../service/eventEdition.service.php";
require_once __DIR__ . "/../../config/mysql.connection.php";

header("Content-Type: application/json");
error_reporting(E_ALL);
ini_set('display_errors', 1);

class EventEditionController
{
    private $service;

    public function __construct($conn)
    {
        $this->service = new EventEditionService($conn);
    }

    public function handleRequest()
    {
        $method = $_SERVER["REQUEST_METHOD"];
        $data = json_decode(file_get_contents("php://input"), true);
        $eventTitleId = isset($_GET["event_title_id"]) ? intval($_GET["event_title_id"]) : null;

        switch ($method) {
            case 'GET':
                if (isset($_GET['event_title_id'])) {
                    $this->fetchEventEditionByTitleId($eventTitleId);
                } else {
                    $this->fetchEventEdition();
                }
                break;
            case 'POST':
                $this->addEventEdition($data);
                break;
            case 'DELETE':
                $this->deleteEventEdition($data);
                break;
            default:
                http_response_code(405);
                echo json_encode(["error" => "Método no permitido"]);
                exit;
        }
    }

    private function fetchEventEdition()
    {
        $data = [
            "status" => $_GET['status'] ?? 1,
            "eventId" => $_GET['eventId'] ?? -1,
            "page" => $_GET['page'] ?? -1,
            "perPage" => $_GET['perPage'] ?? -1
        ];
        // echo json_encode([$data,"Hola si paso"]); exit;
        echo json_encode($this->service->getAllEventEdition($data));
        exit;
    }

    private function fetchEventEditionByTitleId($eventTitleId)
    {
        echo json_encode($this->service->fetchEventEditionByTitleId($eventTitleId));
        exit;
    }

    private function addEventEdition($data)
    {
        if (!isset($data["p_event_title_id"]) && !isset($data["p_start_date"]) && !isset($data["p_end_date"])) {
            echo json_encode(["error" => "El campo 'evento,fecha inicio,fecha fin' es obligatorio"]);
            exit;
        }
        echo json_encode($this->service->createEventEdition($data));
        exit;
    }

    private function deleteEventEdition($data)
    {
        if (!isset($data["id"])) {
            echo json_encode(["error" => "El campo 'id' es obligatorio"]);
            exit;
        }
        echo json_encode($this->service->deleteEventEdition($data["id"]));
        exit;
    }
}

$controller = new EventEditionController($conn);
$controller->handleRequest();
