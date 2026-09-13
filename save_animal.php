<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: add_animal.php');
    exit;
}

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

$errors = [];
if ($animal_name === '') $errors[] = 'Animal name is required.';
if ($animal_type === '') $errors[] = 'Animal type is required.';
if ($age === false || $age < 0 || $age > 100) $errors[] = 'Age must be between 0 and 100.';
if ($gender === '') $errors[] = 'Gender is required.';
if ($category === '') $errors[] = 'Category is required.';
if ($health_status === '') $errors[] = 'Health status is required.';
if ($adoption_status === '') $adoption_status = 'Available';

if ($errors) {
    header('Location: add_animal.php?error=' . urlencode(implode(' ', $errors)));
    exit;
}

$sql = 'INSERT INTO animals
        (animal_name, animal_type, breed, age, gender, category, rescue_date,
         health_status, adoption_status, description)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';

$stmt = mysqli_prepare($conn, $sql);
if (!$stmt) {
    header('Location: add_animal.php?error=' . urlencode('Unable to prepare the insert operation.'));
    exit;
}

// sssissssss = 10 values: 3 strings, integer age, then 6 strings.
mysqli_stmt_bind_param(
    $stmt,
    'sssissssss',
    $animal_name,
    $animal_type,
    $breed,
    $age,
    $gender,
    $category,
    $rescue_date,
    $health_status,
    $adoption_status,
    $description
);

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    header('Location: animals.php?message=' . urlencode('Animal record added successfully.'));
    exit;
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
header('Location: add_animal.php?error=' . urlencode('Unable to add animal record. Please check the entered data.'));
exit;
