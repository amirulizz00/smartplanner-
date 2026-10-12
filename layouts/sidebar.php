<?php
$pathPrefix = $pathPrefix ?? '';
if ($pathPrefix === '') {
    $projectRoot = dirname(__DIR__);
    $scriptPath = realpath($_SERVER['SCRIPT_FILENAME'] ?? '');
    $currentDir = $scriptPath ? dirname($scriptPath) : '';
    $projectRootReal = realpath($projectRoot) ?: $projectRoot;

    if ($currentDir && str_starts_with($currentDir, $projectRootReal)) {
        $relative = trim(str_replace($projectRootReal, '', $currentDir), DIRECTORY_SEPARATOR);
        $segmentCount = $relative === '' ? 0 : count(array_filter(explode(DIRECTORY_SEPARATOR, $relative)));
        $pathPrefix = str_repeat('../', $segmentCount);
    }
}
$assetBase = $assetBase ?? smartwills_asset_base();
?>
<button class="hamburger-menu" id="hamburgerMenu" aria-label="Open sidebar">
    <span></span>
    <span></span>
    <span></span>
</button>

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="sidebar">
    <div class="sidebar-header">
        <img
            src="<?php echo htmlspecialchars($assetBase); ?>/images/smartwills-logo.png"
            alt="SmartWills Logo"
            class="logo-image"
        >

        <div class="domain">
            smartwillsplanner.com
        </div>
    </div>

    <div class="sidebar-menu">
        <a href="<?php echo htmlspecialchars($pathPrefix); ?>index.php"
           class="menu-item <?= ($activePage == 'dashboard') ? 'active' : ''; ?>">
            Dashboard
        </a>

        <a href="<?php echo htmlspecialchars($pathPrefix); ?>clients/clients.php"
           class="menu-item <?= ($activePage == 'clients') ? 'active' : ''; ?>">
            Clients
        </a>

        <a href="<?php echo htmlspecialchars($pathPrefix); ?>cases/cases.php"
           class="menu-item <?= ($activePage == 'cases') ? 'active' : ''; ?>">
            Cases
        </a>

        <a href="<?php echo htmlspecialchars($pathPrefix); ?>tools.php"
           class="menu-item <?= ($activePage == 'tools') ? 'active' : ''; ?>">
            Tools
        </a>

        <a href="<?php echo htmlspecialchars($pathPrefix); ?>education/dashboard.php"
           class="menu-item <?= ($activePage == 'education') ? 'active' : ''; ?>">
            Education
        </a>

        <a href="<?php echo htmlspecialchars($pathPrefix); ?>resources.php"
           class="menu-item <?= ($activePage == 'resources') ? 'active' : ''; ?>">
            Resources
        </a>

        <a href="<?php echo htmlspecialchars($pathPrefix); ?>salesreport.php"
           class="menu-item <?= ($activePage == 'salesreport') ? 'active' : ''; ?>">
            Sales Report
        </a>
    </div>

    <div class="sidebar-footer">
        <a href="<?php echo htmlspecialchars($pathPrefix); ?>profile.php"
           class="menu-item <?= ($activePage == 'profile') ? 'active' : ''; ?>">My Profile</a>

        <a href="<?php echo htmlspecialchars($pathPrefix); ?>settings.php"
           class="menu-item <?= ($activePage == 'settings') ? 'active' : ''; ?>">Settings</a>

        <a href="<?php echo htmlspecialchars($pathPrefix); ?>login.php"
           class="menu-item <?= ($activePage == 'Logout') ? 'active' : ''; ?>">Logout</a>
    </div>
</div>