<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../controller/clientcontrol.php'; //calls the controller class in clientcontrol.php

$activePage = 'clients';
$message = '';

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    $controller = new ClientController();
    $name = trim((string)($_POST['client_name'] ?? ''));
    $contact = trim((string)($_POST['contact'] ?? ''));
    $status = trim((string)($_POST['status'] ?? 'active'));
    $userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;

    if ($name === '' || $contact === '') {
        $message = 'Please fill in all required fields.';
    } else {
        $result = $controller->createClient([     //calls the createClient function in clientcontrol.php
            'name' => $name,
            'contact' => $contact,
            'status' => $status,
        ]);

        if ($result['success']) {
            header('Location: clients.php?added=1');
            exit;
        }

        $message = $result['message'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Add Client · SmartWills</title>
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/sidebar.css">
    <link rel="stylesheet" href="../assets/css/topbar.css">
    <link rel="stylesheet" href="../assets/css/components.css">
    <link rel="stylesheet" href="../assets/css/clients.css">
    <link rel="stylesheet" href="../assets/css/add_client.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
<div class="wrapper">
    <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>
    <div class="main-content">
        <?php require_once __DIR__ . '/../layouts/topbar.php'; ?>
        <div class="content">
            <div class="page-header">
                <h1><i class="fas fa-user-plus"></i> Add New Client</h1>
                <a href="clients.php" class="btn btn-cancel" style="margin-left:auto; padding:8px 22px; border-radius:30px; background:#eef2f8; color:#2c4a66; text-decoration:none; font-weight:600;">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>

            <?php if ($message): ?>
                <div class="alert alert-error" style="background:#fde8e8; padding:12px 20px; border-radius:10px; color:#b33c3c; margin-bottom:20px;">
                    <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <div class="add-client-container">
                <form method="POST" action="">
                    <div class="fsection">
                        <h3><i class="fas fa-info-circle"></i> Client Information</h3>
                        <div class="frow">
                            <label>Client Name <span style="color:red;">*</span></label>
                            <input type="text" name="client_name" placeholder="Enter client name" required>
                        </div>
                        <div class="frow">
                            <label>Contact <span style="color:red;">*</span></label>
                            <input type="text" name="contact" placeholder="Enter contact info (phone/email)" required>
                        </div>
                        <div class="frow">
                            <label>Status</label>
                            <select name="status">
                                <option value="active">Active</option>
                                <option value="pending">Pending</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                    </div>

                    <div class="modal-btns" style="margin-top:20px;">
                        <a href="clients.php" class="btn btn-cancel">Cancel</a>
                        <button type="submit" class="btn btn-save"><i class="fas fa-save"></i> Save Client</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="../assets/js/global.js"></script>
<script src="../assets/js/add_client.js"></script>
</body>
</html>