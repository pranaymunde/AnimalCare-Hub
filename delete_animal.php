<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: animals.php');
    exit;
}

$animal_id = filter_var($_POST['animal_id'] ?? null, FILTER_VALIDATE_INT);
if (!$animal_id || $animal_id < 1) {
    header('Location: animals.php?error=' . urlencode('Invalid animal ID.'));
    exit;
}

mysqli_begin_transaction($conn);

try {
    // Remove dependent rows first so the foreign-key relationships remain valid.
    $queries = [
        'DELETE FROM adoption_requests WHERE animal_id = ?',
        'DELETE FROM vaccinations WHERE animal_id = ?',
        'DELETE FROM treatments WHERE animal_id = ?',
        'DELETE FROM animals WHERE animal_id = ?'
    ];

    foreach ($queries as $sql) {
        $stmt = mysqli_prepare($conn, $sql);
        if (!$stmt) {
            throw new Exception('prepare');
        }
        mysqli_stmt_bind_param($stmt, 'i', $animal_id);
        if (!mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            throw new Exception('execute');
        }
        mysqli_stmt_close($stmt);
    }

    mysqli_commit($conn);
    mysqli_close($conn);
    header('Location: animals.php?message=' . urlencode('Animal and its related records were deleted successfully.'));
    exit;
} catch (Throwable $e) {
    mysqli_rollback($conn);
    mysqli_close($conn);
    header('Location: animals.php?error=' . urlencode('Unable to delete the animal record.'));
    exit;
}
