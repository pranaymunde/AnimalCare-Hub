<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: veterinarians.php');
    exit;
}

$vet_id = filter_var($_POST['vet_id'] ?? null, FILTER_VALIDATE_INT);
if (!$vet_id || $vet_id < 1) {
    header('Location: veterinarians.php?error=' . urlencode('Invalid veterinarian ID.'));
    exit;
}

mysqli_begin_transaction($conn);

try {
    $deleteTreatments = mysqli_prepare($conn, 'DELETE FROM treatments WHERE vet_id = ?');
    if (!$deleteTreatments) {
        throw new Exception('prepare');
    }
    mysqli_stmt_bind_param($deleteTreatments, 'i', $vet_id);
    if (!mysqli_stmt_execute($deleteTreatments)) {
        mysqli_stmt_close($deleteTreatments);
        throw new Exception('execute');
    }
    mysqli_stmt_close($deleteTreatments);

    $deleteVet = mysqli_prepare($conn, 'DELETE FROM veterinarians WHERE vet_id = ?');
    if (!$deleteVet) {
        throw new Exception('prepare');
    }
    mysqli_stmt_bind_param($deleteVet, 'i', $vet_id);
    if (!mysqli_stmt_execute($deleteVet)) {
        mysqli_stmt_close($deleteVet);
        throw new Exception('execute');
    }
    mysqli_stmt_close($deleteVet);

    mysqli_commit($conn);
    mysqli_close($conn);
    header('Location: veterinarians.php?message=' . urlencode('Veterinarian and linked treatment records were deleted successfully.'));
    exit;
} catch (Throwable $e) {
    mysqli_rollback($conn);
    mysqli_close($conn);
    header('Location: veterinarians.php?error=' . urlencode('Unable to delete the veterinarian record.'));
    exit;
}
