<?php
// 1. Include core settings to prevent the fatal error in sidebar.php
require_once __DIR__ . '/../includes/bootstrap.php';

// 2. Disable the login redirect to allow direct interface access
// smartwills_require_login();

$activePage = 'clients';
$clientId = isset($_GET['id']) ? intval($_GET['id']) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Risk Assessment · SmartWills</title>
    <!-- Global styles -->
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/sidebar.css">
    <link rel="stylesheet" href="../assets/css/topbar.css">
    <link rel="stylesheet" href="../assets/css/components.css">
    <!-- Page-specific styles -->
    <link rel="stylesheet" href="../assets/css/risk_assessment.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
<div class="wrapper">
    <?php include '../layouts/sidebar.php'; ?>
    <div class="main-content">
        <?php include '../layouts/topbar.php'; ?>
        <div class="content">
            <!-- Page header -->
            <div class="page-header">
                <h1><i class="fas fa-clipboard-list"></i> Risk Assessment</h1>
                <div class="client-info" id="clientInfo">
                    <span class="avatar" id="clientAvatar">?</span>
                    <span class="client-name-text" id="clientNameDisplay">Loading...</span>
                </div>
            </div>

            <!-- Risk assessment container -->
            <div id="riskContainer">
                <!-- Form section -->
                <div id="riskFormSection">
                    <div class="intro-text">
                        <i class="fas fa-info-circle" style="color:#7a3f9e;margin-right:8px;"></i>
                        For us to serve you better, kindly tick where appropriate when any of the situations apply to you.
                    </div>

                    <!-- Personal & Family -->
                    <div class="risk-section">
                        <h3><i class="fas fa-user"></i> Personal & Family Circumstances</h3>
                        <div class="ck-item"><input type="checkbox" id="risk_p1"><label for="risk_p1">I have no Will</label></div>
                        <div class="ck-item"><input type="checkbox" id="risk_p2"><label for="risk_p2">My marital status has changed recently</label></div>
                        <div class="ck-item"><input type="checkbox" id="risk_p3"><label for="risk_p3">My spouse is unfamiliar with business/financial matters</label></div>
                        <div class="ck-item"><input type="checkbox" id="risk_p4"><label for="risk_p4">I am unsure who to appoint to manage my affairs after my passing</label></div>
                        <div class="ck-item"><input type="checkbox" id="risk_p5"><label for="risk_p5">I have a newborn in the family</label></div>
                        <div class="ck-item"><input type="checkbox" id="risk_p6"><label for="risk_p6">I have young children</label></div>
                        <div class="ck-item"><input type="checkbox" id="risk_p7"><label for="risk_p7">I named my young children as beneficiaries to my insurance policies</label></div>
                        <div class="ck-item"><input type="checkbox" id="risk_p8"><label for="risk_p8">I have a child with special needs</label></div>
                        <div class="ck-item"><input type="checkbox" id="risk_p9"><label for="risk_p9">There is strong sibling rivalry among my children</label></div>
                        <div class="ck-item"><input type="checkbox" id="risk_p10"><label for="risk_p10">I have dependents, e.g. parents or parents-in-law whom I provide for</label></div>
                    </div>

                    <!-- Executor, Guardian -->
                    <div class="risk-section">
                        <h3><i class="fas fa-shield-alt"></i> Executor, Guardian &amp; Safekeeping Issues Circumstances</h3>
                        <div class="ck-item"><input type="checkbox" id="risk_e1"><label for="risk_e1">I am no longer in touch with the appointed executor</label></div>
                        <div class="ck-item"><input type="checkbox" id="risk_e2"><label for="risk_e2">I am no longer in touch with the appointed guardian</label></div>
                        <div class="ck-item"><input type="checkbox" id="risk_e3"><label for="risk_e3">I need to pass my Will to someone for safekeeping</label></div>
                    </div>

                    <!-- Assets -->
                    <div class="risk-section">
                        <h3><i class="fas fa-chart-pie"></i> Assets &amp; Financial Complexity</h3>
                        <div class="ck-item"><input type="checkbox" id="risk_a1"><label for="risk_a1">I have assets in joint names and need to understand the legal implications of such holdings</label></div>
                        <div class="ck-item"><input type="checkbox" id="risk_a2"><label for="risk_a2">I engage in active management of investments or trade in a portfolio of stocks</label></div>
                        <div class="ck-item"><input type="checkbox" id="risk_a3"><label for="risk_a3">I have overseas assets</label></div>
                        <div class="ck-item"><input type="checkbox" id="risk_a4"><label for="risk_a4">I do not have a schedule of assets that details my assets and liabilities</label></div>
                        <div class="ck-item"><input type="checkbox" id="risk_a5"><label for="risk_a5">My family would need continuity of funds while waiting for grant of probate</label></div>
                    </div>

                    <!-- Cross-Border -->
                    <div class="risk-section">
                        <h3><i class="fas fa-globe-asia"></i> Cross-Border Family &amp; Legal Issues</h3>
                        <div class="ck-item"><input type="checkbox" id="risk_c1"><label for="risk_c1">I have family members who are permanent residents or citizens of another jurisdiction</label></div>
                        <div class="ck-item"><input type="checkbox" id="risk_c2"><label for="risk_c2">I have family members who are married to foreigners</label></div>
                        <div class="ck-item"><input type="checkbox" id="risk_c3"><label for="risk_c3">I am a Muslim or from a religious faith where my estate must be divided in a certain manner</label></div>
                    </div>

                    <!-- Business -->
                    <div class="risk-section">
                        <h3><i class="fas fa-briefcase"></i> Business &amp; Legacy Planning</h3>
                        <div class="ck-item"><input type="checkbox" id="risk_b1"><label for="risk_b1">I wish to give a legacy to a charity but am not sure how this could be achieved</label></div>
                        <div class="ck-item"><input type="checkbox" id="risk_b2"><label for="risk_b2">I work well with my business partners, but their heirs may be a problem</label></div>
                        <div class="ck-item"><input type="checkbox" id="risk_b3"><label for="risk_b3">I am concerned that my business becomes fragmented if I divide it among my children</label></div>
                        <div class="ck-item"><input type="checkbox" id="risk_b4"><label for="risk_b4">I am a businessman and am exposed to business risks and liabilities</label></div>
                        <div class="ck-item"><input type="checkbox" id="risk_b5"><label for="risk_b5">I have properties and business interests that can provide for multi-generations or be maintained as long-term resources</label></div>
                    </div>

                    <div class="modal-btns">
                        <button type="button" class="btn btn-cancel" onclick="window.location.href='clients.php'">Cancel</button>
                        <button type="button" class="btn btn-save-risk" id="checkResultBtn"><i class="fas fa-save"></i> Go to Check result</button>
                    </div>
                </div>

                <!-- Result section (initially hidden) -->
                <div id="riskResultSection" style="display:none;">
                    <div class="risk-result">
                        <h3>WHAT'S YOUR SCORE AND WHERE DO YOU STAND?</h3>
                        <div class="score-display" id="resultScore">0 points</div>
                        <div class="risk-level" id="resultLevel">LOW RISK</div>
                        <div class="desc" id="resultDesc">...</div>
                    </div>
                    <div class="modal-btns" style="margin-top:20px;">
                        <button type="button" class="btn btn-cancel" onclick="window.location.href='clients.php'">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Page-specific JS -->
<script src="../assets/js/risk_assessment.js"></script>
</body>
</html>