<?php
class RecognitionRepository
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAll($page = 1, $perPage = 10, $filterByName = null, $filterByEventId = -1, $filterByEditionId = -1, $filterByCategoryId = -1)
    {
        $sql = "CALL sp_getRecognition(?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iisiii", $page, $perPage, $filterByName, $filterByEventId, $filterByEditionId, $filterByCategoryId);
        $stmt->execute();

        $result = $stmt->get_result();
        $recognition = [];

        while ($row = $result->fetch_assoc()) {
            $recognition[] = $row;
        }

        $stmt->next_result();
        $totalQuery = $stmt->get_result();
        $total = $totalQuery->fetch_assoc()['total'];

        $stmt->close();

        return [
            'recognition' => $recognition,
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

        $jsonData = json_encode($data);

        $query = "CALL sp_insertRecognition(?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('s', $jsonData);

        if ($stmt->execute()) {
            return "Datos insertados correctamente.";
        } else {
            return "Error al insertar datos: " . $stmt->error;
        }
    }


    // public function delete($id)
    // {
    //     $stmt = $this->conn->prepare("DELETE FROM event_edition WHERE id = ?");
    //     $stmt->bind_param("i", $id);
    //     return $stmt->execute();
    // }
}
