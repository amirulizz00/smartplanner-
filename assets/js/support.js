document.addEventListener('DOMContentLoaded', function () {
    const resCards = document.querySelectorAll('.res-card');
    const modal = document.getElementById('infoModal');
    const modalTitle = document.getElementById('modalTitle');
    const modalList = document.getElementById('modalList');
    const closeModal = document.getElementById('closeModal');

    // Sample content database for each category
    const infoData = {
        basics: [
            "Introduction to Estate Planning",
            "What makes a Will legally binding?",
            "Understanding Executors and Beneficiaries"
        ],
        products: [
            "Overview of SmartWills Packages",
            "Updating Your Plan Over Time",
            "Secure Storage & Access"
        ],
        partnership: [
            "Partner Program Benefits",
            "Referral Commissions & Payouts",
            "Co-branded Resources"
        ],
        platform: [
            "Navigating the Dashboard",
            "Account Security & 2FA Setup",
            "Troubleshooting Common Errors"
        ]
    };

    resCards.forEach(card => {
        card.addEventListener('click', function () {
            const category = this.getAttribute('data-category');
            const title = this.getAttribute('data-title');

            modalTitle.textContent = title;
            modalList.innerHTML = '';

            // Populate list items
            const items = infoData[category] || ["No additional reading available."];
            items.forEach(text => {
                const li = document.createElement('li');
                li.style.marginBottom = '8px';
                li.innerHTML = `<a href="#" style="color: #4f46e5; text-decoration: none;">${text}</a>`;
                modalList.appendChild(li);
            });

            // Show modal
            modal.style.display = 'flex';
        });
    });

    // Close modal handlers
    closeModal.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    window.addEventListener('click', (event) => {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
});