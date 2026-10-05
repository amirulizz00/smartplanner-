<?php
    ob_start();
    session_start();

    require_once __DIR__ . '/includes/bootstrap.php';

    if (!isset($_SESSION["portal"]["user"]) || empty($_SESSION["portal"]["user"]["id"])) {
        header("Location: login.php");
        exit();
    }

    $activePage = 'dashboard';
    $pageTitle = 'SmartWill Planner · Dashboard';
    $pageStyles = ['index.css'];
    $pageScripts = ['index.js'];

    $userName = $_SESSION["portal"]["user"]["name"] ?? 'User';

    $Cases = 0;
    $NewClients = 0;
    $TrainingModules = 0;
    $EndingCommission = 0;

    try {

    // Fetch counts from the database  
        $casesCount = $db->query("SELECT COUNT(*) AS total FROM cases");
        $Cases = (int)($casesCount->rows[0]['total'] ?? 0);

        $clientsCount = $db->query("SELECT COUNT(*) AS total FROM client");
        $NewClients = (int)($clientsCount->rows[0]['total'] ?? 0);

        $usersCount = $db->query("SELECT COUNT(*) AS total FROM users");
        $TrainingModules = (int)($usersCount->rows[0]['total'] ?? 0);

        $completedCount = $db->query("SELECT COUNT(*) AS total FROM cases WHERE LOWER(TRIM(case_status)) = 'completed'");
        $EndingCommission = (int)($completedCount->rows[0]['total'] ?? 0);
    } catch (Throwable $e) {
        $Cases = 0;
        $NewClients = 0;
        $TrainingModules = 0;
        $EndingCommission = 0;
    }

    include __DIR__ . '/layouts/header.php';
?>
<div class="wrapper">
    <?php include __DIR__ . '/layouts/sidebar.php'; ?>
    <main class="main-content">
        <?php include __DIR__ . '/layouts/topbar.php'; ?>
        <div class="content">
            <div class="hero-banner">
                <h5>DASHBOARD</h5>
                <h1>Welcome back, <?php echo htmlspecialchars($userName); ?> 👋</h1>
                <p>Here's an overview of your estate planning portal today.</p>
                <div class="date-badge"><i class="far fa-calendar-alt"></i> <span id="realTimeDate">----Year--Month--Day</span></div>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon blue"><i class="fas fa-briefcase"></i></div>
                    <div>
                        <div class="stat-number"><?php echo (int)$Cases; ?></div>
                        <div class="stat-label">Active Cases</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon green"><i class="fas fa-user-plus"></i></div>
                    <div>
                        <div class="stat-number"><?php echo (int)$NewClients; ?></div>
                        <div class="stat-label">New Clients</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon orange"><i class="fas fa-chalkboard-user"></i></div>
                    <div>
                        <div class="stat-number"><?php echo (int)$TrainingModules; ?></div>
                        <div class="stat-label">Training Modules</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon purple"><i class="fas fa-dollar-sign"></i></div>
                    <div>
                        <div class="stat-number"><?php echo (int)$EndingCommission; ?></div>
                        <div class="stat-label">Ending Comm.</div>
                    </div>
                </div>
            </div>

            <div class="modules-grid">
                <div class="module-card">
                    <h3>Announcement &amp; Update</h3>
                    <p>No new announcements</p>
                </div>
                <div class="module-card">
                    <h3>Upcoming Events &amp; Training</h3>
                    <p>No upcoming events</p>
                </div>
                <div class="module-card recent-cases">
                    <h3>Recent Cases</h3>
                    <p>No recent cases</p>
                    <div class="placeholder-table">
                        <span class="header">Client</span>
                        <span class="header">Product</span>
                        <span class="header">Status</span>
                        <span class="header">Date</span>
                        <span>—</span>
                        <span>—</span>
                        <span>—</span>
                        <span>—</span>
                        <span>—</span>
                        <span>—</span>
                        <span>—</span>
                        <span>—</span>
                    </div>
                </div>
            </div>

            <footer>© 2026 smartwillsplanner.com</footer>
        </div>
    </main>
</div>

<?php include __DIR__ . '/layouts/footer.php'; ?>