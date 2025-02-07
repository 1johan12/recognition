<?php
require_once __DIR__ . "/../repository/category.repository.php";

class CategoryService {
    private $repository;

    public function __construct($conn) {
        $this->repository = new CategoryRepository($conn);
    }

    public function getAllCategory() {
        return $this->repository->getAll();
    }

    public function createCategory($name) {
        if (empty($name)) {
            return ["success" => false, "message" => "El nombre es requerido"];
        }
        return ["success" => $this->repository->create($name)];
    }

    public function deleteCategory($id) {
        if (!is_numeric($id)) {
            return ["success" => false, "message" => "ID inválido"];
        }
        return ["success" => $this->repository->delete($id)];
    }
}
?>
