<?php
class EventEditionRepository
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAll($data)
    {
        $sql = "call sp_getEventEdition(?,?,?,?);";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iiii", $data["status"], $data["eventId"],$data["page"],$data["perPage"]);
        $stmt->execute();

        $result = $stmt->get_result();
        $eventEdition = [];
        // echo json_encode($data);exit;
        while ($row = $result->fetch_assoc()) {
            $eventEdition[] = $row;
        }

        $stmt->next_result();
        $totalQuery = $stmt->get_result();
        $total = $totalQuery->fetch_assoc()['total'];

        $stmt->close();

        return [
            'eventEdition' => $eventEdition,
            'total' => $total,
        ];
    }

    public function fetchByEventTitleId($eventTitleId)
    {

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


    public function create($data)
    {
        $query = "CALL sp_insertEventEdition(?,?,?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('iss', $data["p_event_title_id"],$data["p_start_date"],$data["p_end_date"]);
        return $stmt->execute();
    }
    // call sp_insertEventEdition(3, '2024-11-11', '2024-12-11');
    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM event_edition WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>