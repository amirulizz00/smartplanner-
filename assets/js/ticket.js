document.addEventListener('DOMContentLoaded', function () {
    const ticketForm = document.getElementById('ticketForm');

    if (!ticketForm) return;

    ticketForm.addEventListener('submit', function (event) { //fetches submission
        event.preventDefault();

        const formData = new FormData(ticketForm);
        const subject = formData.get('subject');
        const description = formData.get('description');

        alert("Ticket submitted successfully! (Placeholder mode)");

        setTimeout(() => {
            alert('Ticket submitted successfully! (Placeholder mode)');
            ticketForm.reset();
        }, 300);
    });
});