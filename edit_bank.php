<?php
require_once __DIR__ . '/includes/bootstrap.php';

smartwills_require_login();

$activePage = 'profile';
$pageTitle = 'Edit Bank Information - SmartWills';
$pageStyles = ['profile.css'];
$pageScripts = ['profile.js'];

$userId    = $_SESSION["portal"]["user"]["id"] ?? ($_SESSION['user_id'] ?? null);
$userEmail = $_SESSION['user_email'] ?? 'maminuddenh@gmail.com';
$errorMsg  = '';

// Process Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bankName      = trim($_POST['bank_name'] ?? '');
    $bankAccountNo = trim($_POST['bank_account_no'] ?? '');
    $bankHolder    = trim($_POST['bank_account_holder'] ?? '');
    $bankSwift     = trim($_POST['bank_swift'] ?? '');

    if (isset($db)) {
        $sBankName   = $db->escape($bankName);
        $sAccNo      = $db->escape($bankAccountNo);
        $sHolder     = $db->escape($bankHolder);
        $sSwift      = $db->escape($bankSwift);

        if ($userId) {
            $safeId = (int)$userId;
            $updateSql = "UPDATE users SET bank_name = '{$sBankName}', bank_account_no = '{$sAccNo}', bank_account_holder = '{$sHolder}', bank_swift = '{$sSwift}' WHERE id = {$safeId}";
        } else {
            $safeEmail = $db->escape($userEmail);
            $updateSql = "UPDATE users SET bank_name = '{$sBankName}', bank_account_no = '{$sAccNo}', bank_account_holder = '{$sHolder}', bank_swift = '{$sSwift}' WHERE email = '{$safeEmail}'";
        }

        if ($db->query($updateSql) !== false) {
            header("Location: profile.php?bank_updated=1");
            exit;
        } else {
            $errorMsg = "Failed to update bank details. Please try again.";
        }
    }
}

// Fetch Current Bank Details
$userData = null;
if (isset($db)) {
    if ($userId) {
        $safeId = (int)$userId;
        $result = $db->query("SELECT * FROM users WHERE id = {$safeId} LIMIT 1");
    } else {
        $safeEmail = $db->escape($userEmail);
        $result = $db->query("SELECT * FROM users WHERE email = '{$safeEmail}' LIMIT 1");
    }

    if ($result && $result->num_rows > 0) {
        $userData = $result->rows[0];
    }
}

$bankName      = $userData['bank_name'] ?? '';
$bankAccountNo = $userData['bank_account_no'] ?? '';
$bankHolder    = $userData['bank_account_holder'] ?? '';
$bankSwift     = $userData['bank_swift'] ?? '';

include __DIR__ . '/layouts/header.php';
?>
<div class="wrapper">
    <?php include __DIR__ . '/layouts/sidebar.php'; ?>

    <main class="main-content">
        <?php include __DIR__ . '/layouts/topbar.php'; ?>

        <div class="content">
            <header class="profile-header">
                <div class="greeting">
                    <h1>Manage Bank Account</h1>
                    <p><i class="fas fa-university"></i> Update your banking details</p>
                </div>
            </header>

            <section class="profile-grid">
                <article class="profile-card">
                    <div class="card-header">
                        <i class="fas fa-university"></i>
                        Bank Account Form
                    </div>

                    <?php if ($errorMsg): ?>
                        <div class="alert alert-danger mb-3" style="color: #721c24; background-color: #f8d7da; padding: 10px; border-radius: 6px;">
                            <?php echo htmlspecialchars($errorMsg); ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="edit_bank.php">
                        <div class="info-row">
                            <span class="label">Bank Name</span>
                            <span class="value">
                                <input type="text" name="bank_name" class="form-control" value="<?php echo htmlspecialchars($bankName); ?>" placeholder="e.g., Maybank" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="label">Account Number</span>
                            <span class="value">
                                <input type="text" name="bank_account_no" class="form-control" value="<?php echo htmlspecialchars($bankAccountNo); ?>" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="label">Account Holder</span>
                            <span class="value">
                                <input type="text" name="bank_account_holder" class="form-control" value="<?php echo htmlspecialchars($bankHolder); ?>" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="label">SWIFT / BIC Code</span>
                            <span class="value">
                                <input type="text" name="bank_swift" class="form-control" value="<?php echo htmlspecialchars($bankSwift); ?>" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                            </span>
                        </div>

                        <div class="btn-group" style="margin-top: 20px;">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Bank Details
                            </button>
                            <a href="profile.php" class="btn btn-outline" style="text-decoration: none; display: inline-block; padding: 8px 16px;">
                                Cancel
                            </a>
                        </div>
                    </form>
                </article>
            </section>
        </div>
    </main>
</div>
<?php include __DIR__ . '/layouts/footer.php'; ?>