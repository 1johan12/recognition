<?php
require_once __DIR__ . "/../repository/recognition.repository.php";

class RecognitionService {
    private $repository;

    public function __construct($conn) {
        $this->repository = new RecognitionRepository($conn);
    }

    public function getAllRecognition($page,$perPage,$filterByName,$filterByEventId) {
        return $this->repository->getAll($page,$perPage,$filterByName,$filterByEventId);
    }

    public function createRecognition($data) {
        
        if (empty($data)) {
            return ["success" => false, "message" => "El nombre es requerido"];
        }
        return ["success" => $this->repository->create($data)];
    }

    public function deleteRecognition($id) {
        if (!is_numeric($id)) {
            return ["success" => false, "message" => "ID inválido"];
        }
        return ["success" => $this->repository->delete($id)];
    }
}
?>
