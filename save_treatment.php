<?php

require 'config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $animal_id = $_POST['animal_id'] ?? '';
    $vet_id = $_POST['vet_id'] ?? '';
    $treatment_date = $_POST['treatment_date'] ?? '';
    $diagnosis = trim($_POST['diagnosis'] ?? '');
    $treatment_description = trim($_POST['treatment_description'] ?? '');
    $treatment_status = $_POST['treatment_status'] ?? '';

    // Server-side validation
    if (
        $animal_id === '' ||
        $vet_id === '' ||
        $treatment_date === '' ||
        $diagnosis === '' ||
        $treatment_status === ''
    ) {
        die("Please fill all required fields.");
    }

    if (!is_numeric($animal_id) || !is_numeric($vet_id)) {
        die("Invalid animal or veterinarian.");
    }

    if (!in_array($treatment_status, ['Ongoing', 'Completed'], true)) {
        die("Invalid treatment status.");
    }

    $animal_id = (int)$animal_id;
    $vet_id = (int)$vet_id;

    /*
     * Prepared INSERT statement
     * DO NOT remove the ? placeholders.
     */
    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO treatments
        (animal_id, vet_id, treatment_date, diagnosis, treatment_description, treatment_status)
        VALUES (?, ?, ?, ?, ?, ?)"
    );

    if (!$stmt) {
        die("Unable to process request.");
    }

    mysqli_stmt_bind_param(
        $stmt,
        "iissss",
        $animal_id,
        $vet_id,
        $treatment_date,
        $diagnosis,
        $treatment_description,
        $treatment_status
    );

    if (mysqli_stmt_execute($stmt)) {

        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        header("Location: treatments.php?message=" . urlencode("Treatment record added successfully."));
        exit;

    } else {

        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        header("Location: treatments.php?error=" . urlencode("Unable to save treatment record."));
        exit;
    }
}

mysqli_close($conn);

?>