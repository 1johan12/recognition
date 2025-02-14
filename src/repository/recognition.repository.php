<?php
class RecognitionRepository
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAll($page = 1, $perPage = 10,$filterByName = null,$filterByEventId = null){
        // echo json_encode(["Pagina : " . $page . " | Por Pagina : " . $perPage . " |Name :" . $filterByName .  " |EventId " . $filterByEventId]);
        // exit;
        $sql = "CALL sp_getRecognition(?, ?, ? )";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iis", $page, $perPage,$filterByName);
        $stmt->execute();

        $result = $stmt->get_result();
        $categorias = [];

        while ($row = $result->fetch_assoc()) {
            $categorias[] = $row;
        }

        $stmt->next_result();
        $totalQuery = $stmt->get_result();
        $total = $totalQuery->fetch_assoc()['total'];

        $stmt->close();

        return [
            'recognition' => $categorias,
            'total' => $total,
        ];
    }

    public function fetchByEventTitleId($eventTitleId){

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


    // public function create($name) {
    //     $stmt = $this->conn->prepare("INSERT INTO recognition (fullname,mail,access_code,created_at,participant_type,event_edition_id,category_id) VALUES(?,?,?,now(),?,?,?);");
    //     $stmt->bind_param("s", $name);
    //     return $stmt->execute();
    // }
    public function create($data){
        $stmt = $this->conn->prepare("INSERT INTO recognition (fullname,mail,access_code,created_at,participant_type,event_edition_id,category_id) VALUES(?,?,?,now(),?,?,?);");
        $stmt->bind_param(
            "ssssii",
            $data["fullname"],
            $data["mail"],
            $data["access_code"],
            $data["participant_type"],
            $data["event_edition_id"],
            $data["category_id"]
        );
        $stmt->execute();
        return true;
    }


    public function delete($id){
        $stmt = $this->conn->prepare("DELETE FROM event_edition WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
