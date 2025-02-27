<?php
require_once __DIR__ . "/../repository/category.repository.php";

class CategoryService {
    private $repository;

    public function __construct($conn) {
        $this->repository = new CategoryRepository($conn);
    }

    public function getAllCategory($status) {
        return $this->repository->getAll($status);
    }

    public function createCategory($name) {
        if (empty($name)) {
            return ["success" => false, "message" => "El nombre es requerido"];
        }
        return ["success" => $this->repository->create($name)];
    }

    public function updateCategory($data) {
        if (empty($data)) {
            return ["success" => false, "message" => "ID inválido"];
        }
        return ["success" => $this->repository->update($data)];
    }
    public function deleteCategory($id) {
        if (!is_numeric($id)) {
            return ["success" => false, "message" => "ID inválido"];
        }
        return ["success" => $this->repository->delete($id)];
    }
}
?>
