<?php
$activePage = 'tools';
$clientId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$clientName = 'Zhang Wei';
$pageTitle = 'Funding Calculator - SmartWills';
$pageStyles = ['fundinggap.css'];
$pageScripts = ['fundinggap.js'];

include 'layouts/header.php';
?>
<div class="wrapper">
    <?php include 'layouts/sidebar.php'; ?>

    <main class="main-content">
        <?php include 'layouts/topbar.php'; ?>

        <div class="content">
            <div class="funding-wrapper">
                <!-- Header (no progress bar) -->
                <div class="header">
                    <h1><i class="fas fa-calculator"></i> Funding Calculator</h1>
                </div>

                <form method="POST" action="clients/plan/recommendations.php?id=<?=$clientId?>">
                    <input type="hidden" name="client_id" value="<?=$clientId?>">

                    <!-- Part 1: Movable Funds -->
                    <div class="accordion-section">
                        <div class="accordion-header" onclick="toggleAccordion(this)">
                            <span class="title"><i class="fas fa-money-bill-wave"></i> Part 1: Movable Funds</span>
                            <span class="arrow"><i class="fas fa-chevron-down"></i></span>
                        </div>
                        <div class="accordion-body">
                            <div class="row"><label>1. Cash In The Bank</label><input type="number" id="cash" value="20000" step="100" oninput="calc()"><span class="val" id="cashDisp">$ 20,000</span></div>
                            <div class="row"><label>2. Unit Trust or Investments</label><input type="number" id="invest" value="200000" step="100" oninput="calc()"><span class="val" id="investDisp">$ 200,000</span></div>
                            <div class="row"><label>3. Retirement Fund</label><input type="number" id="retire" value="500000" step="100" oninput="calc()"><span class="val" id="retireDisp">$ 500,000</span></div>
                        </div>
                    </div>

                    <!-- Part 2: Insurance Policies -->
                    <div class="accordion-section">
                        <div class="accordion-header" onclick="toggleAccordion(this)">
                            <span class="title"><i class="fas fa-shield-alt"></i> Part 2: Insurance Policies</span>
                            <span class="arrow"><i class="fas fa-chevron-down"></i></span>
                        </div>
                        <div class="accordion-body">
                            <div class="row">
                                <label>Do you have insurance policy?</label>
                                <div class="radio-group">
                                    <label><input type="radio" name="has_insurance" value="Yes" checked onchange="togglePolicies()"> Yes</label>
                                    <label><input type="radio" name="has_insurance" value="No" onchange="togglePolicies()"> No</label>
                                </div>
                            </div>
                            <div id="policiesWrap">
                                <div id="policiesList"></div>
                                <button type="button" class="btn-sm btn-add" onclick="addPolicy()"><i class="fas fa-plus"></i> Add Insurance Policy</button>
                            </div>
                        </div>
                    </div>

                    <!-- Summary -->
                    <div class="insurance-summary-title">Summary of existing insurance coverage</div>
                    <div id="summaryList"></div>

                    <div class="funding-summary-block">
                        <div class="summary-item"><span class="lbl">1. Cash In The Bank</span><span class="val" id="sCash">$ 20,000</span></div>
                        <div class="summary-item"><span class="lbl">2. Unit Trust or Investments</span><span class="val" id="sInvest">$ 200,000</span></div>
                        <div class="summary-item"><span class="lbl">3. Retirement Fund</span><span class="val" id="sRetire">$ 500,000</span></div>
                        <div class="summary-item"><span class="lbl">4. Total Insurance Coverage</span><span class="val" id="sInsurance">$ 1,500,000</span></div>
                        <div class="total-row"><span>Total Amount of Movable Funds:</span><span class="val" id="totalMovable">$ 2,220,000</span></div>
                        <div class="total-row"><span>Total Amount of Estate Fund Needed:</span><span class="val">$ 3,000,000</span></div>
                        <div class="total-row" id="surplusRow"><span>Surplus / Deficit</span><span class="val" id="surplusVal">$ 780,000</span></div>
                    </div>

                    <input type="hidden" name="total_movable" id="totalMovableHidden">
                    <input type="hidden" name="insurance_total" id="insuranceTotalHidden">
                </form>
            </div>
        </div>
    </main>
</div>

<?php include 'layouts/footer.php'; ?>