<?php
require_once __DIR__ . '/includes/bootstrap.php';

smartwills_require_login();

$activePage = 'profile';
$pageTitle = 'My Profile - SmartWills';
$pageStyles = ['profile.css'];
$pageScripts = ['profile.js'];

// Retrieve current logged in user data
$userId    = $_SESSION["portal"]["user"]["id"] ?? ($_SESSION['user_id'] ?? null);
$userEmail = $_SESSION['user_email'] ?? 'maminuddenh@gmail.com';

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

// Map database columns to display variables
$name             = $userData['name'] ?? $_SESSION['user_name'] ?? 'Amin';
$email            = $userData['email'] ?? $userEmail;
$phone            = $userData['phone'] ?? 'Not provided';
$role             = $userData['role'] ?? 'Planner';
$bankName         = $userData['bank_name'] ?? 'Not provided';
$bankAccNo        = $userData['bank_account_no'] ?? 'Not provided';
$bankAccHolder    = $userData['bank_account_holder'] ?? 'Not provided';
$bankSwift        = $userData['bank_swift'] ?? 'Not provided';

// Generate initials for avatar (e.g., "Amin" -> "A")
$initials = '';
$nameParts = explode(' ', trim($name));
foreach ($nameParts as $part) {
    if (!empty($part)) {
        $initials .= strtoupper($part[0]);
    }
}
$avatarText = substr($initials, 0, 2) ?: 'U';

include __DIR__ . '/layouts/header.php';
?>
<div class="wrapper">
    <?php include __DIR__ . '/layouts/sidebar.php'; ?>

    <main class="main-content">
        <?php include __DIR__ . '/layouts/topbar.php'; ?>

        <div class="content">
            <header class="profile-header">
                <div class="avatar"><?php echo htmlspecialchars($avatarText); ?></div>
                <div class="greeting">
                    <h1>My Profile</h1>
                    <p><i class="fas fa-user-check"></i> Welcome back, <?php echo htmlspecialchars($name); ?></p>
                </div>
            </header>

            <section class="profile-grid" aria-label="Profile information">
                <!-- Personal Information Card -->
                <article class="profile-card">
                    <div class="card-header">
                        <i class="fas fa-id-card"></i>
                        Personal Information
                    </div>
                    <div class="info-row">
                        <span class="label">Full Name</span>
                        <span class="value"><?php echo htmlspecialchars($name); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="label">Email</span>
                        <span class="value"><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($email); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="label">Phone</span>
                        <span class="value"><i class="fas fa-phone"></i> <?php echo htmlspecialchars($phone); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="label">Role</span>
                        <span class="value"><i class="fas fa-user-tag"></i> <?php echo htmlspecialchars($role); ?></span>
                    </div>
                    <div class="btn-group">
                        <a href="edit_profile.php" class="btn btn-primary">
                            <i class="fas fa-edit"></i>
                            Update Profile
                        </a>
                    </div>
                </article>

                <!-- Bank Account Information Card -->
                <article class="profile-card">
                    <div class="card-header">
                        <i class="fas fa-university"></i>
                        Bank Account Information
                    </div>
                    <div class="info-row">
                        <span class="label">Bank Name</span>
                        <span class="value"><i class="fas fa-building"></i> <?php echo htmlspecialchars($bankName); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="label">Account Number</span>
                        <span class="value"><i class="fas fa-credit-card"></i> <?php echo htmlspecialchars($bankAccNo); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="label">Account Holder</span>
                        <span class="value"><?php echo htmlspecialchars($bankAccHolder); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="label">SWIFT Code</span>
                        <span class="value"><?php echo htmlspecialchars($bankSwift); ?></span>
                    </div>
                    <div class="btn-group">
                        <a href="edit_bank.php" class="btn btn-outline">
                            <i class="fas fa-cog"></i>
                            Manage Account
                        </a>
                    </div>
                </article>
            </section>
        </div>
    </main>
</div>
<?php include __DIR__ . '/layouts/footer.php'; ?>