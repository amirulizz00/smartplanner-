<?php
require_once __DIR__ . '/includes/bootstrap.php';

smartwills_require_login();

$activePage  = 'settings';
$pageTitle   = 'Settings - SmartWills';
$pageStyles  = ['settings.css'];
$pageScripts = ['settings.js'];

$userId    = $_SESSION["portal"]["user"]["id"] ?? ($_SESSION['user_id'] ?? null);
$userEmail = $_SESSION["portal"]["user"]["email"] ?? ($_SESSION['user_email'] ?? '');

$pwdSuccessMsg  = '';
$pwdErrorMsg    = '';
$infoSuccessMsg = '';
$infoErrorMsg   = '';

// 1. Process Password Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_password') {
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword     = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
        $pwdErrorMsg = 'Please fill in all password fields.';
    } elseif ($newPassword !== $confirmPassword) {
        $pwdErrorMsg = 'New password and confirm password do not match.';
    } elseif (strlen($newPassword) < 6) {
        $pwdErrorMsg = 'New password must be at least 6 characters long.';
    } elseif (isset($db)) {
        if ($userId) {
            $safeId = (int)$userId;
            $res = $db->query("SELECT password FROM users WHERE id = {$safeId} LIMIT 1");
        } else {
            $safeEmail = $db->escape($userEmail);
            $res = $db->query("SELECT password FROM users WHERE email = '{$safeEmail}' LIMIT 1");
        }

        if ($res && $res->num_rows > 0) {
            $userRecord = $res->rows[0];
            $dbPassword = $userRecord['password'] ?? '';

            $isPasswordValid = password_verify($currentPassword, $dbPassword) || ($currentPassword === $dbPassword);

            if ($isPasswordValid) {
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $sHash = $db->escape($hashedPassword);

                if ($userId) {
                    $updateSql = "UPDATE users SET password = '{$sHash}' WHERE id = {$safeId}";
                } else {
                    $updateSql = "UPDATE users SET password = '{$sHash}' WHERE email = '{$safeEmail}'";
                }

                if ($db->query($updateSql) !== false) {
                    $pwdSuccessMsg = 'Password updated successfully';
                } else {
                    $pwdErrorMsg = 'Failed to update password';
                }
            } else {
                $pwdErrorMsg = 'Current password is incorrect.';
            }
        } else {
            $pwdErrorMsg = 'User account not found.';
        }
    }
}

// 2. Process Personal Information Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_profile') {
    $newName = trim($_POST['name'] ?? '');

    if (empty($newName)) {
        $infoErrorMsg = 'Full name cannot be empty.';
    } elseif (isset($db)) {
        $sName = $db->escape($newName);

        if ($userId) {
            $safeId = (int)$userId;
            $updateSql = "UPDATE users SET name = '{$sName}' WHERE id = {$safeId}";
        } else {
            $safeEmail = $db->escape($userEmail);
            $updateSql = "UPDATE users SET name = '{$sName}' WHERE email = '{$safeEmail}'";
        }

        if ($db->query($updateSql) !== false) {
            if (isset($_SESSION["portal"]["user"])) {
                $_SESSION["portal"]["user"]["name"] = $newName;
            }
            $_SESSION['user_name'] = $newName;
            $infoSuccessMsg = 'Personal information saved successfully!';
        } else {
            $infoErrorMsg = 'Failed to update user details';
        }
    }
}

// Fetch Current User Details from MySQL
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

$userName  = $userData['name'] ?? $_SESSION["portal"]["user"]["name"] ?? 'ali abu';
$userEmail = $userData['email'] ?? $userEmail;
$userIdVal = $userData['id'] ?? $userId ?? '3';

include __DIR__ . '/layouts/header.php';
?>
<div class="wrapper">
    <?php include __DIR__ . '/layouts/sidebar.php'; ?>

    <main class="main-content">
        <?php include __DIR__ . '/layouts/topbar.php'; ?>

        <div class="content">
            <header class="settings-header">
                <div class="icon-box"><i class="fas fa-sliders-h"></i></div>
                <div class="greeting">
                    <h1>Settings</h1>
                    <p><i class="fas fa-cog"></i> Manage your account preferences and security</p>
                </div>
            </header>

            <section class="settings-grid" aria-label="Account settings">
                <!-- Security Form -->
                <article class="settings-card">
                    <div class="card-header">
                        <i class="fas fa-lock"></i>
                        Security
                    </div>

                    <?php if ($pwdSuccessMsg): ?>
                        <div class="alert alert-success" style="color: #155724; background-color: #d4edda; padding: 10px; border-radius: 6px; margin-bottom: 15px;">
                            <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($pwdSuccessMsg); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($pwdErrorMsg): ?>
                        <div class="alert alert-danger" style="color: #721c24; background-color: #f8d7da; padding: 10px; border-radius: 6px; margin-bottom: 15px;">
                            <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($pwdErrorMsg); ?>
                        </div>
                    <?php endif; ?>

                    <form class="settings-form" method="POST" action="settings.php">
                        <input type="hidden" name="action" value="update_password">
                        <div class="setting-row">
                            <span class="label"><i class="fas fa-key"></i> Current Password</span>
                            <input type="password" name="current_password" placeholder="Enter your current password" required>
                        </div>
                        <div class="setting-row">
                            <span class="label"><i class="fas fa-lock"></i> New Password</span>
                            <input type="password" name="new_password" placeholder="Enter a strong new password" required>
                        </div>
                        <div class="setting-row">
                            <span class="label"><i class="fas fa-check-circle"></i> Confirm Password</span>
                            <input type="password" name="confirm_password" placeholder="Re-enter your new password" required>
                        </div>
                        <div class="btn-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                Update Password
                            </button>
                        </div>
                    </form>
                </article>

                <!-- Personal Info Form -->
                <article class="settings-card">
                    <div class="card-header">
                        <i class="fas fa-user-edit"></i>
                        Personal Information
                    </div>

                    <?php if ($infoSuccessMsg): ?>
                        <div class="alert alert-success" style="color: #155724; background-color: #d4edda; padding: 10px; border-radius: 6px; margin-bottom: 15px;">
                            <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($infoSuccessMsg); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($infoErrorMsg): ?>
                        <div class="alert alert-danger" style="color: #721c24; background-color: #f8d7da; padding: 10px; border-radius: 6px; margin-bottom: 15px;">
                            <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($infoErrorMsg); ?>
                        </div>
                    <?php endif; ?>

                    <form class="settings-form" method="POST" action="settings.php">
                        <input type="hidden" name="action" value="update_profile">
                        <div class="setting-row">
                            <span class="label"><i class="fas fa-user"></i> Full Name</span>
                            <input type="text" name="name" placeholder="Enter your full name" value="<?php echo htmlspecialchars($userName); ?>" required>
                        </div>
                        <div class="setting-row">
                            <span class="label"><i class="fas fa-id-badge"></i> Account ID</span>
                            <input type="text" value="SW-<?php echo htmlspecialchars($userIdVal); ?>" readonly>
                        </div>
                        <div class="setting-row">
                            <span class="label"><i class="fas fa-envelope"></i> Email</span>
                            <input type="email" name="email" value="<?php echo htmlspecialchars($userEmail); ?>" readonly>
                        </div>
                        <div class="btn-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                Save Personal Info
                            </button>
                        </div>
                    </form>
                </article>
            </section>
        </div>
    </main>
</div>
<?php include __DIR__ . '/layouts/footer.php'; ?>