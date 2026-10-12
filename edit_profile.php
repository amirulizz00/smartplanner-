<?php
require_once __DIR__ . '/includes/bootstrap.php';

smartwills_require_login();

$activePage = 'profile';
$pageTitle = 'Edit Profile - SmartWills';
$pageStyles = ['profile.css'];
$pageScripts = ['profile.js'];

$userId    = $_SESSION["portal"]["user"]["id"] ?? ($_SESSION['user_id'] ?? null);
$userEmail = $_SESSION['user_email'] ?? 'maminuddenh@gmail.com';
$errorMsg  = '';

// Process Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newName  = trim($_POST['name'] ?? '');
    $newPhone = trim($_POST['phone'] ?? '');

    if (isset($db)) {
        $safeName  = $db->escape($newName);
        $safePhone = $db->escape($newPhone);

        if ($userId) {
            $safeId = (int)$userId;
            $updateSql = "UPDATE users SET name = '{$safeName}', phone = '{$safePhone}' WHERE id = {$safeId}";
        } else {
            $safeEmail = $db->escape($userEmail);
            $updateSql = "UPDATE users SET name = '{$safeName}', phone = '{$safePhone}' WHERE email = '{$safeEmail}'";
        }

        if ($db->query($updateSql) !== false) {
            $_SESSION['user_name'] = $newName;
            header("Location: profile.php?updated=1");
            exit;
        } else {
            $errorMsg = "Failed to update profile. Please try again.";
        }
    }
}

// Fetch Current User Details
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

$currentName  = $userData['name'] ?? $_SESSION['user_name'] ?? '';
$currentPhone = $userData['phone'] ?? '';

include __DIR__ . '/layouts/header.php';
?>
<div class="wrapper">
    <?php include __DIR__ . '/layouts/sidebar.php'; ?>

    <main class="main-content">
        <?php include __DIR__ . '/layouts/topbar.php'; ?>

        <div class="content">
            <header class="profile-header">
                <div class="greeting">
                    <h1>Edit Personal Information</h1>
                    <p><i class="fas fa-edit"></i> Update your personal profile details</p>
                </div>
            </header>

            <section class="profile-grid">
                <article class="profile-card">
                    <div class="card-header">
                        <i class="fas fa-id-card"></i>
                        Personal Information Form
                    </div>

                    <?php if ($errorMsg): ?>
                        <div class="alert alert-danger mb-3" style="color: #721c24; background-color: #f8d7da; padding: 10px; border-radius: 6px;">
                            <?php echo htmlspecialchars($errorMsg); ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="edit_profile.php">
                        <div class="info-row">
                            <span class="label">Full Name</span>
                            <span class="value">
                                <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($currentName); ?>" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="label">Phone Number</span>
                            <span class="value">
                                <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($currentPhone); ?>" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                            </span>
                        </div>

                        <div class="btn-group" style="margin-top: 20px;">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Changes
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