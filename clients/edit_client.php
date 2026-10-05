<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../controller/clientcontrol.php';  //calls the controller class in clientcontrol.php

$activePage = 'clients';
$message = '';

$controller = new ClientController();
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0; // reads the client ID from the query parameter
$client = $controller->getClientById($id);

if (!$client) {
    header('Location: clients.php');
    exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {  //updates the client information
    $result = $controller->updateClient($id, [  //calls the updateClient function in clientcontrol.php
        'name' => $_POST['name'] ?? '',
        'contact' => $_POST['contact'] ?? '',
        'status' => $_POST['status'] ?? 'active',  
    ]);

    if ($result['success']) {
        header('Location: clients.php?updated=1');
        exit;   //redirectes on success
    }

    $message = $result['message'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Edit Client · SmartWills</title>
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
                <h1><i class="fas fa-user-edit"></i> Edit Client</h1>
                <a href="clients.php" class="btn-primary" style="margin-left:auto; background:#6c757d;">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </div>

            <?php if ($message): ?>
                <div class="alert alert-error" style="background:#fde8e8; padding:12px 20px; border-radius:10px; color:#b33c3c; margin-bottom:20px;">
                    <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <div style="background: white; border-radius: 20px; border: 1px solid #eef2f8; padding: 24px 30px;">
                <form id="editForm" action="edit_client.php?id=<?= (int)($client['id'] ?? 0) ?>" method="POST">
                    <input type="hidden" name="id" value="<?= (int)($client['id'] ?? 0) ?>">
                    
                    <div class="fsection">
                        <h3><i class="fas fa-info-circle"></i> General Information</h3>
                        <div class="frow">
                            <label>Full Name</label>
                            <input type="text" name="name" value="<?= htmlspecialchars((string)($client['name'] ?? '')) ?>" required>
                        </div>
                        <div class="frow">
                            <label>Contact</label>
                            <input type="text" name="contact" value="<?= htmlspecialchars((string)($client['contact'] ?? '')) ?>" required>
                        </div>
                        <div class="frow">
                            <label>Status</label>
                            <select name="status">
                                <option value="active" <?= (($client['status'] ?? 'active') === 'active') ? 'selected' : '' ?>>Active</option>
                                <option value="completed" <?= (($client['status'] ?? 'active') === 'completed') ? 'selected' : '' ?>>Completed</option>
                                <option value="pending" <?= (($client['status'] ?? 'active') === 'pending') ? 'selected' : '' ?>>Pending</option>
                            </select>
                        </div>
                    </div>

                    <div class="modal-btns" style="justify-content: flex-start; margin-top: 20px;">
                        <button type="submit" class="btn btn-save"><i class="fas fa-save"></i> Save Changes</button>
                        <a href="clients.php" class="btn btn-cancel">Cancel</a>
                    </div>
                </form>
            </div>

            <footer style="margin-top:30px;">© 2026 smartwillsplanner.com</footer>
        </div>
    </div>
</div>

<script src="../assets/js/global.js"></script>
<script src="../assets/js/clients.js"></script>
</body>
</html>