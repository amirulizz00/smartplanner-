document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.res-card').forEach(function (card) {
        card.addEventListener('click', function () {
            const resourceName = card.getAttribute('data-resource');
            window.location.href = `resources/${resourceName}.php`;
            
        });
    });

    const supportButton = document.getElementById('supportBtn');

    if (supportButton) {
        supportButton.addEventListener('click', function () {
            window.location.href = 'resources/ticket.php';
        });
    }
});
