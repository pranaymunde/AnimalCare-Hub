<?php
require '../config.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

$user = [];
if (isset($_SESSION['user_id'])) {
    $user = [
        'authenticated' => true,
        'user_id' => (int)$_SESSION['user_id'],
        'name' => $_SESSION['name'] ?? 'Care Team',
        'email' => $_SESSION['email'] ?? '',
        'role' => $_SESSION['role'] ?? 'Care Team'
    ];
} else {
    $user = [
        'authenticated' => false,
        'user_id' => null,
        'name' => '',
        'email' => '',
        'role' => ''
    ];
}

echo json_encode($user, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
