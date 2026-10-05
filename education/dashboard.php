<?php

ob_start();
session_start();

$userName = $_SESSION["portal"]["user"]["name"] ?? 'User';

// 1. Include core settings to prevent the fatal error in sidebar.php
require_once __DIR__ . '/../includes/bootstrap.php';

// 2. Disable the login redirect to allow direct interface access
smartwills_require_login();

$activePage = 'education';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Education Center · SmartWills</title>
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/sidebar.css">
    <link rel="stylesheet" href="../assets/css/topbar.css">
    <link rel="stylesheet" href="../assets/css/components.css">
    <link rel="stylesheet" href="../assets/css/education.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
<div class="wrapper">
    <?php include '../layouts/sidebar.php'; ?>
    <div class="main-content">
        <?php include '../layouts/topbar.php'; ?>
        <div class="content">
            <!-- Hero Banner -->
            <div class="hero-banner">
                <div class="hero-inner">
                    <div class="hero-left">
                        <h5>EDUCATION CENTER</h5>
                        <h1>Welcome back, <?php echo htmlspecialchars($userName); ?> 👋</h1>
                        <p>Continue your estate planning certification journey.</p>
                    </div>
                    <div class="hero-right">
                        <div class="progress-card">
                            <div class="progress-header">
                                <span class="label">Current Learning</span>
                                <span class="percentage">80%</span>
                            </div>
                            <div class="progress-track">
                                <div class="progress-fill"></div>
                            </div>
                            <p class="progress-desc">
                                <strong>Progress</strong> &nbsp; 4 of 5 Core Certifications Complete
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info Grid -->
            <div class="info-grid">
                <div class="info-card">
                    <h3>Announcement & Update</h3>
                    <p>No new announcements</p>
                </div>
                <div class="info-card">
                    <h3>Upcoming Events & Training</h3>
                    <p>No upcoming events</p>
                </div>
            </div>

            <!-- Course Catalogue with search bar -->
            <div class="section-header">
                <h2>Course Catalogue</h2>
                <div class="search-wrapper">
                    <input type="text" placeholder="Search courses..." id="searchCourse">
                    <button id="searchBtn"><i class="fas fa-search"></i> Search</button>
                </div>
            </div>

            <!-- Tabs: Dropdown + independent buttons -->
            <div class="tabs" id="tabContainer">
                <div class="dropdown">
                    <button class="dropdown-toggle tab" id="dropdownToggle">
                        <span id="selectedLabel">All</span>
                        <i class="fas fa-chevron-down arrow" id="dropdownArrow"></i>
                    </button>
                    <ul class="dropdown-menu" id="dropdownMenu">
                        <li data-tab="all" class="active">All</li>
                        <li data-tab="sg">SG</li>
                        <li data-tab="my">MY</li>
                        <li data-tab="th">TH</li>
                        <li data-tab="mywa">MYWA</li>
                    </ul>
                </div>
                <button class="tab" data-tab="sg">SG</button>
                <button class="tab" data-tab="my">MY</button>
                <button class="tab" data-tab="th">TH</button>
                <button class="tab" data-tab="mywa">MYWA</button>
            </div>

            <!-- Course grid -->
            <div class="course-grid" id="courseGrid"></div>
        </div>
    </div>
</div>

<!--Referencing external JavaScript (after separation) -->
<script src="../assets/js/global.js"></script>
<script src="../assets/js/dashboard.js"></script>
</body>
</html>