<?php

class CaseController
{
    private mysqli $conn;

    public function __construct()
    {
        $this->conn = new mysqli('localhost', 'amirul', '123', 'smartwillsplanner', 3306);

        if ($this->conn->connect_error) {
            throw new RuntimeException('Database connection failed: ' . $this->conn->connect_error);
        }

        $this->conn->set_charset('utf8mb4');
    }

    public function listCases(): array
    {
        $result = $this->conn->query(
            'SELECT c.case_ID AS id, c.client_id, cl.client_name AS client, c.case_type AS type, '
            . 'c.case_status AS status, c.time_submitted AS submitted, c.time_updated AS updated '
            . 'FROM cases c INNER JOIN client cl ON cl.client_ID = c.client_id ORDER BY c.case_ID DESC'
        );

        $cases = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $row['status'] = $this->statusToSlug($row['status']);
                $cases[] = $row;
            }
        }

        return $cases;
    }

    public function listClients(): array
    {
        $result = $this->conn->query('SELECT client_ID AS id, client_name AS name FROM client ORDER BY client_name');
        $clients = [];

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $clients[] = $row;
            }
        }

        return $clients;
    }

    public function getCaseById(int $id): ?array
    {
        $stmt = $this->conn->prepare(
            'SELECT c.case_ID AS id, c.client_id, cl.client_name AS client, c.case_type AS type, '
            . 'c.case_status AS status, c.time_submitted AS submitted, c.time_updated AS updated '
            . 'FROM cases c INNER JOIN client cl ON cl.client_ID = c.client_id '
            . 'WHERE c.case_ID = ? LIMIT 1'
        );
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $case = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($case) {
            $case['status'] = $this->statusToSlug($case['status']);
        }

        return $case ?: null;
    }

    public function createCase(array $data): array
    {
        $clientId = (int)($data['client_id'] ?? 0);
        $type = (string)($data['case_type'] ?? 'Will');
        $status = $this->statusToDb((string)($data['status'] ?? 'in-progress'));
        $userId = $this->currentUserId();

        if ($clientId <= 0 || $userId <= 0) {
            return ['success' => false, 'message' => 'A valid client and logged-in user are required.'];
        }

        if (!in_array($type, ['Will', 'Trust', 'LPA', 'AMD'], true)) {
            $type = 'Will';
        }

        $stmt = $this->conn->prepare(
            'INSERT INTO cases (case_type, case_status, time_updated, user_id, client_id) '
            . 'VALUES (?, ?, CURRENT_TIMESTAMP, ?, ?)'
        );
        $stmt->bind_param('ssii', $type, $status, $userId, $clientId);

        if (!$stmt->execute()) {
            $message = $stmt->error;
            $stmt->close();
            return ['success' => false, 'message' => 'Failed to create case: ' . $message];
        }

        $id = $this->conn->insert_id;
        $stmt->close();

        return ['success' => true, 'id' => $id];
    }

    public function updateCase(int $id, array $data): array
    {
        $clientId = (int)($data['client_id'] ?? 0);
        $type = (string)($data['case_type'] ?? 'Will');
        $status = $this->statusToDb((string)($data['status'] ?? 'in-progress'));

        if ($id <= 0 || $clientId <= 0) {
            return ['success' => false, 'message' => 'A valid case and client are required.'];  //rejects nonexistant case or client
        }

        if (!in_array($type, ['Will', 'Trust', 'LPA', 'AMD'], true)) {   //defaults to Will if invalid type is provided
            $type = 'Will';
        }

        //prepares SQL command to update the case in the database
        $stmt = $this->conn->prepare(
            'UPDATE cases SET case_type = ?, case_status = ?, client_id = ?, time_updated = CURRENT_TIMESTAMP '
            . 'WHERE case_ID = ?'   
        );
        $stmt->bind_param('ssii', $type, $status, $clientId, $id);

        if (!$stmt->execute()) {
            $message = $stmt->error;
            $stmt->close();
            return ['success' => false, 'message' => 'Failed to update case: ' . $message];
        }

        $stmt->close();
        return ['success' => true];
    }

    public function deleteCase(int $id): array
    {
        if ($id <= 0) {
            return ['success' => false, 'message' => 'Invalid case ID.'];
        }

        $stmt = $this->conn->prepare('DELETE FROM cases WHERE case_ID = ?');
        $stmt->bind_param('i', $id);

        if (!$stmt->execute()) {
            $message = $stmt->error;
            $stmt->close();
            return ['success' => false, 'message' => 'Failed to delete case: ' . $message];
        }

        $stmt->close();
        return ['success' => true];
    }

    private function currentUserId(): int
    {
        if (isset($_SESSION['user_id'])) {
            return (int)$_SESSION['user_id'];
        }

        return (int)($_SESSION['portal']['user']['id'] ?? 0);
    }

    private function statusToDb(string $status): string
    {
        return [
            'in-progress' => 'In Progress',
            'in-review' => 'In Review',
            'completed' => 'Completed',
            'rejected' => 'Rejected',
            'pending' => 'Pending',
        ][strtolower($status)] ?? 'In Progress';
    }

    private function statusToSlug(string $status): string
    {
        return strtolower(str_replace(' ', '-', trim($status)));
    }
}
