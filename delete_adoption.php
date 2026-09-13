<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: adoption.php');
    exit;
}

$request_id = filter_var($_POST['request_id'] ?? null, FILTER_VALIDATE_INT);
if (!$request_id || $request_id < 1) {
    header('Location: adoption.php?error=' . urlencode('Invalid adoption request ID.'));
    exit;
}

$stmt = mysqli_prepare($conn, 'DELETE FROM adoption_requests WHERE request_id = ?');
if (!$stmt) {
    mysqli_close($conn);
    header('Location: adoption.php?error=' . urlencode('Unable to prepare delete request.'));
    exit;
}

mysqli_stmt_bind_param($stmt, 'i', $request_id);
if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    header('Location: adoption.php?message=' . urlencode('Adoption request deleted successfully.'));
    exit;
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
header('Location: adoption.php?error=' . urlencode('Unable to delete the adoption request.'));
exit;
