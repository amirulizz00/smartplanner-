<?php

class ClientController
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

    public function listClients(): array
    {
        $result = $this->conn->query(
            'SELECT client_ID AS id, client_name AS name, contact, client_risk AS risk, client_status AS status, time_updated AS updated_at FROM client ORDER BY client_ID DESC'
        );

        $clients = [];

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $row['risk'] = strtolower((string)($row['risk'] ?? 'low'));
                $row['status'] = strtolower((string)($row['status'] ?? 'active'));
                $clients[] = $row;
            }
        }

        return $clients;
    }

    public function getClientById(int $id): ?array
    {
        $stmt = $this->conn->prepare('SELECT client_ID AS id, client_name AS name, contact, client_risk AS risk, client_status AS status, time_updated AS updated_at FROM client WHERE client_ID = ? LIMIT 1');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $client = $result->fetch_assoc();
        $stmt->close();

        if ($client) {
            $client['risk'] = strtolower((string)($client['risk'] ?? 'low'));
            $client['status'] = strtolower((string)($client['status'] ?? 'active'));
        }

        return $client ?: null;
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
        $userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;

        if ($name === '' || $contact === '') {
            return ['success' => false, 'message' => 'Name and contact are required.'];
        }

        $stmt = $this->conn->prepare('INSERT INTO client (client_name, contact, client_risk, client_status, user_id) VALUES (?, ?, ?, ?, ?)');
        $stmt->bind_param('ssssi', $name, $contact, $risk, $status, $userId);

        if (!$stmt->execute()) {
            $stmt->close();
            return ['success' => false, 'message' => 'Failed to create client: ' . $stmt->error];
        }

        $clientId = $this->conn->insert_id;
        $stmt->close();

        return ['success' => true, 'message' => 'Client created successfully.', 'id' => $clientId];
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

        $stmt = $this->conn->prepare('UPDATE client SET client_name = ?, contact = ?, client_status = ?, time_updated = CURRENT_TIMESTAMP WHERE client_ID = ?');
        $stmt->bind_param('sssi', $name, $contact, $status, $id);

        if (!$stmt->execute()) {
            $stmt->close();
            return ['success' => false, 'message' => 'Failed to update client: ' . $stmt->error];
        }

        $stmt->close();

        return ['success' => true, 'message' => 'Client updated successfully.'];
    }

    public function deleteClient(int $id): array
    {
        $stmt = $this->conn->prepare('DELETE FROM client WHERE client_ID = ?');
        $stmt->bind_param('i', $id);

        if (!$stmt->execute()) {
            $stmt->close();
            return ['success' => false, 'message' => 'Failed to delete client: ' . $stmt->error];
        }

        $stmt->close();

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
