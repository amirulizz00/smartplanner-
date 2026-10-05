<?php
    ob_start();
    session_start();
    
    // Load the bootstrap file from the 'includes' folder
    require_once __DIR__ . '/includes/bootstrap.php';

    $pageTitle = 'SmartWill Planner · Register';
    
    echo '<!DOCTYPE html>';
    echo '<html lang="en" class="h-100">';
    echo '<head>';
    echo '<meta charset="UTF-8">';
    echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
    echo '<title>' . $pageTitle . '</title>';
    echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">';
    echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">';
    // Cloudflare Turnstile API
    echo '<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>';
    echo '</head>';
    
    echo '<body class="d-flex flex-column h-100 bg-light">';
    
    echo '<main class="form-signin w-100 m-auto py-5" style="max-width: 400px;">';
    echo '<form id="register_form" method="POST">';
    echo '<h1 class="h3 mb-3 fw-normal text-center">Create Account</h1>';
    
    echo '<div id="alert_box" class="mb-3"></div>';

    echo '<div class="form-floating mb-3">';
    echo '<input type="text" class="form-content form-control" id="name" name="name" placeholder="Full Name" required>';
    echo '<label for="name" class="text-dark">Full Name</label>';
    echo '</div>';

    echo '<div class="form-floating mb-3">';
    echo '<input type="email" class="form-content form-control" id="email" name="email" placeholder="name@example.com" required>';
    echo '<label for="email" class="text-dark">Email address</label>';
    echo '</div>';

    echo '<div class="form-floating mb-3">';
    echo '<input type="password" class="form-content form-control" id="password" name="password" placeholder="Password" required>';
    echo '<label for="password" class="text-dark">Password</label>';
    echo '</div>';

    // Cloudflare Turnstile Widget
    echo '<div class="cf-turnstile mb-3 d-flex justify-content-center" data-sitekey="' . (defined('TURNSTILE_SITE_KEY') ? TURNSTILE_SITE_KEY : '0x4AAAAAAFC7HpyFfPXJIZA') . '"></div>';

    echo '<button class="w-100 btn btn-lg btn-danger mb-3" type="submit">Register</button>';
    
    // Divider
    echo '<div class="position-relative my-4">';
    echo '<hr class="text-muted">';
    echo '<span class="position-absolute top-50 start-50 translate-middle px-3 bg-light text-muted small">or continue with</span>';
    echo '</div>';

    // Social Buttons
    echo '<div class="d-grid gap-2 mb-3">';
    echo '<a href="controller/auth.php?provider=google" class="btn btn-outline-dark text-dark border"><i class="fab fa-google me-2 text-danger"></i> Register with Google</a>';
    echo '<a href="controller/auth.php?provider=facebook" class="btn btn-outline-primary"><i class="fab fa-facebook-f me-2"></i> Register with Facebook</a>';
    echo '</div>';

    echo '<p class="text-center">Already have an account? <a href="login.php">Sign in</a></p>';
    
    echo '</form>';
    echo '</main>';

    echo '<script src="script/auth.js"></script>';
    echo '</body>';
    echo '</html>';
?>