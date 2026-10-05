/**
 * Clients Page JavaScript
 * 
 * Changes:
 * - "Add Client" button redirects to add_client.php
 * - Risk Analyze redirects to risk_assessment.php
 * - Edit button now redirects to edit_client.php?id=...
 * - Removed modal display logic
 */

function applyClientFilters() {
    const rows = Array.from(document.querySelectorAll('#clientTableBody tr[data-client-row]'));
    const searchInput = document.getElementById('searchInput');
    const riskFilter = document.getElementById('filterRisk');
    const statusFilter = document.getElementById('filterStatus');

    if (!rows.length || !searchInput || !riskFilter || !statusFilter) {
        return;
    }

    const keyword = searchInput.value.trim().toLowerCase();
    const riskValue = riskFilter.value;
    const statusValue = statusFilter.value;

    rows.forEach((row) => {
        const rowText = row.textContent.toLowerCase();
        const rowRisk = (row.dataset.risk || '').toLowerCase();
        const rowStatus = (row.dataset.status || '').toLowerCase();

        const matchesKeyword = !keyword || rowText.includes(keyword);
        const matchesRisk = riskValue === 'all' || rowRisk === riskValue;
        const matchesStatus = statusValue === 'all' || rowStatus === statusValue;

        row.style.display = matchesKeyword && matchesRisk && matchesStatus ? '' : 'none';
    });
}

document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchInput');
    const riskFilter = document.getElementById('filterRisk');
    const statusFilter = document.getElementById('filterStatus');

    if (searchInput) {
        searchInput.addEventListener('input', applyClientFilters);
    }

    if (riskFilter) {
        riskFilter.addEventListener('change', applyClientFilters);
    }

    if (statusFilter) {
        statusFilter.addEventListener('change', applyClientFilters);
    }
});