<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/controller/casecontrol.php';

smartwills_require_login();

$activePage = 'cases';
$pageTitle = 'Case Management · SmartWills';
$pageStyles = ['cases.css'];
$pageScripts = ['cases.js'];
$caseController = new CaseController();
$cases = $caseController->listCases();  //points to the listCases() method in casecontrol.php

//set default counts for each status
$caseCounts = [
    'in-progress' => 0,
    'in-review' => 0,
    'completed' => 0,
    'rejected' => 0,
    'pending' => 0,
];

foreach ($cases as $case) {
    if (isset($caseCounts[$case['status']])) {
        $caseCounts[$case['status']]++;
    }
}

include __DIR__ . '/layouts/header.php';
?>
<div class="wrapper">
    <?php include __DIR__ . '/layouts/sidebar.php'; ?>
    <main class="main-content">
        <?php include __DIR__ . '/layouts/topbar.php'; ?>
        <div class="content">
            <div class="page-header">
                <h1><i class="fas fa-gavel"></i> Case Management</h1>
                <a class="btn-primary" href="add_case.php"><i class="fas fa-plus"></i> Add Case</a>
            </div>

            <div class="stats-grid">
                <!--fetches count for each status-->
                <div class="stat-card"><div class="stat-icon blue"><i class="fas fa-spinner"></i></div><div><div class="stat-number" id="statInProgress"><?= $caseCounts['in-progress'] ?></div><div class="stat-label">In Progress</div></div></div>
                <div class="stat-card"><div class="stat-icon orange"><i class="fas fa-search"></i></div><div><div class="stat-number" id="statInReview"><?= $caseCounts['in-review'] ?></div><div class="stat-label">In Review</div></div></div>
                <div class="stat-card"><div class="stat-icon green"><i class="fas fa-check-circle"></i></div><div><div class="stat-number" id="statCompleted"><?= $caseCounts['completed'] ?></div><div class="stat-label">Completed</div></div></div>
                <div class="stat-card"><div class="stat-icon red"><i class="fas fa-times-circle"></i></div><div><div class="stat-number" id="statRejected"><?= $caseCounts['rejected'] ?></div><div class="stat-label">Rejected</div></div></div>
                <div class="stat-card"><div class="stat-icon purple"><i class="fas fa-clock"></i></div><div><div class="stat-number" id="statPending"><?= $caseCounts['pending'] ?></div><div class="stat-label">Pending</div></div></div>
            </div>

            <div class="toolbar">
                <div class="search-box"><i class="fas fa-search"></i><input type="text" placeholder="Search by client / case ID" id="searchInput"></div>
                <div class="filter-group">
                    <select id="filterStatus">
                        <option value="all">All Status</option>
                        <option value="in-progress">In Progress</option>
                        <option value="in-review">In Review</option>
                        <option value="completed">Completed</option>
                        <option value="rejected">Rejected</option>
                        <option value="pending">Pending</option>
                    </select>
                </div>
            </div>

            <div class="table-wrapper">
                <div class="table-scroll">
                    <table class="case-table">
                        <thead><tr><th>Case ID</th><th>Client</th><th>Case Type</th><th>Status</th><th>Submitted</th><th>Updated</th><th>Actions</th></tr></thead>
                        <tbody id="caseTableBody">
                            <?php if (empty($cases)): ?>
                                <tr>
                                    <td colspan="7" style="text-align:center;padding:40px;color:#8aa4bc;">No cases found</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($cases as $case): ?>
                                    <tr data-case-row data-status="<?= htmlspecialchars($case['status']) ?>">
                                        <td><span class="case-id">#<?= (int)$case['id'] ?></span></td>
                                        <td>
                                            <div class="client-name">
                                                <span class="avatar"><?= htmlspecialchars(strtoupper(substr($case['client'], 0, 1))) ?></span>
                                                <?= htmlspecialchars($case['client']) ?>
                                            </div>
                                        </td>
                                        <td><?= htmlspecialchars($case['type']) ?></td>
                                        <td><span class="status-badge <?= htmlspecialchars($case['status']) ?>"><?= htmlspecialchars(ucwords(str_replace('-', ' ', $case['status']))) ?></span></td>
                                        <td><?= htmlspecialchars($case['submitted']) ?></td>
                                        <td><?= htmlspecialchars($case['updated']) ?></td>
                                        <td>
                                            <div class="action-cell">
                                                <a class="btn-sm btn-view" href="edit_case.php?id=<?= (int)$case['id'] ?>"><i class="fas fa-eye"></i> View</a>
                                                <a class="btn-sm btn-edit" href="edit_case.php?id=<?= (int)$case['id'] ?>"><i class="fas fa-edit"></i> Edit</a>
                                                <form method="POST" action="delete_case.php" onsubmit="return confirm('Delete case #<?= (int)$case['id'] ?>?');">
                                                    <input type="hidden" name="id" value="<?= (int)$case['id'] ?>">
                                                    <button type="submit" class="btn-sm btn-delete"><i class="fas fa-trash-alt"></i> Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <footer>© 2026 smartwillsplanner.com</footer>
        </div>
    </main>
</div>
<?php include __DIR__ . '/layouts/footer.php'; ?>