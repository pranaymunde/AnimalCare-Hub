<?php
require 'config.php';

$error = trim($_GET['error'] ?? '');
$message = trim($_GET['message'] ?? '');
$vet_id = filter_var($_GET['vet_id'] ?? ($_POST['vet_id'] ?? null), FILTER_VALIDATE_INT);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vet_id = filter_var($_POST['vet_id'] ?? null, FILTER_VALIDATE_INT);
    $vet_name = trim($_POST['vet_name'] ?? '');
    $specialization = trim($_POST['specialization'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $clinic_name = trim($_POST['clinic_name'] ?? '');

    if (!$vet_id || $vet_id < 1 || $vet_name === '' || $phone === '') {
        mysqli_close($conn);
        header('Location: update_veterinarian.php?vet_id=' . (int)$vet_id . '&error=' . urlencode('Please fill all required fields.'));
        exit;
    }

    if (!preg_match('/^[A-Za-z .]+$/', $vet_name)) {
        mysqli_close($conn);
        header('Location: update_veterinarian.php?vet_id=' . (int)$vet_id . '&error=' . urlencode('Invalid veterinarian name.'));
        exit;
    }

    if (!preg_match('/^[0-9+ ()-]{10,20}$/', $phone)) {
        mysqli_close($conn);
        header('Location: update_veterinarian.php?vet_id=' . (int)$vet_id . '&error=' . urlencode('Invalid phone number.'));
        exit;
    }

    if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        mysqli_close($conn);
        header('Location: update_veterinarian.php?vet_id=' . (int)$vet_id . '&error=' . urlencode('Invalid email address.'));
        exit;
    }

    $stmt = mysqli_prepare($conn, 'UPDATE veterinarians SET vet_name = ?, specialization = ?, phone = ?, email = ?, clinic_name = ? WHERE vet_id = ?');
    if (!$stmt) {
        mysqli_close($conn);
        header('Location: update_veterinarian.php?vet_id=' . (int)$vet_id . '&error=' . urlencode('Unable to process request.'));
        exit;
    }

    mysqli_stmt_bind_param($stmt, 'sssssi', $vet_name, $specialization, $phone, $email, $clinic_name, $vet_id);
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        header('Location: veterinarians.php?message=' . urlencode('Veterinarian record updated successfully.'));
        exit;
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    header('Location: update_veterinarian.php?vet_id=' . (int)$vet_id . '&error=' . urlencode('Unable to update veterinarian record.'));
    exit;
}

if (!$vet_id || $vet_id < 1) {
    mysqli_close($conn);
    header('Location: veterinarians.php?error=' . urlencode('Invalid veterinarian ID.'));
    exit;
}

$stmt = mysqli_prepare($conn, 'SELECT vet_id, vet_name, specialization, phone, email, clinic_name FROM veterinarians WHERE vet_id = ? LIMIT 1');
if (!$stmt) {
    mysqli_close($conn);
    header('Location: veterinarians.php?error=' . urlencode('Unable to load veterinarian.'));
    exit;
}

mysqli_stmt_bind_param($stmt, 'i', $vet_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $existingVetId, $existingName, $existingSpecialization, $existingPhone, $existingEmail, $existingClinic);
if (!mysqli_stmt_fetch($stmt)) {
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    header('Location: veterinarians.php?error=' . urlencode('Veterinarian not found.'));
    exit;
}
mysqli_stmt_close($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AnimalCare Hub - Edit Veterinarian</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header class="main-header">
    <div class="container header-content">
        <div class="logo-area"><div class="logo-icon">🐾</div><div><h1>AnimalCare Hub</h1><p>Animal Rescue & Care Management System</p></div></div>
    </div>
</header>
<nav class="navbar">
    <div class="container nav-inner">
        <ul class="nav-list">
            <li><a href="index.php">Home</a></li>
            <li><a href="animals.php">🐾 Animals</a></li>
            <li><a href="veterinarians.php" class="active">🩺 Veterinary</a></li>
            <li><a href="treatments.php">🩹 Treatments</a></li>
            <li><a href="vaccinations.php">💉 Vaccinations</a></li>
            <li><a href="adoption.php">🏠 Adoption</a></li>
            <li><a href="search.php">🔍 Search</a></li>
        </ul>
    </div>
</nav>
<main class="page-container">
<section class="form-page">
    <div class="form-heading">
        <div class="page-title-content">
            <span class="title-icon">👨‍⚕️</span>
            <div>
                <h2>Edit Veterinarian</h2>
                <p>Update the veterinarian profile and contact details.</p>
            </div>
        </div>
        <a href="veterinarians.php" class="secondary-btn">← Back to Veterinarians</a>
    </div>
    <?php if ($error): ?><div class="flash error">⚠ <?= htmlspecialchars($error) ?></div><?php endif; ?>
    <?php if ($message): ?><div class="flash success">✓ <?= htmlspecialchars($message) ?></div><?php endif; ?>
    <form action="update_veterinarian.php" method="POST" class="animal-form">
        <input type="hidden" name="vet_id" value="<?= (int)$existingVetId ?>">
        <div class="form-section">
            <h3>Veterinarian Information</h3>
            <div class="form-grid">
                <div class="form-group"><label for="vet_name">Veterinarian Name <span>*</span></label><input id="vet_name" name="vet_name" type="text" required value="<?= htmlspecialchars($existingName) ?>"></div>
                <div class="form-group"><label for="specialization">Specialization</label><input id="specialization" name="specialization" type="text" value="<?= htmlspecialchars($existingSpecialization) ?>"></div>
                <div class="form-group"><label for="phone">Phone <span>*</span></label><input id="phone" name="phone" type="text" required value="<?= htmlspecialchars($existingPhone) ?>"></div>
                <div class="form-group"><label for="email">Email</label><input id="email" name="email" type="email" value="<?= htmlspecialchars($existingEmail) ?>"></div>
                <div class="form-group"><label for="clinic_name">Clinic Name</label><input id="clinic_name" name="clinic_name" type="text" value="<?= htmlspecialchars($existingClinic) ?>"></div>
            </div>
        </div>
        <div class="form-actions">
            <a href="veterinarians.php" class="secondary-btn">Cancel</a>
            <button type="submit" class="primary-btn">✓ Update Veterinarian</button>
        </div>
    </form>
</section>
</main>
<footer class="site-footer"><p>© 2026 AnimalCare Hub | DBMS Mini Project</p></footer>
<script src="script.js"></script>
</body>
</html>
