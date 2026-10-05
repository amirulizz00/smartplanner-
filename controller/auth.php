<?php
ob_start();
session_start();

require_once __DIR__ . '/../includes/bootstrap.php';

// Helper function to verify Cloudflare Turnstile token
function verifyTurnstile($token) {
    if (empty($token)) {
        return false;
    }

    $secretKey = defined('TURNSTILE_SECRET_KEY') ? TURNSTILE_SECRET_KEY : '';
    if (empty($secretKey)) {
        return true; // Bypass check if key is not configured locally
    }

    $verifyUrl = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';
    $postData = [
        'secret'   => $secretKey,
        'response' => $token,
        'remoteip' => $_SERVER['REMOTE_ADDR'] ?? ''
    ];

    $ch = curl_init($verifyUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
    $response = curl_exec($ch);

    $outcome = json_decode($response, true);
    return !empty($outcome['success']);
}

// Helper to persist/login social accounts
function handleSocialLogin($db, $provider, $oauthId, $email, $name) {
    $escapedEmail    = $db->escape($email);
    $escapedOAuthId  = $db->escape($oauthId);
    $escapedName     = $db->escape($name);
    $escapedProvider = $db->escape($provider);

    // 1. Check if user already exists by OAuth ID and provider
    $existing = $db->query("SELECT * FROM users WHERE oauth_provider = '$escapedProvider' AND oauth_id = '$escapedOAuthId' LIMIT 1");

    if ($existing && $existing->num_rows > 0) {
        $user = $existing->rows[0];
    } else {
        // 2. Check if an account already exists with the same email
        $byEmail = $db->query("SELECT * FROM users WHERE email = '$escapedEmail' LIMIT 1");

        if ($byEmail && $byEmail->num_rows > 0) {
            $user = $byEmail->rows[0];
            // Link existing account with social provider using oauth_id
            $userId = (int)$user['id'];
            $db->query("UPDATE users SET oauth_provider = '$escapedProvider', oauth_id = '$escapedOAuthId' WHERE id = $userId");
        } else {
            // 3. Register as a new user with oauth_id and active status
            $insertQuery = "INSERT INTO users (name, email, password, oauth_provider, oauth_id, active, created_at) 
                            VALUES ('$escapedName', '$escapedEmail', NULL, '$escapedProvider', '$escapedOAuthId', 1, NOW())";
            $db->query($insertQuery);

            $fetchNew = $db->query("SELECT * FROM users WHERE oauth_provider = '$escapedProvider' AND oauth_id = '$escapedOAuthId' LIMIT 1");
            $user = ($fetchNew && $fetchNew->num_rows > 0) ? $fetchNew->rows[0] : ['name' => $name, 'email' => $email];
        }
    }

    // Set user session matching both standard and portal requirements
    $_SESSION['user_id'] = $user['id'] ?? null;
    $_SESSION['user_name'] = $user['name'] ?? $name;
    $_SESSION['user_email'] = $user['email'] ?? $email;
    $_SESSION['portal']['user'] = [
        'id'    => $user['id'] ?? null,
        'name'  => $user['name'] ?? $name,
        'email' => $user['email'] ?? $email
    ];

    header('Location: ../index.php');
    exit;
}

// -------------------------------------------------------------
// 1. AJAX AUTHENTICATION ENDPOINTS (Register & Login)
// -------------------------------------------------------------
if (isset($_GET['type'])) {
    header('Content-Type: application/json');

    // Registration Handler
    if ($_GET['type'] === 'register') {
        // Cloudflare Turnstile Bot Verification
        $turnstileToken = $_POST['cf-turnstile-response'] ?? '';
        if (!verifyTurnstile($turnstileToken)) {
            echo json_encode(['error' => 'Bot challenge verification failed. Please verify you are human and try again.']);
            exit;
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($name) || empty($email) || empty($password)) {
            echo json_encode(['error' => 'Please fill in all required fields.']);
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['error' => 'Please provide a valid email address.']);
            exit;
        }

        $escapedEmail = $db->escape($email);
        $check = $db->query("SELECT id FROM users WHERE email = '$escapedEmail' LIMIT 1");
        if ($check && $check->num_rows > 0) {
            echo json_encode(['error' => 'An account with this email address already exists.']);
            exit;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $escapedName = $db->escape($name);

        $inserted = $db->query("INSERT INTO users (name, email, password, oauth_provider, active, created_at) 
                                VALUES ('$escapedName', '$escapedEmail', '$hashedPassword', 'local', 1, NOW())");

        if ($inserted) {
            echo json_encode(['success' => 'Account created successfully! You can now log in.']);
        } else {
            echo json_encode(['error' => 'Failed to create account. Please try again.']);
        }
        exit;
    }

    // Standard Login Handler
    if ($_GET['type'] === 'login') {
        // Cloudflare Turnstile Bot Verification for Login
        $turnstileToken = $_POST['cf-turnstile-response'] ?? '';
        if (!verifyTurnstile($turnstileToken)) {
            echo json_encode(['error' => 'Bot challenge verification failed. Please verify you are human and try again.']);
            exit;
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            echo json_encode(['error' => 'Please enter both your email and password.']);
            exit;
        }

        $escapedEmail = $db->escape($email);
        /** @var SmartWillsDB $result */
        $result = $db->query("SELECT * FROM users WHERE email = '$escapedEmail' LIMIT 1");

        if ($result && $result->num_rows > 0) {
            $user = $result->rows[0];

            if (!empty($user['password']) && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['portal']['user'] = [
                    'id'    => $user['id'],
                    'name'  => $user['name'],
                    'email' => $user['email']
                ];

                echo json_encode(['success' => true]);
                exit;
            }
        }

        echo json_encode(['error' => 'Invalid email or password combination.']);
        exit;
    }

    // Logout Handler
    if ($_GET['type'] === 'logout') {
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
        header('Location: ../login.php');
        exit;
    }
}

// -------------------------------------------------------------
// 2. SOCIAL OAUTH FLOWS (Google & Facebook)
// -------------------------------------------------------------
if (isset($_GET['provider'])) {
    $provider = strtolower($_GET['provider']);

    // --- Google OAuth ---
    if ($provider === 'google') {
        if (!isset($_GET['code'])) {
            $params = [
                'client_id'     => GOOGLE_CLIENT_ID,
                'redirect_uri'  => GOOGLE_REDIRECT_URI,
                'response_type' => 'code',
                'scope'         => 'openid profile email',
                'access_type'   => 'online',
                'prompt'        => 'select_account'
            ];
            header('Location: https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query($params));
            exit;
        }

        $tokenUrl = 'https://oauth2.googleapis.com/token';
        $postData = [
            'code'          => $_GET['code'],
            'client_id'     => GOOGLE_CLIENT_ID,
            'client_secret' => GOOGLE_CLIENT_SECRET,
            'redirect_uri'  => GOOGLE_REDIRECT_URI,
            'grant_type'    => 'authorization_code'
        ];

        $ch = curl_init($tokenUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        $res = json_decode(curl_exec($ch), true);

        if (!empty($res['access_token'])) {
            $userUrl = 'https://www.googleapis.com/oauth2/v3/userinfo';
            $chUser = curl_init($userUrl);
            curl_setopt($chUser, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($chUser, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $res['access_token']]);
            $profile = json_decode(curl_exec($chUser), true);

            if (!empty($profile['sub'])) {
                $gOAuthId = $profile['sub'];
                $gEmail   = $profile['email'] ?? ($gOAuthId . '@google.local');
                $gName    = $profile['name'] ?? 'Google User';

                handleSocialLogin($db, 'google', $gOAuthId, $gEmail, $gName);
            }
        }

        header('Location: ../login.php?error=google_failed');
        exit;
    }

    // --- Facebook OAuth ---
    if ($provider === 'facebook') {
        if (!isset($_GET['code'])) {
            $dialogUrl = 'https://www.facebook.com/v18.0/dialog/oauth?' . http_build_query([
                'client_id'     => FB_APP_ID,
                'redirect_uri'  => FB_REDIRECT_URI,
                'scope'         => 'email,public_profile'
            ]);
            header('Location: ' . $dialogUrl);
            exit;
        }

        $tokenUrl = 'https://graph.facebook.com/v18.0/oauth/access_token?' . http_build_query([
            'client_id'     => FB_APP_ID,
            'client_secret' => FB_APP_SECRET,
            'redirect_uri'  => FB_REDIRECT_URI,
            'code'          => $_GET['code']
        ]);

        $ch = curl_init($tokenUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = json_decode(curl_exec($ch), true);

        if (!empty($res['access_token'])) {
            $userUrl = 'https://graph.facebook.com/v18.0/me?fields=id,name,email&access_token=' . $res['access_token'];
            $chUser = curl_init($userUrl);
            curl_setopt($chUser, CURLOPT_RETURNTRANSFER, true);
            $profile = json_decode(curl_exec($chUser), true);

            if (!empty($profile['id'])) {
                $fbOAuthId = $profile['id'];
                $fbEmail   = $profile['email'] ?? ($fbOAuthId . '@facebook.local');
                $fbName    = $profile['name'] ?? 'Facebook User';

                handleSocialLogin($db, 'facebook', $fbOAuthId, $fbEmail, $fbName);
            }
        }

        header('Location: ../login.php?error=facebook_failed');
        exit;
    }
}

// Fallback redirect if accessed directly
header('Location: ../login.php');
exit;