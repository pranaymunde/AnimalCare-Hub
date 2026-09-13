<?php

require 'config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $animal_id = $_POST['animal_id'] ?? '';
    $vaccine_name = trim($_POST['vaccine_name'] ?? '');
    $vaccination_date = $_POST['vaccination_date'] ?? '';
    $next_due_date = $_POST['next_due_date'] ?? '';
    $vaccination_status = $_POST['vaccination_status'] ?? '';

    // Server-side validation
    if (
        $animal_id === '' ||
        $vaccine_name === '' ||
        $vaccination_date === '' ||
        $vaccination_status === ''
    ) {
        die("Please fill all required fields.");
    }

    if (!is_numeric($animal_id)) {
        die("Invalid animal selected.");
    }

    if (!in_array(
        $vaccination_status,
        ['Completed', 'Due Soon', 'Pending'],
        true
    )) {
        die("Invalid vaccination status.");
    }

    $animal_id = (int)$animal_id;

    /*
     * Prepared INSERT statement.
     * Keep the ? placeholders for secure SQL.
     */
    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO vaccinations
        (animal_id, vaccine_name, vaccination_date,
         next_due_date, vaccination_status)
        VALUES (?, ?, ?, ?, ?)"
    );

    if (!$stmt) {
        die("Unable to process request.");
    }

    mysqli_stmt_bind_param(
        $stmt,
        "issss",
        $animal_id,
        $vaccine_name,
        $vaccination_date,
        $next_due_date,
        $vaccination_status
    );

    if (mysqli_stmt_execute($stmt)) {

        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        header("Location: vaccinations.php?message=" . urlencode("Vaccination record added successfully."));
        exit;

    } else {

        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        header("Location: vaccinations.php?error=" . urlencode("Unable to save vaccination record."));
        exit;
    }
}

mysqli_close($conn);

?>