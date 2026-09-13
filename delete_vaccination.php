<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: vaccinations.php');
    exit;
}

$vaccination_id = filter_var($_POST['vaccination_id'] ?? null, FILTER_VALIDATE_INT);
if (!$vaccination_id || $vaccination_id < 1) {
    header('Location: vaccinations.php?error=' . urlencode('Invalid vaccination ID.'));
    exit;
}

$stmt = mysqli_prepare($conn, 'DELETE FROM vaccinations WHERE vaccination_id = ?');
if (!$stmt) {
    mysqli_close($conn);
    header('Location: vaccinations.php?error=' . urlencode('Unable to prepare delete request.'));
    exit;
}

mysqli_stmt_bind_param($stmt, 'i', $vaccination_id);
if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    header('Location: vaccinations.php?message=' . urlencode('Vaccination record deleted successfully.'));
    exit;
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
header('Location: vaccinations.php?error=' . urlencode('Unable to delete the vaccination record.'));
exit;
