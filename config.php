<?php
// Configuration paths
define('DATA_DIR', __DIR__ . '/data/');
define('UPLOAD_DIR', __DIR__ . '/public/uploads/');
define('MEDIA_DIR', __DIR__ . '/media/');

// Ensure directories exist and are writable
$dirs = [DATA_DIR, UPLOAD_DIR, MEDIA_DIR];
foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
}

// Helper functions for data management
function getData($filename) {
    $path = DATA_DIR . $filename . '.json';
    if (!file_exists($path)) {
        return [];
    }
    return json_decode(file_get_contents($path), true);
}

function saveData($filename, $data) {
    $path = DATA_DIR . $filename . '.json';
    return file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT));
}

// Authentication check
session_start();
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: /login.php');
        exit;
    }
}
?>
