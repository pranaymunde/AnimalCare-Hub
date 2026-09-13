<?php

require 'config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $animal_id = $_POST['animal_id'] ?? '';
    $applicant_name = trim($_POST['applicant_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $request_date = $_POST['request_date'] ?? '';
    $reason = trim($_POST['reason'] ?? '');
    $request_status = $_POST['request_status'] ?? 'Pending';

    // Server-side validation
    if (
        $animal_id === '' ||
        $applicant_name === '' ||
        $email === '' ||
        $phone === '' ||
        $city === '' ||
        $request_date === '' ||
        $request_status === ''
    ) {
        die("Please fill all required fields.");
    }

    if (!is_numeric($animal_id)) {
        die("Invalid animal selected.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email address.");
    }

    if (!preg_match("/^[0-9+ ()-]{10,20}$/", $phone)) {
        die("Invalid phone number.");
    }

    if (!preg_match("/^[A-Za-z .]+$/", $applicant_name)) {
        die("Invalid applicant name.");
    }

    if (!in_array(
        $request_status,
        ['Pending', 'Approved', 'Rejected'],
        true
    )) {
        die("Invalid request status.");
    }

    $animal_id = (int)$animal_id;

    /*
     * Prepared INSERT statement.
     * Keep the ? placeholders.
     */
    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO adoption_requests
        (animal_id, applicant_name, email, phone, city,
         request_date, reason, request_status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
    );

    if (!$stmt) {
        die("Unable to process request.");
    }

    mysqli_stmt_bind_param(
        $stmt,
        "isssssss",
        $animal_id,
        $applicant_name,
        $email,
        $phone,
        $city,
        $request_date,
        $reason,
        $request_status
    );

    if (mysqli_stmt_execute($stmt)) {

        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        header("Location: adoption.php?message=" . urlencode("Adoption request added successfully."));
        exit;

    } else {

        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        header("Location: adoption.php?error=" . urlencode("Unable to save adoption request."));
        exit;
    }
}

mysqli_close($conn);

?>