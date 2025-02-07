<?php
require_once __DIR__ . "/../../config/mysql.connection.php";
// echo json_encode(["categoria repository"]);
// exit;
class CategoryRepository {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $sql = "SELECT * FROM category";
        $result = $this->conn->query($sql);
        $categorias = [];

        while ($row = $result->fetch_assoc()) {
            $categorias[] = $row;
        }
        return $categorias;
    }

    public function create($name) {
        $stmt = $this->conn->prepare("INSERT INTO category (name) VALUES (?)");
        $stmt->bind_param("s", $name);
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM category WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>
