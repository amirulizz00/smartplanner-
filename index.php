<?php
    ob_start();
    session_start();

    require_once __DIR__ . '/includes/bootstrap.php';
    require_once __DIR__ . '/controller/casecontrol.php';

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
    $recentCases = [];

    try {
        // 1. Fetch counts from database
        $casesCount = $db->query("SELECT COUNT(*) AS total FROM cases");
        $Cases = (int)($casesCount->rows[0]['total'] ?? 0);

        $clientsCount = $db->query("SELECT COUNT(*) AS total FROM client");
        $NewClients = (int)($clientsCount->rows[0]['total'] ?? 0);

        $usersCount = $db->query("SELECT COUNT(*) AS total FROM users");
        $TrainingModules = (int)($usersCount->rows[0]['total'] ?? 0);

        $completedCount = $db->query("SELECT COUNT(*) AS total FROM cases WHERE LOWER(TRIM(case_status)) = 'completed'");
        $EndingCommission = (int)($completedCount->rows[0]['total'] ?? 0);

        // 2. Fetch recent cases using CaseController
        $caseController = new CaseController();
        $allCases = $caseController->listCases();
        $recentCases = array_slice($allCases, 0, 5); // Take the latest 5 records

    } catch (Throwable $e) {
        $Cases = 0;
        $NewClients = 0;
        $TrainingModules = 0;
        $EndingCommission = 0;
        $recentCases = [];
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
                <div class="module-card recent-cases" style="grid-column: 1 / -1;">
                    <h3>Recent Cases</h3>
                    <?php if (empty($recentCases)): ?>
                        <p>No recent cases</p>
                    <?php else: ?>
                        <div class="table-scroll" style="margin-top: 15px; overflow-x: auto;">
                            <table class="case-table" style="width: 100%; border-collapse: collapse;">
                                <thead>
                                    <tr style="text-align: left; border-bottom: 1px solid #eef2f8; color: #8aa4bc; font-size: 13px;">
                                        <th style="padding: 10px 8px;">Client</th>
                                        <th style="padding: 10px 8px;">Product</th>
                                        <th style="padding: 10px 8px;">Status</th>
                                        <th style="padding: 10px 8px;">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recentCases as $case): ?>
                                        <?php 
                                            $clientName = !empty($case['client_name']) ? $case['client_name'] : '—';
                                            $status = !empty($case['status']) ? $case['status'] : 'Pending';
                                            $statusSlug = strtolower(str_replace(' ', '-', trim($status)));
                                        ?>
                                        <tr style="border-bottom: 1px solid #f7f9fb;">
                                            <td style="padding: 12px 8px; font-weight: 600; color: #2c4a66;">
                                                <?= htmlspecialchars($clientName) ?>
                                            </td>
                                            <td style="padding: 12px 8px; color: #555;">
                                                <?= htmlspecialchars($case['type'] ?? '—') ?>
                                            </td>
                                            <td style="padding: 12px 8px;">
                                                <span class="status-badge <?= htmlspecialchars($statusSlug) ?>">
                                                    <?= htmlspecialchars(ucwords(str_replace('-', ' ', $statusSlug))) ?>
                                                </span>
                                            </td>
                                            <td style="padding: 12px 8px; color: #8aa4bc; font-size: 13px;">
                                                <?= htmlspecialchars($case['submitted_at'] ?? '—') ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <footer>© 2026 smartwillsplanner.com</footer>
        </div>
    </main>
</div>

<?php include __DIR__ . '/layouts/footer.php'; ?>