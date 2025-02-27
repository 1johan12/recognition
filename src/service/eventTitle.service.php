<?php
require_once __DIR__ . "/../repository/eventTitle.repository.php";

class EventTitleService {
    private $repository;

    public function __construct($conn) {
        $this->repository = new EventTitleRepository($conn);
    }

    public function getAllEventTitle() {
        return $this->repository->getAll();
    }

    public function createEventTitle($data) {
        if (empty($data)) {
            return ["success" => false, "message" => "Data is required"];
        }
        return ["success" => $this->repository->create($data)];
    }

    public function deleteEventTitle($id) {
        if (!is_numeric($id)) {
            return ["success" => false, "message" => "ID inválido"];
        }
        return ["success" => $this->repository->delete($id)];
    }
}
?>
