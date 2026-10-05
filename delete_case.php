<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/controller/casecontrol.php';

smartwills_require_login();

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    header('Location: cases.php');
    exit;
}

$controller = new CaseController();
$result = $controller->deleteCase((int)($_POST['id'] ?? 0));

header('Location: cases.php?' . ($result['success'] ? 'deleted=1' : 'delete_error=1'));
exit;
