<?php
require_once __DIR__ . "/../repository/eventEdition.repository.php";

class EventEditionService {
    private $repository;

    public function __construct($conn) {
        $this->repository = new EventEditionRepository($conn);
    }

    public function getAllEventEdition() {
        return $this->repository->getAll();
    }

    public function fetchEventEditionByTitleId($eventTitleId) {
        return $this->repository->fetchByEventTitleId($eventTitleId);
    }

    public function createEventEdition($name) {
        if (empty($name)) {
            return ["success" => false, "message" => "El nombre es requerido"];
        }
        return ["success" => $this->repository->create($name)];
    }

    public function deleteEventEdition($id) {
        if (!is_numeric($id)) {
            return ["success" => false, "message" => "ID inválido"];
        }
        return ["success" => $this->repository->delete($id)];
    }
}
?>
