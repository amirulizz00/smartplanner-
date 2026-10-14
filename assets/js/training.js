function toggleAccordion(header) {
    const body = header.nextElementSibling;
    const arrow = header.querySelector('.arrow');
    const isOpen = body.classList.contains('open');
    if (isOpen) {
        body.classList.remove('open');
        if (arrow) arrow.classList.remove('open');
    } else {
        body.classList.add('open');
        if (arrow) arrow.classList.add('open');
    }
}

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.res-card').forEach(function (card) {
        card.addEventListener('click', function (e) {
            const resourceName = card.getAttribute('data-resource');
            if (resourceName && resourceName.trim() !== '' && !e.target.closest('a')) {
                window.location.href = `resources/${resourceName}.php`;
            }
        });
    });
s
    document.querySelectorAll('.accordion-header').forEach(function (header) {
        header.addEventListener('click', function () {
            toggleAccordion(header);
        });
    });
});
