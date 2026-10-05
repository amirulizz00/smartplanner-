<?php
    // Place this at the top of protected pages right after session_start() and includes
    if (!isset($_SESSION["portal"]["user"]) || empty($_SESSION["portal"]["user"]["id"])) {
        // Redirect unauthorized users back to the login page
        header("Location: login.php");
        exit();
    }
?>