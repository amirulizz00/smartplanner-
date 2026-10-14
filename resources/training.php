<?php
$assetBase = '../assets';

require_once __DIR__ . '/../includes/bootstrap.php';

smartwills_require_login();

$activePage = 'resources';
$pageTitle = 'Training Resources - SmartWills';
$pageStyles = ['resources.css', 'fundinggap.css'];
$pageScripts = ['training.js'];

include __DIR__ . '/../layouts/header.php';
?>
<div class="wrapper">
    <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

    <main class="main-content">
        <?php include __DIR__ . '/../layouts/topbar.php'; ?>

        <div class="content">
            <header class="page-header">
                <h1>
                    <i class="fas fa-video" style="color: purple"></i>
                    Training Resources
                </h1>
            </header>

            <section class="training-resources">
                <div class="section-divider" style="margin-bottom: 24px;">
                    <h2 style="font-size: 1.25rem; font-weight: 600; color: #333; border-bottom: 2px solid #e5e7eb; padding-bottom: 8px;">SmartWills Malaysia Training</h2>
                </div>

                <section class="resource-grid" aria-label="Resource categories">
                    <article class="res-card" data-resource="">
                        <div class="icon-wrap purple"><i class="fas fa-pen"></i></div>
                        <h4>Practice Will (Training Mode)</h4>
                        <div class="meta">
                            <a href="https://sandbox.mysmartwills.com/package/?service=will&session_id=TRGBFJ3BFQXTGPJN" class="badge">Start</a>
                        </div>
                    </article>

                    <article class="res-card" data-resource="">
                        <div class="icon-wrap purple"><i class="fas fa-pen"></i></div>
                        <h4>Practice SmartGP (Training Mode)</h4>
                        <div class="meta">
                            <a href="https://sandbox.mysmartwills.com/details/?service=grant_of_probate&session_id=S6S743YLGWENMBJ6" class="badge">Start</a>
                        </div>
                    </article>


                    <article class="res-card" data-resource="">
                        <div class="icon-wrap purple"><i class="fas fa-pen"></i></div>
                        <h4>Practice SmartLA (Training Mode)</h4>
                        <div class="meta">
                            <a href="https://sandbox.mysmartwills.com/details/?service=letters_of_administration&session_id=E6F8DCAMUJ2T27TZ" class="badge">Start</a>
                        </div>
                    </article>
                </section>
            </section>

            <section class="elearning-media" style="margin-top: 40px;">
                <div class="section-divider" style="margin-bottom: 24px;">
                    <h2 style="font-size: 1.25rem; font-weight: 600; color: #333; border-bottom: 2px solid #e5e7eb; padding-bottom: 8px;">SmartWills Malaysia eLearning Media</h2>
                </div>

                <div class="accordion-section">
                    <div class="accordion-header" onclick="toggleAccordion(this)">
                        <span class="title"> eLearning Video Modules</span>
                        <span class="arrow"><i class="fas fa-chevron-down"></i></span>
                    </div>
                    <div class="accordion-body">
                        <p style="padding: 15px; color: #666;">No eLearning videos available at the moment.</p>
                    </div>
                </div>

                <div class="accordion-section">
                    <div class="accordion-header" onclick="toggleAccordion(this)">
                        <span class="title"> Portal Training</span>
                        <span class="arrow"><i class="fas fa-chevron-down"></i></span>
                    </div>
                    <div class="accordion-body">
                        <p style="padding: 15px; color: #666;">No eLearning videos available at the moment.</p>
                    </div>
                </div>

            </section>
        </div>
    </main>
</div>
<?php include __DIR__ . '/../layouts/footer.php'; ?>

        