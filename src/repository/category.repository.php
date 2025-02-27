<?php
// require_once __DIR__ . "/../../config/mysql.connection.php";
class CategoryRepository {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll($status = 1) {
        $sql = "CALL sp_getCategory(?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $status);
        $stmt->execute();

        $result = $stmt->get_result();
        $category = [];
        while ($row = $result->fetch_assoc()) {
            $category[] = $row;
        }
        return $category;
    }

    public function create($name) {
        $stmt = $this->conn->prepare("INSERT INTO category (name) VALUES (?)");
        $stmt->bind_param("s", $name);
        return $stmt->execute();
    }

    public function update($data) {
        // echo json_encode([$data["name"],$data["status"],$data["id"]]);
        // exit;
        $stmt = $this->conn->prepare("UPDATE category SET name = ?, status = ? WHERE id = ?");
        $stmt->bind_param("sii", $data["name"],$data["status"],$data["id"]);
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("call sp_deleteCategory(?)");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>
