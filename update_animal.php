<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: animals.php');
    exit;
}

$animal_id = filter_var($_POST['animal_id'] ?? null, FILTER_VALIDATE_INT);
$animal_name = trim($_POST['animal_name'] ?? '');
$animal_type = trim($_POST['animal_type'] ?? '');
$breed = trim($_POST['breed'] ?? '');
$age = filter_var($_POST['age'] ?? null, FILTER_VALIDATE_INT);
$gender = trim($_POST['gender'] ?? '');
$category = trim($_POST['category'] ?? '');
$rescue_date = trim($_POST['rescue_date'] ?? '');
$health_status = trim($_POST['health_status'] ?? '');
$adoption_status = trim($_POST['adoption_status'] ?? '');
$description = trim($_POST['description'] ?? '');

if (!$animal_id || $animal_id < 1 || $animal_name === '' || $animal_type === '' ||
    $age === false || $age < 0 || $age > 100 || $gender === '' ||
    $category === '' || $health_status === '' || $adoption_status === '') {
    header('Location: animals.php?error=' . urlencode('Please enter valid values in all required fields.'));
    exit;
}

$sql = 'UPDATE animals
        SET animal_name = ?, animal_type = ?, breed = ?, age = ?, gender = ?,
            category = ?, rescue_date = ?, health_status = ?, adoption_status = ?,
            description = ?
        WHERE animal_id = ?';

$stmt = mysqli_prepare($conn, $sql);
if (!$stmt) {
    header('Location: animals.php?error=' . urlencode('Unable to prepare the update operation.'));
    exit;
}

// 10 text/date values + 1 integer ID.
mysqli_stmt_bind_param(
    $stmt,
    'sssissssssi',
    $animal_name,
    $animal_type,
    $breed,
    $age,
    $gender,
    $category,
    $rescue_date,
    $health_status,
    $adoption_status,
    $description,
    $animal_id
);

if (mysqli_stmt_execute($stmt)) {
    $changed = mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    $msg = $changed >= 0 ? 'Animal record updated successfully.' : 'Animal record processed successfully.';
    header('Location: animals.php?message=' . urlencode($msg));
    exit;
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
header('Location: animals.php?error=' . urlencode('Unable to update animal record.'));
exit;
