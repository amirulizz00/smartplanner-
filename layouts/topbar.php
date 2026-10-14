<?php
$currentScript = $_SERVER['SCRIPT_NAME'] ?? '';

// Determine relative path to cases/cases.php and cases/add_case.php dynamically
$casesListHref = 'cases/cases.php';
$caseCreateHref = 'cases/add_case.php';

if (str_contains($currentScript, '/clients/plan/')) {
    $casesListHref = '../../cases/cases.php';
    $caseCreateHref = '../../cases/add_case.php';
} elseif (
    str_contains($currentScript, '/cases/') || 
    str_contains($currentScript, '/clients/') || 
    str_contains($currentScript, '/education/')
) {
    if (str_contains($currentScript, '/cases/')) {
        $casesListHref = 'cases.php';
        $caseCreateHref = 'add_case.php';
    } else {
        $casesListHref = '../cases/cases.php';
        $caseCreateHref = '../cases/add_case.php';
    }
}
?>
<div class="topbar">

    <div class="back">
        <a href="javascript:history.back()">
            <i class="fas fa-arrow-left"></i>
            Back
        </a>

    </div>
    <div class="search-box">
        <form action="<?php echo htmlspecialchars($casesListHref); ?>" method="GET" id="topbarSearchForm" style="margin: 0; width: 100%;">
            <input
                type="text"
                name="search"
                placeholder="Search clients or cases..."
                id="searchInput"
                value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>"
            >
        </form>
    </div>

    <div class="topbar-right">

        <span id="notificationBell">🔔</span>

        <span id="helpIcon">❓</span>

        <span id="supportText">Support</span>

        <a class="btn-new-case" href="<?php echo htmlspecialchars($caseCreateHref); ?>">
            New Case
        </a>

        <div class="avatar" id="avatarBtn">
            AS
        </div>

    </div>

</div>