<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: treatments.php');
    exit;
}

$treatment_id = filter_var($_POST['treatment_id'] ?? null, FILTER_VALIDATE_INT);
if (!$treatment_id || $treatment_id < 1) {
    header('Location: treatments.php?error=' . urlencode('Invalid treatment ID.'));
    exit;
}

$stmt = mysqli_prepare($conn, 'DELETE FROM treatments WHERE treatment_id = ?');
if (!$stmt) {
    mysqli_close($conn);
    header('Location: treatments.php?error=' . urlencode('Unable to prepare delete request.'));
    exit;
}

mysqli_stmt_bind_param($stmt, 'i', $treatment_id);
if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    header('Location: treatments.php?message=' . urlencode('Treatment record deleted successfully.'));
    exit;
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
header('Location: treatments.php?error=' . urlencode('Unable to delete the treatment record.'));
exit;
