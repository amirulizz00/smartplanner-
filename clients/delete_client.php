<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../controller/clientcontrol.php';

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    header('Location: clients.php');
    exit;
}

$id = (int)($_POST['id'] ?? 0);

if ($id <= 0) {
    header('Location: clients.php?delete_error=invalid_id');
    exit;
}

$result = $clientController->deleteClient($id);  //calls the deleteClient function in clientcontrol.php

if ($result['success']) {
    header('Location: clients.php?deleted=1');
    exit;
}

header('Location: clients.php?delete_error=1');
exit;
