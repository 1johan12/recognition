<?php
require_once __DIR__ . "/../repository/recognition.repository.php";

class RecognitionService {
    private $repository;

    public function __construct($conn) {
        $this->repository = new RecognitionRepository($conn);
    }

    public function getAllRecognition($page,$perPage,$filterByName,$filterByEventId,$filterByEditionId,$filterByCategoryId) {
        return $this->repository->getAll($page,$perPage,$filterByName,$filterByEventId,$filterByEditionId,$filterByCategoryId);
    }

    public function createRecognition($data) {
        
        if (empty($data)) {
            return ["success" => false, "message" => "Datos no registrados"];
        }
        return ["success" => $this->repository->create($data)];
    }

}
?>
