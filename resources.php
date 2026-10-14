<?php
require_once __DIR__ . '/includes/bootstrap.php';

smartwills_require_login();

$activePage = 'resources';
$pageTitle = 'Resource Center - SmartWills';
$pageStyles = ['resources.css'];
$pageScripts = ['resources.js'];

include __DIR__ . '/layouts/header.php';
?>
<div class="wrapper">
    <?php include __DIR__ . '/layouts/sidebar.php'; ?>

    <main class="main-content">
        <?php include __DIR__ . '/layouts/topbar.php'; ?>

        <div class="content">
            <header class="page-header">
                <h1><i class="fas fa-database"></i> Resource Center</h1>
                <button class="btn-primary" id="supportBtn">
                    <i class="fas fa-headset"></i>
                    Submit a Ticket
                </button>
            </header>

            <section class="resource-grid" aria-label="Resource categories">
                <article class="res-card" data-resource="compliance">
                    <div class="icon-wrap blue"><i class="fas fa-shield-alt"></i></div>
                    <h4>Compliance &amp; Guidelines</h4>
                    <p>Policies, AML, regulatory updates</p>
                    <div class="meta">
                        <span class="badge">12 items</span>
                        <i class="fas fa-arrow-right" ></i>
                    </div>
                </article>

                <article class="res-card" data-resource="forms">
                    <div class="icon-wrap green"><i class="fas fa-file-alt"></i></div>
                    <h4>Forms &amp; Documents</h4>
                    <p>Templates, contracts, disclosure forms</p>
                    <div class="meta">
                        <span class="badge green">8 items</span>
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </article>

                <article class="res-card" data-resource="training">
                    <div class="icon-wrap purple"><i class="fas fa-video"></i></div>
                    <h4>Training Resources</h4>
                    <p>Videos, PPT, PDFs, brochures</p>
                    <div class="meta">
                        <span class="badge">24 items</span>
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </article>

                <article class="res-card" data-resource="marketplace">
                    <div class="icon-wrap orange"><i class="fas fa-store"></i></div>
                    <h4>Shopping &amp; Marketplace</h4>
                    <p>Suppliers, merchandise, gifts</p>
                    <div class="meta">
                        <span class="badge orange">6 items</span>
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </article>

                <article class="res-card" data-resource="support">
                    <div class="icon-wrap red"><i class="fas fa-ticket-alt"></i></div>
                    <h4>Support</h4>
                    <p>Submit tickets, FAQ, knowledge base</p>
                    <div class="meta">
                        <span class="badge red">3 items</span>
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </article>
            </section>
        </div>
    </main>
</div>
<?php include __DIR__ . '/layouts/footer.php'; ?>