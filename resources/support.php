<?php
$assetBase = '../assets';

require_once __DIR__ . '/../includes/bootstrap.php';

smartwills_require_login();

$activePage = 'resources';
$pageTitle = 'Support & Ticketing - SmartWills';
$pageStyles = ['resources.css', 'fundinggap.css'];
$pageScripts = ['support.js', 'training.js'];

include __DIR__ . '/../layouts/header.php';
?>
<div class="wrapper">
    <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

    <main class="main-content">
        <?php include __DIR__ . '/../layouts/topbar.php'; ?>    

        <div class="content">
            <header class="page-header">
                <h1>
                    <i class="fas fa-ticket-alt"></i> 
                    Support
                </h1>
            </header>

            <section class="resource-grid" aria-label="Resource categories">
                <article class="res-card" data-resource="">
                    <div class="icon-wrap red"><i class="fas fa-book"></i></div>
                        <h4>Will & Legal Basics</h4>
                </article>

                <article class="res-card" data-resource="">
                    <div class="icon-wrap red"><i class="fas fa-barcode"></i></div>
                        <h4>Products & Services</h4>
                </article>

                <article class="res-card" data-resource="">
                    <div class="icon-wrap red"><i class="fa-solid fa-users"></i></div>
                        <h4>Partnership</h4>
                </article>

                <article class="res-card" data-resource="">
                    <div class="icon-wrap red"><i class="fa-solid fa-shield-heart"></i></div>
                        <h4>Platform</h4>
                </article>
            </section>

            <!-- Info Modal Popup -->
            <div id="infoModal" class="modal-overlay" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
                <div class="modal-content" style="background: white; padding: 24px; border-radius: 12px; width: 90%; max-width: 500px; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                        <h3 id="modalTitle" style="margin: 0; font-size: 1.25rem; color: #260000;">Information</h3>
                        <button id="closeModal" style="background: none; border: none; font-size: 1.25rem; cursor: pointer;">&times;</button>
                    </div>
                    <ul id="modalList" style="list-style-type: disc; padding-left: 20px; color: #4b5563; line-height: 1.6;">
                        <!-- Dynamically populated list items -->
                    </ul>
                </div>
            </div>

            <section class="elearning-media" style="margin-top: 40px;">
                <div class="section-divider" style="margin-bottom: 24px;">
                    <h2 style="font-size: 1.25rem; font-weight: 600; color: #333; border-bottom: 2px solid #e5e7eb; padding-bottom: 8px;">More Information</h2>
                </div>

            <div class="accordion-section">
                    <div class="accordion-header" onclick="toggleAccordion(this)">
                        <span class="title"> FAQs</span>
                        <span class="arrow"><i class="fas fa-chevron-down"></i></span>
                    </div>
                    <div class="accordion-body">
                        <p style="padding: 15px; color: #666;">No FAQs available at the moment.</p>
                    </div>
                </div>
        </div>
    </main>
</div>
<?php include __DIR__ . '/../layouts/footer.php'; ?>