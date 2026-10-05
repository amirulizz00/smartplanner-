<?php
    ob_start();
    session_start();
    
    include_once(dirname(__FILE__) . "/include/config.php");
    include_once(dirname(__FILE__) . "/include/function.php");

    // Enforce CTO Zelozz-style login guard
    if (!isset($_SESSION["portal"]["user"]) || empty($_SESSION["portal"]["user"]["id"])) {
        header("Location: login.php");
        exit();
    }

    $activePage = 'settings';
    $pageTitle = 'Settings - SmartWills';
    $pageStyles = ['settings.css'];
    $pageScripts = ['settings.js'];

    // Retrieve logged-in user's session data dynamically
    $userId = $_SESSION["portal"]["user"]["id"] ?? '';
    $userName = $_SESSION["portal"]["user"]["name"] ?? '';
    $userEmail = $_SESSION["portal"]["user"]["email"] ?? '';

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
                <article class="settings-card">
                    <div class="card-header">
                        <i class="fas fa-lock"></i>
                        Security
                    </div>
                    <form class="settings-form" data-demo-message="Password updated (demo)">
                        <div class="setting-row">
                            <span class="label"><i class="fas fa-key"></i> Current Password</span>
                            <input type="password" placeholder="Enter your current password" required>
                        </div>
                        <div class="setting-row">
                            <span class="label"><i class="fas fa-lock"></i> New Password</span>
                            <input type="password" placeholder="Enter a strong new password" required>
                        </div>
                        <div class="setting-row">
                            <span class="label"><i class="fas fa-check-circle"></i> Confirm Password</span>
                            <input type="password" placeholder="Re-enter your new password" required>
                        </div>
                        <div class="btn-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                Update Password
                            </button>
                        </div>
                    </form>
                </article>

                <article class="settings-card">
                    <div class="card-header">
                        <i class="fas fa-user-edit"></i>
                        Personal Information
                    </div>
                    <form class="settings-form" data-demo-message="Personal information saved (demo)">
                        <div class="setting-row">
                            <span class="label"><i class="fas fa-user"></i> Full Name</span>
                            <input type="text" placeholder="Enter your full name" value="<?php echo htmlspecialchars($userName); ?>">
                        </div>
                        <div class="setting-row">
                            <span class="label"><i class="fas fa-birthday-cake"></i> Date of Birth</span>
                            <input type="date" value="1990-01-01">
                        </div>
                        <div class="setting-row">
                            <span class="label"><i class="fas fa-id-badge"></i> Account ID</span>
                            <input type="text" value="SW-<?php echo htmlspecialchars($userId); ?>" readonly>
                        </div>
                        <div class="setting-row">
                            <span class="label"><i class="fas fa-envelope"></i> Email</span>
                            <input type="email" placeholder="Enter your email" value="<?php echo htmlspecialchars($userEmail); ?>">
                        </div>
                        <div class="btn-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                Save Changes
                            </button>
                        </div>
                    </form>
                </article>
            </section>
        </div>
    </main>
</div>
<?php include __DIR__ . '/layouts/footer.php'; ?>