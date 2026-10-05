<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../controller/clientcontrol.php';

$activePage = 'clients';

$controller = new ClientController();
$clients = $controller->listClients();

$statusLabelMap = [
    'active' => 'Active',
    'pending' => 'Pending',
    'completed' => 'Completed',
    'done' => 'Completed',
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Clients · SmartWills</title>
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/sidebar.css">
    <link rel="stylesheet" href="../assets/css/topbar.css">
    <link rel="stylesheet" href="../assets/css/components.css">
    <link rel="stylesheet" href="../assets/css/clients.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
<div class="wrapper">
    <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>
    <div class="main-content">
        <?php require_once __DIR__ . '/../layouts/topbar.php'; ?>
        <div class="content">
            <div class="page-header">
                <h1><i class="fas fa-users"></i> Client Management</h1>
                <button class="btn-total" onclick="alert('Total clients: <?= count($clients) ?>')">
                    <i class="fas fa-user-friends"></i>
                    <span class="num"><?= count($clients) ?></span>
                    <span class="label">Total</span>
                </button>
                <button class="btn-primary" id="addClientBtn" style="margin-left:auto;" onclick="window.location.href='add_client.php'"><i class="fas fa-plus"></i> Add Client</button>
            </div>
            <div class="toolbar">
                <div class="search-box"><i class="fas fa-search"></i><input type="text" placeholder="Search by name / phone" id="searchInput"></div>
                <div class="filter-group">
                    <select id="filterRisk"><option value="all">All Risks</option><option value="low">Low</option><option value="moderate">Moderate</option><option value="high">High</option></select>
                    <select id="filterStatus"><option value="all">All Status</option><option value="active">Active</option><option value="done">Completed</option><option value="pending">Pending</option></select>
                </div>
            </div>
            <div class="table-wrapper">
                <div class="table-scroll">
                    <table class="client-table">
                        <thead>
                            <tr>
                                <th>Client</th>
                                <th>Contact</th>
                                <th>Risk</th>
                                <th>Status</th>
                                <th>Updated</th>
                                <th>Risk Assessment</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="clientTableBody">
                            <?php if (empty($clients)): ?>
                                <tr>
                                    <td colspan="7" style="text-align:center;padding:40px;color:#8aa4bc;">No clients found</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($clients as $client): ?>
                                    <?php
                                        $riskValue = strtolower((string)($client['risk'] ?? 'low'));
                                        $riskClass = $riskValue === 'high' ? 'high' : ($riskValue === 'moderate' ? 'moderate' : 'low');
                                        $riskLabel = ucfirst($riskValue);
                                    ?>
                                    <tr data-client-row data-status="<?= htmlspecialchars((string)($client['status'] ?? 'active')) ?>" data-risk="<?= htmlspecialchars($riskValue) ?>">
                                        <td>
                                            <div class="client-name">
                                                <span class="avatar"><?= htmlspecialchars(strtoupper(substr(trim((string)($client['name'] ?? '')), 0, 1))) ?></span>
                                                <?= htmlspecialchars((string)($client['name'] ?? '')) ?>
                                            </div>
                                        </td>
                                        <td><?= htmlspecialchars((string)($client['contact'] ?? '—')) ?></td>
                                        <td><span class="risk-badge <?= htmlspecialchars($riskClass) ?>"><?= htmlspecialchars($riskLabel) ?></span></td>
                                        <td><span class="status-badge <?= htmlspecialchars((string)($client['status'] ?? 'active')) ?>"><?= htmlspecialchars($statusLabelMap[$client['status']] ?? ucfirst((string)($client['status'] ?? 'active'))) ?></span></td>
                                        <td><?= htmlspecialchars((string)($client['updated_at'] ?? $client['created_at'] ?? '—')) ?></td>
                                        <td><button class="btn-sm btn-risk-analyze" data-id="<?= (int)($client['id'] ?? 0) ?>"><i class="fas fa-chart-line"></i> Risk Analyze</button></td>
                                        <td>
                                            <div class="action-cell">
                                                <button class="btn-sm btn-plan" data-id="<?= (int)($client['id'] ?? 0) ?>"><i class="fas fa-route"></i> Plan</button>

                                                <!-- edit client data -->
                                                <a class="btn-sm btn-edit" href="edit_client.php?id=<?= (int)($client['id'] ?? 0) ?>"><i class="fas fa-edit"></i> Edit</a>

                                                <!-- Delete client data -->
                                                <form method="POST" action="delete_client.php" class="delete-form" onsubmit="return confirm('Are you sure you want to delete this client?');">
                                                    <input type="hidden" name="id" value="<?= (int)($client['id'] ?? 0) ?>">
                                                    <button type="submit" class="btn-sm btn-delete"><i class="fas fa-trash-alt"></i> Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <footer>© 2026 smartwillsplanner.com</footer>
        </div>
    </div>
</div>

<script src="../assets/js/global.js"></script>
<script src="../assets/js/clients.js"></script>
</body>
</html>