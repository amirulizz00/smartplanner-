function filterCases() {
    const keyword = document.getElementById('searchInput').value.trim().toLowerCase();
    const statusFilter = document.getElementById('filterStatus').value;
    const rows = document.querySelectorAll('#caseTableBody tr[data-case-row]');

    rows.forEach((row) => {
        const matchesKeyword = row.textContent.toLowerCase().includes(keyword);
        const matchesStatus = statusFilter === 'all' || row.dataset.status === statusFilter;
        row.style.display = matchesKeyword && matchesStatus ? '' : 'none';
    });
}

document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('searchInput').addEventListener('input', filterCases);
    document.getElementById('filterStatus').addEventListener('change', filterCases);
});