<?php

require_once __DIR__ . '/../includes/bootstrap.php';

smartwills_require_login();

$assetBase = '../assets';
$activePage = 'support';
$pageTitle = 'Submit a Ticket - SmartWills';
$pageStyles = ['resources.css', 'ticket.css'];
$pageScripts = ['ticket.js'];

include __DIR__ . '/../layouts/header.php';
?>
<div class="wrapper">
    <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

    <main class="main-content">
        <?php include __DIR__ . '/../layouts/topbar.php'; ?>

        <div class="content">
            <header class="page-header">
                <h1>
                    <i class="fas fa-headset"></i>
                    Submit a Ticket
                </h1>
            </header>

            <section class="ticket-form-section">
                <form id="ticketForm" method="POST" class="ticket-form">
                    <div class="form-group">
                        <label for="subject">Subject</label>
                        <input type="text" id="subject" name="subject" required>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" rows="5" required></textarea>
                    </div>

                    <!-- No need to input user name and email as session already recorded -->
                    <button type="submit" class="btn-primary">Submit Ticket</button>
                </form>
            </section>
        </div>
    </main>

<?php 

include __DIR__ . '/../layouts/footer.php'; 

?>
