<?php
require_once __DIR__ . '/../includes/bootstrap.php';

class ClientController
{
    private $db;

    public function __construct()
    {
        global $db;
        $this->db = $db;
    }

    public function listClients(): array
    {
        $userId = $_SESSION["portal"]["user"]["id"] ?? ($_SESSION['user_id'] ?? null);

        $sql = "SELECT 
                    client_ID AS id, 
                    client_name AS name, 
                    contact, 
                    client_risk AS risk, 
                    client_status AS status, 
                    time_updated AS updated_at 
                FROM client";

        if ($userId) {
            $safeUserId = (int)$userId;
            $sql .= " WHERE user_id = {$safeUserId}";
        }

        $sql .= " ORDER BY client_ID DESC";

        $result = $this->db->query($sql);
        $clients = [];

        if ($result && isset($result->rows)) {
            foreach ($result->rows as $row) {
                $row['risk'] = strtolower((string)($row['risk'] ?? 'low'));
                $row['status'] = strtolower((string)($row['status'] ?? 'active'));
                $clients[] = $row;
            }
        }

        return $clients;
    }

    public function getClientById(int $id): ?array
    {
        $safeId = (int)$id;
        $sql = "SELECT 
                    client_ID AS id, 
                    client_name AS name, 
                    contact, 
                    client_risk AS risk, 
                    client_status AS status, 
                    time_updated AS updated_at 
                FROM client 
                WHERE client_ID = {$safeId} 
                LIMIT 1";

        $result = $this->db->query($sql);

        if ($result && !empty($result->rows)) {
            $client = $result->rows[0];
            $client['risk'] = strtolower((string)($client['risk'] ?? 'low'));
            $client['status'] = strtolower((string)($client['status'] ?? 'active'));
            return $client;
        }

        return null;
    }

    public function createClient(array $data): array
    {
        $name = trim((string)($data['name'] ?? ''));
        $contact = trim((string)($data['contact'] ?? ''));
        $status = trim((string)($data['status'] ?? 'active'));
        $status = $status === '' ? 'active' : $status;

        $allowedStatusMap = [
            'active' => 'Active',
            'pending' => 'Pending',
            'completed' => 'Completed',
            'done' => 'Completed',
        ];

        $status = $allowedStatusMap[strtolower($status)] ?? 'Active';
        $risk = 'Low';
        $userId = $_SESSION["portal"]["user"]["id"] ?? ($_SESSION['user_id'] ?? 0);

        if ($name === '' || $contact === '') {
            return ['success' => false, 'message' => 'Name and contact are required.'];
        }

        $sName = $this->db->escape($name);
        $sContact = $this->db->escape($contact);
        $sRisk = $this->db->escape($risk);
        $sStatus = $this->db->escape($status);
        $safeUserId = (int)$userId;

        $sql = "INSERT INTO client (client_name, contact, client_risk, client_status, user_id) 
                VALUES ('{$sName}', '{$sContact}', '{$sRisk}', '{$sStatus}', {$safeUserId})";

        if ($this->db->query($sql) === false) {
            return ['success' => false, 'message' => 'Failed to create client in database.'];
        }

        return ['success' => true, 'message' => 'Client created successfully.'];
    }

    public function updateClient(int $id, array $data): array
    {
        $name = trim((string)($data['name'] ?? ''));
        $contact = trim((string)($data['contact'] ?? ''));
        $status = trim((string)($data['status'] ?? 'active'));
        $status = $status === '' ? 'active' : $status;

        $allowedStatusMap = [
            'active' => 'Active',
            'pending' => 'Pending',
            'completed' => 'Completed',
            'done' => 'Completed',
        ];

        $status = $allowedStatusMap[strtolower($status)] ?? 'Active';

        if ($name === '' || $contact === '') {
            return ['success' => false, 'message' => 'Name and contact are required.'];
        }

        $safeId = (int)$id;
        $sName = $this->db->escape($name);
        $sContact = $this->db->escape($contact);
        $sStatus = $this->db->escape($status);

        $sql = "UPDATE client 
                SET client_name = '{$sName}', 
                    contact = '{$sContact}', 
                    client_status = '{$sStatus}', 
                    time_updated = CURRENT_TIMESTAMP 
                WHERE client_ID = {$safeId}";

        if ($this->db->query($sql) === false) {
            return ['success' => false, 'message' => 'Failed to update client in database.'];
        }

        return ['success' => true, 'message' => 'Client updated successfully.'];
    }

    public function deleteClient(int $id): array
    {
        $safeId = (int)$id;
        $sql = "DELETE FROM client WHERE client_ID = {$safeId}";

        if ($this->db->query($sql) === false) {
            return ['success' => false, 'message' => 'Failed to delete client from database.'];
        }

        return ['success' => true, 'message' => 'Client deleted successfully.'];
    }

    public function handleRequest(): void
    {
        header('Content-Type: application/json');

        try {
            $action = $_GET['action'] ?? ($_POST['action'] ?? 'list');

            switch ($action) {
                case 'list':
                    echo json_encode(['success' => true, 'data' => $this->listClients()]);
                    return;

                case 'get':
                    $id = (int)($_GET['id'] ?? 0);
                    $client = $this->getClientById($id);
                    echo json_encode(['success' => $client !== null, 'data' => $client]);
                    return;

                case 'create':
                    echo json_encode($this->createClient($_POST));
                    return;

                case 'update':
                    $id = (int)($_POST['id'] ?? 0);
                    echo json_encode($this->updateClient($id, $_POST));
                    return;

                case 'delete':
                    $id = (int)($_POST['id'] ?? ($_GET['id'] ?? 0));
                    $result = $this->deleteClient($id);

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

$clientController = new ClientController();

if (basename($_SERVER['PHP_SELF']) === 'clientcontrol.php') {
    $clientController->handleRequest();
    exit;
}