<?php
$assetBase = '../assets';

require_once __DIR__ . '/../includes/bootstrap.php';

smartwills_require_login();

$activePage = 'resources';
$pageTitle = 'Shopping & Marketplace - SmartWills';
$pageStyles = ['resources.css'];
$pageScripts = ['marketplace.js'];

include __DIR__ . '/../layouts/header.php';
?>
<div class="wrapper">
    <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

    <main class="main-content">
        <?php include __DIR__ . '/../layouts/topbar.php'; ?>

        <div class="content">
            <header class="page-header">
                <h1><i class="fas fa-shopping-cart"></i> Shopping & Marketplace</h1>
                <button class="btn-primary" id="cartBtn">
                    <i class="fas fa-shopping-cart"></i>
                    Your Cart
                </button>
            </header>

        