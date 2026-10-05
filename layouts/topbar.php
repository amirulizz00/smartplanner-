<?php
$currentScript = $_SERVER['SCRIPT_NAME'] ?? '';
$caseCreateHref = 'add_case.php';
if (str_contains($currentScript, '/clients/plan/')) {
    $caseCreateHref = '../../add_case.php';
} elseif (str_contains($currentScript, '/clients/') || str_contains($currentScript, '/education/')) {
    $caseCreateHref = '../add_case.php';
}
?>
<div class="topbar">

    <div class="search-box">

        <input
            type="text"
            placeholder="Search clients or cases..."
            id="searchInput"
        >

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