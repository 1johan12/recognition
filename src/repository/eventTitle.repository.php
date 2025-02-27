<?php
class EventTitleRepository {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $sql = "SELECT * FROM event_title where status = 1";
        $result = $this->conn->query($sql);
        $categorias = [];

        while ($row = $result->fetch_assoc()) {
            $categorias[] = $row;
        }
        return $categorias;
    }

    public function create($data) {
        // $name = $data["name"];
        // echo json_encode("Create ". $name);exit;
        $stmt = $this->conn->prepare("INSERT INTO event_title (title,created_at) VALUES (?,now())");
        $stmt->bind_param("s", $data["name"]);
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM event_title WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>
