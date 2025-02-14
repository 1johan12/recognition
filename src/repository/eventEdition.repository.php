<?php
class EventEditionRepository {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $sql = "SELECT * FROM event_edition where status = 1";
        $result = $this->conn->query($sql);
        $categorias = [];

        while ($row = $result->fetch_assoc()) {
            $categorias[] = $row;
        }
        return $categorias;
    }

    public function fetchByEventTitleId($eventTitleId) {
        
        $sql = "SELECT * FROM event_edition WHERE status = 1 AND event_title_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $eventTitleId);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $editions = [];
        while ($row = $result->fetch_assoc()) {
            $editions[] = $row;
        }
        return $editions;
    }
    

    public function create($name) {
        $stmt = $this->conn->prepare("INSERT INTO event_edition (name) VALUES (?)");
        $stmt->bind_param("s", $name);
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM event_edition WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>
