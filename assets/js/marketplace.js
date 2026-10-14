document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.res-card').forEach(function (card) {
        card.addEventListener('click', function () {
            const resourceName = card.getAttribute('data-resource');
            window.location.href = `resources/${resourceName}.php`;
        });
    });

    const cartButton = document.getElementById('cartBtn');

    if (cartButton) {
        cartButton.addEventListener('click', function () {
            alert('Your Cart (demo)');
        });
    }
});
