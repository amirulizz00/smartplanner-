<?php
require_once __DIR__ . '/../includes/bootstrap.php';

class CaseController
{
    private $db;

    public function __construct()
    {
        global $db;
        $this->db = $db;
    }

    public function listCases(?string $searchTerm = null): array
    {
        $userId = $_SESSION["portal"]["user"]["id"] ?? ($_SESSION['user_id'] ?? null);

        $sql = "SELECT 
                    c.case_ID AS id, 
                    c.case_type AS type, 
                    c.case_status AS status, 
                    c.time_submitted AS submitted_at, 
                    c.time_updated AS updated_at,
                    cl.client_name AS client_name
                FROM cases c
                LEFT JOIN client cl ON c.client_id = cl.client_ID";

        $conditions = [];

        if ($userId) {
            $safeUserId = (int)$userId;
            $conditions[] = "c.user_id = {$safeUserId}";
        }

        // Global search filtering
        if (!empty($searchTerm)) {
            $escaped = $this->db->escape(trim($searchTerm));
            $conditions[] = "(cl.client_name LIKE '%{$escaped}%' 
                             OR c.case_type LIKE '%{$escaped}%' 
                             OR c.case_status LIKE '%{$escaped}%' 
                             OR c.case_ID LIKE '%{$escaped}%')";
        }

        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        $sql .= " ORDER BY c.case_ID DESC";

        $result = $this->db->query($sql);
        $cases = [];

        if ($result && isset($result->rows)) {
            foreach ($result->rows as $row) {
                $row['type'] = (string)($row['type'] ?? '');
                $row['status'] = (string)($row['status'] ?? 'Pending');
                $cases[] = $row;
            }
        }

        return $cases;
    }

    public function getCaseById(int $id): ?array
    {
        $safeId = (int)$id;
        $sql = "SELECT 
                    c.case_ID AS id, 
                    c.case_type AS type, 
                    c.case_status AS status, 
                    c.time_submitted AS submitted_at, 
                    c.time_updated AS updated_at,
                    c.client_id,
                    cl.client_name AS client_name
                FROM cases c
                LEFT JOIN client cl ON c.client_id = cl.client_ID
                WHERE c.case_ID = {$safeId} 
                LIMIT 1";

        $result = $this->db->query($sql);

        if ($result && !empty($result->rows)) {
            return $result->rows[0];
        }

        return null;
    }

    public function createCase(array $data): array
    {
        $caseType   = trim((string)($data['case_type'] ?? 'Will'));
        $caseStatus = trim((string)($data['case_status'] ?? 'Pending'));
        $clientId   = (int)($data['client_id'] ?? 0);
        $userId     = $_SESSION["portal"]["user"]["id"] ?? ($_SESSION['user_id'] ?? 0);

        if ($clientId <= 0) {
            return ['success' => false, 'message' => 'Valid Client ID is required.'];
        }

        $sType   = $this->db->escape($caseType);
        $sStatus = $this->db->escape($caseStatus);
        $safeUserId   = (int)$userId;
        $safeClientId = (int)$clientId;

        $sql = "INSERT INTO cases (case_type, case_status, time_submitted, time_updated, user_id, client_id) 
                VALUES ('{$sType}', '{$sStatus}', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, {$safeUserId}, {$safeClientId})";

        if ($this->db->query($sql) === false) {
            return ['success' => false, 'message' => 'Failed to create case in database.'];
        }

        return ['success' => true, 'message' => 'Case created successfully.'];
    }

    public function updateCase(int $id, array $data): array
    {
        $caseType   = trim((string)($data['case_type'] ?? 'Will'));
        $caseStatus = trim((string)($data['case_status'] ?? 'Pending'));

        $safeId  = (int)$id;
        $sType   = $this->db->escape($caseType);
        $sStatus = $this->db->escape($caseStatus);

        $sql = "UPDATE cases 
                SET case_type = '{$sType}', 
                    case_status = '{$sStatus}', 
                    time_updated = CURRENT_TIMESTAMP 
                WHERE case_ID = {$safeId}";

        if ($this->db->query($sql) === false) {
            return ['success' => false, 'message' => 'Failed to update case in database.'];
        }

        return ['success' => true, 'message' => 'Case updated successfully.'];
    }

    public function deleteCase(int $id): array
    {
        $safeId = (int)$id;
        $sql = "DELETE FROM cases WHERE case_ID = {$safeId}";

        if ($this->db->query($sql) === false) {
            return ['success' => false, 'message' => 'Failed to delete case from database.'];
        }

        return ['success' => true, 'message' => 'Case deleted successfully.'];
    }

    public function handleRequest(): void
    {
        header('Content-Type: application/json');

        try {
            $action = $_GET['action'] ?? ($_POST['action'] ?? 'list');

            switch ($action) {
                case 'list':
                    $search = $_GET['search'] ?? null;
                    echo json_encode(['success' => true, 'data' => $this->listCases($search)]);
                    return;

                case 'get':
                    $id = (int)($_GET['id'] ?? 0);
                    $case = $this->getCaseById($id);
                    echo json_encode(['success' => $case !== null, 'data' => $case]);
                    return;

                case 'create':
                    echo json_encode($this->createCase($_POST));
                    return;

                case 'update':
                    $id = (int)($_POST['id'] ?? 0);
                    echo json_encode($this->updateCase($id, $_POST));
                    return;

                case 'delete':
                    $id = (int)($_POST['id'] ?? ($_GET['id'] ?? 0));
                    $result = $this->deleteCase($id);

                    if (!empty($_POST['return_to'])) {
                        header('Location: ' . $_POST['return_to']);
                        exit;
                    }

                    echo json_encode($result);
                    return;

                default:
                    echo json_encode(['success' => false, 'message' => 'Unknown action.']);
                    return;
            }
        } catch (Throwable $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}

$caseController = new CaseController();

if (basename($_SERVER['PHP_SELF']) === 'casecontrol.php') {
    $caseController->handleRequest();
    exit;
}