<?php

define('SMARTWILLS_APP_ROOT', dirname(__DIR__));

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database Configuration & Connection (Zelozz style wrapper)
$db_host = "localhost";
$db_port = "3306"; // Matching your MAMP/WAMP port
$db_user = "amirul";
$db_pass = "123";
$db_name = "smartwillsplanner";

// OAuth Configuration
define('GOOGLE_CLIENT_ID', '1042980156775-jpk14m71ptb9va813b4d2ijglj25jl4u.apps.googleusercontent.com');
define('GOOGLE_CLIENT_SECRET', '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');
define('GOOGLE_REDIRECT_URI', 'http://localhost:8888/smartwills%20planner/smartwillplanner-main/controller/auth.php?provider=google');

define('FB_APP_ID', '3189825431210554');
define('FB_APP_SECRET', '550c9cf992da524ccd9c31b41c953674');
define('FB_REDIRECT_URI', 'http://localhost:8888/smartwills%20planner/smartwillplanner-main/controller/auth.php?provider=facebook');

// Cloudflare Turnstile Keys
define('TURNSTILE_SITE_KEY', '0x4AAAAAAFC7ThpyFfPXJlZA');
define('TURNSTILE_SECRET_KEY', '0x4AAAAAAFC7Tq0kREyAA8F1ygCjNuWm42A');
class SmartWillsDB {
    private $connection;
    public $num_rows = 0;
    public $rows = [];

    public function __construct($host, $port, $user, $pass, $name) {
        $this->connection = new mysqli($host, $user, $pass, $name, (int)$port);
        if ($this->connection->connect_error) {
            die("Database Connection Failed: " . $this->connection->connect_error);
        }
        $this->connection->set_charset("utf8mb4");
    }

    public function query($sql) {
        $result = $this->connection->query($sql);
        
        if ($result === false) {
            return false;
        }

        if ($result === true) {
            return true;
        }

        $this->rows = [];
        while ($row = $result->fetch_assoc()) {
            $this->rows[] = $row;
        }
        $this->num_rows = count($this->rows);
        
        // Return a lightweight object mimicking custom query results
        return $this;
    }

    public function escape($value) {
        return $this->connection->real_escape_string($value);
    }
}

// Initialize the global $db instance
$db = new SmartWillsDB($db_host, $db_port, $db_user, $db_pass, $db_name);

function smartwills_require_login(): void
{
    // Updated to match your portal session structure
    if (!isset($_SESSION["portal"]["user"]) || empty($_SESSION["portal"]["user"]["id"])) {
        header('Location: /login.php');
        exit;
    }
}

function smartwills_asset_base(): string
{
    static $assetBase = null;

    if ($assetBase !== null) {
        return $assetBase;
    }

    $assetBase = '/assets';

    if (isset($_SERVER['SCRIPT_NAME'])) {
        $currentScript = $_SERVER['SCRIPT_NAME'];
        if (str_contains($currentScript, '/clients/')) {
            $assetBase = '../assets';
        } elseif (str_contains($currentScript, '/education/')) {
            $assetBase = '../assets';
        }
    }

    return $assetBase;
}

function smartwills_page_meta(string $page, string $title, array $styles = [], array $scripts = []): array
{
    return [
        'activePage' => $page,
        'pageTitle' => $title,
        'pageStyles' => $styles,
        'pageScripts' => $scripts,
        'assetBase' => smartwills_asset_base(),
    ];
}