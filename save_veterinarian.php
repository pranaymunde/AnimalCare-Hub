<?php

require 'config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $vet_name = trim($_POST['vet_name'] ?? '');
    $specialization = trim($_POST['specialization'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $clinic_name = trim($_POST['clinic_name'] ?? '');

    // Server-side validation
    if ($vet_name === '' || $phone === '') {
        die("Please fill all required fields.");
    }

    if (!preg_match("/^[A-Za-z .]+$/", $vet_name)) {
        die("Invalid veterinarian name.");
    }

    if (!preg_match("/^[0-9+ ()-]{10,20}$/", $phone)) {
        die("Invalid phone number.");
    }

    if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email address.");
    }

    // Prepared INSERT statement
    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO veterinarians
        (vet_name, specialization, phone, email, clinic_name)
        VALUES (?, ?, ?, ?, ?)"
    );

    if (!$stmt) {
        die("Unable to process request.");
    }

    // Keep ? placeholders and parameter binding
    mysqli_stmt_bind_param(
        $stmt,
        "sssss",
        $vet_name,
        $specialization,
        $phone,
        $email,
        $clinic_name
    );

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        header("Location: veterinarians.php?message=" . urlencode("Veterinarian record added successfully."));
        exit;
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    header("Location: veterinarians.php?error=" . urlencode("Unable to save veterinarian record."));
    exit;
}

mysqli_close($conn);

?>