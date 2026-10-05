<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/controller/casecontrol.php';

smartwills_require_login();

$activePage = 'cases';
$pageTitle = 'Edit Case · SmartWills';
$pageStyles = ['cases.css'];
$pageScripts = [];
$message = '';
$caseId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$caseController = new CaseController();
$case = $caseController->getCaseById($caseId);
$clients = $caseController->listClients();

if (!$case) {
    header('Location: cases.php?error=notfound');
    exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    $result = $caseController->updateCase($caseId, $_POST);

    if ($result['success']) {
        header('Location: cases.php?updated=1');
        exit;
    }

    $message = $result['message'];
}

include __DIR__ . '/layouts/header.php';
?>
<div class="wrapper">
    <?php include __DIR__ . '/layouts/sidebar.php'; ?>
    <main class="main-content">
        <?php include __DIR__ . '/layouts/topbar.php'; ?>
        <div class="content">
            <div class="page-header">
                <h1><i class="fas fa-edit"></i> Edit Case #<?php echo $caseId; ?></h1>
                <a href="cases.php" class="btn btn-cancel" style="margin-left:auto; padding:8px 22px; border-radius:30px; background:#eef2f8; color:#2c4a66; text-decoration:none; font-weight:600;">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>

            <?php if ($message): ?>
                <div class="alert alert-error" style="background:#fde8e8; padding:12px 20px; border-radius:10px; color:#b33c3c; margin-bottom:20px;">
                    <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <div class="edit-case-container" style="background:#fff; border-radius:20px; padding:30px; border:1px solid #eef2f8;">
                <form method="POST" action="">
                    <div class="fsection">
                        <h3><i class="fas fa-info-circle"></i> Case Information</h3>
                        <div class="frow">
                            <label>Client Name <span style="color:red;">*</span></label>
                            <select name="client_id" required>   <!-- Dropdown for selecting client, saves ID to client_id -->
                                <?php foreach ($clients as $client): ?>
                                    <option value="<?= (int)$client['id'] ?>" <?= ((int)$client['id'] === (int)$case['client_id']) ? 'selected' : '' ?>><?= htmlspecialchars($client['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="frow">
                            <label>Case Type</label>
                            <select name="case_type">  <!-- Dropdown for selecting case type, saves type to case_type -->
                                <?php
                                $types = ['Will', 'Trust', 'LPA', 'AMD'];
                                foreach ($types as $t) {
                                    $sel = ($t === $case['type']) ? 'selected' : '';
                                    echo "<option value=\"$t\" $sel>$t</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="frow">
                            <label>Status</label>
                            <select name="status">  <!-- Dropdown for selecting status, saves status to status -->
                                <?php
                                $statuses = [
                                    'in-progress' => 'In Progress',
                                    'in-review' => 'In Review',
                                    'completed' => 'Completed',
                                    'rejected' => 'Rejected',
                                    'pending' => 'Pending',
                                ];
                                foreach ($statuses as $val => $label) {
                                    $sel = ($val === $case['status']) ? 'selected' : '';
                                    echo "<option value=\"$val\" $sel>$label</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-btns" style="margin-top:20px;">
                        <a href="cases.php" class="btn btn-cancel">Cancel</a>
                        <button type="submit" class="btn btn-save"><i class="fas fa-save"></i> Update Case</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>
<?php include __DIR__ . '/layouts/footer.php'; ?>