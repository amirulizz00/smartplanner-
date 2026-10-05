<?php
require_once __DIR__ . '/../includes/bootstrap.php';

$pageTitle = $pageTitle ?? 'SmartWills Planner';
// FIXED: Hardcoded relative path to ensure CSS and images load correctly
$assetBase = 'assets'; 
$pageStyles = $pageStyles ?? [];
$pageScripts = $pageScripts ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <link rel="stylesheet" href="<?php echo htmlspecialchars($assetBase); ?>/css/global.css">
    <link rel="stylesheet" href="<?php echo htmlspecialchars($assetBase); ?>/css/sidebar.css">
    <link rel="stylesheet" href="<?php echo htmlspecialchars($assetBase); ?>/css/topbar.css">
    <link rel="stylesheet" href="<?php echo htmlspecialchars($assetBase); ?>/css/components.css">
    <?php foreach ($pageStyles as $stylesheet): ?>
        <link rel="stylesheet" href="<?php echo htmlspecialchars($assetBase . '/css/' . $stylesheet); ?>">
    <?php endforeach; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body></body></body>