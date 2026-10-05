document.addEventListener("DOMContentLoaded", function () {
    // Helper function to safely reset the Turnstile widget
    function resetTurnstile(formElement) {
        if (typeof turnstile !== "undefined") {
            const widget = formElement.querySelector(".cf-turnstile");
            if (widget) {
                turnstile.reset(widget);
            }
        }
    }

    // Handle Registration Form Submission via AJAX
    const registerForm = document.getElementById("register_form");
    if (registerForm) {
        registerForm.addEventListener("submit", function (e) {
            e.preventDefault();
            const formData = new FormData(registerForm);

            fetch("controller/auth.php?type=register", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                const alertBox = document.getElementById("alert_box");
                if (data.error) {
                    alertBox.innerHTML = `<div class="alert alert-danger">${data.error}</div>`;
                    // Reset Turnstile token on validation/submission failure
                    resetTurnstile(registerForm);
                } else if (data.success) {
                    alertBox.innerHTML = `<div class="alert alert-success">${data.success}</div>`;
                    registerForm.reset();
                    // Reset Turnstile for a fresh state
                    resetTurnstile(registerForm);
                }
            })
            .catch(error => {
                console.error("Error:", error);
                resetTurnstile(registerForm);
            });
        });
    }

    // Handle Login Form Submission via AJAX
    const loginForm = document.getElementById("login_form");
    if (loginForm) {
        loginForm.addEventListener("submit", function (e) {
            e.preventDefault();
            const formData = new FormData(loginForm);

            fetch("controller/auth.php?type=login", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                const alertBox = document.getElementById("alert_box");
                if (data.error) {
                    alertBox.innerHTML = `<div class="alert alert-danger">${data.error}</div>`;
                    // Reset Turnstile if login also uses the widget
                    resetTurnstile(loginForm);
                } else if (data.success) {
                    // Redirect to the main dashboard on successful login
                    window.location.href = "index.php";
                }
            })
            .catch(error => {
                console.error("Error:", error);
                resetTurnstile(loginForm);
            });
        });
    }
});