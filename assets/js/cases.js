function filterCases() {
    const keyword = document.getElementById('caseSearchInput').value.trim().toLowerCase();
    const statusFilter = document.getElementById('filterStatus').value;
    const rows = document.querySelectorAll('#caseTableBody tr[data-case-row]');

    rows.forEach((row) => {
        const matchesKeyword = row.textContent.toLowerCase().includes(keyword);
        const matchesStatus = statusFilter === 'all' || row.dataset.status === statusFilter;
        row.style.display = matchesKeyword && matchesStatus ? '' : 'none';
    });
}


//search and filter functionality
document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('caseSearchInput').addEventListener('input', filterCases);
    document.getElementById('filterStatus').addEventListener('change', filterCases);
});