<?php
require 'config.php';

$error = trim($_GET['error'] ?? '');
$message = trim($_GET['message'] ?? '');
$treatment_id = filter_var($_GET['treatment_id'] ?? ($_POST['treatment_id'] ?? null), FILTER_VALIDATE_INT);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $treatment_id = filter_var($_POST['treatment_id'] ?? null, FILTER_VALIDATE_INT);
    $animal_id = filter_var($_POST['animal_id'] ?? null, FILTER_VALIDATE_INT);
    $vet_id = filter_var($_POST['vet_id'] ?? null, FILTER_VALIDATE_INT);
    $treatment_date = trim($_POST['treatment_date'] ?? '');
    $diagnosis = trim($_POST['diagnosis'] ?? '');
    $treatment_description = trim($_POST['treatment_description'] ?? '');
    $treatment_status = trim($_POST['treatment_status'] ?? '');

    if (!$treatment_id || !$animal_id || !$vet_id || $treatment_date === '' || $diagnosis === '' || $treatment_status === '') {
        mysqli_close($conn);
        header('Location: update_treatment.php?treatment_id=' . (int)$treatment_id . '&error=' . urlencode('Please fill all required fields.'));
        exit;
    }

    if (!in_array($treatment_status, ['Ongoing', 'Completed'], true)) {
        mysqli_close($conn);
        header('Location: update_treatment.php?treatment_id=' . (int)$treatment_id . '&error=' . urlencode('Invalid treatment status.'));
        exit;
    }

    $stmt = mysqli_prepare($conn, 'UPDATE treatments SET animal_id = ?, vet_id = ?, treatment_date = ?, diagnosis = ?, treatment_description = ?, treatment_status = ? WHERE treatment_id = ?');
    if (!$stmt) {
        mysqli_close($conn);
        header('Location: update_treatment.php?treatment_id=' . (int)$treatment_id . '&error=' . urlencode('Unable to process request.'));
        exit;
    }

    mysqli_stmt_bind_param($stmt, 'iissssi', $animal_id, $vet_id, $treatment_date, $diagnosis, $treatment_description, $treatment_status, $treatment_id);
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        header('Location: treatments.php?message=' . urlencode('Treatment record updated successfully.'));
        exit;
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    header('Location: update_treatment.php?treatment_id=' . (int)$treatment_id . '&error=' . urlencode('Unable to update treatment record.'));
    exit;
}

if (!$treatment_id || $treatment_id < 1) {
    mysqli_close($conn);
    header('Location: treatments.php?error=' . urlencode('Invalid treatment ID.'));
    exit;
}

$stmt = mysqli_prepare($conn, 'SELECT treatment_id, animal_id, vet_id, treatment_date, diagnosis, treatment_description, treatment_status FROM treatments WHERE treatment_id = ? LIMIT 1');
if (!$stmt) {
    mysqli_close($conn);
    header('Location: treatments.php?error=' . urlencode('Unable to load treatment.'));
    exit;
}

mysqli_stmt_bind_param($stmt, 'i', $treatment_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $existingTreatmentId, $existingAnimalId, $existingVetId, $existingDate, $existingDiagnosis, $existingDescription, $existingStatus);
if (!mysqli_stmt_fetch($stmt)) {
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    header('Location: treatments.php?error=' . urlencode('Treatment not found.'));
    exit;
}
mysqli_stmt_close($stmt);

$animals = mysqli_query($conn, 'SELECT animal_id, animal_name, animal_type FROM animals ORDER BY animal_name ASC');
$veterinarians = mysqli_query($conn, 'SELECT vet_id, vet_name, specialization FROM veterinarians ORDER BY vet_name ASC');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AnimalCare Hub - Edit Treatment</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header class="main-header">
    <div class="container header-content"><div class="logo-area"><div class="logo-icon">🐾</div><div><h1>AnimalCare Hub</h1><p>Animal Rescue & Care Management System</p></div></div></div>
</header>
<nav class="navbar">
    <div class="container nav-inner">
        <ul class="nav-list">
            <li><a href="index.php">Home</a></li>
            <li><a href="animals.php">🐾 Animals</a></li>
            <li><a href="veterinarians.php">🩺 Veterinary</a></li>
            <li><a href="treatments.php" class="active">🩹 Treatments</a></li>
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
            <span class="title-icon">🩺</span>
            <div>
                <h2>Edit Treatment Record</h2>
                <p>Update treatment, diagnosis and care details.</p>
            </div>
        </div>
        <a href="treatments.php" class="secondary-btn">← Back to Treatments</a>
    </div>
    <?php if ($error): ?><div class="flash error">⚠ <?= htmlspecialchars($error) ?></div><?php endif; ?>
    <?php if ($message): ?><div class="flash success">✓ <?= htmlspecialchars($message) ?></div><?php endif; ?>
    <form action="update_treatment.php" method="POST" class="animal-form">
        <input type="hidden" name="treatment_id" value="<?= (int)$existingTreatmentId ?>">
        <div class="form-section">
            <h3>Patient & Veterinarian</h3>
            <div class="form-grid">
                <div class="form-group">
                    <label for="animal_id">Animal <span>*</span></label>
                    <select id="animal_id" name="animal_id" required>
                        <option value="">-- Select Animal --</option>
                        <?php while ($animal = mysqli_fetch_assoc($animals)): ?>
                            <option value="<?= (int)$animal['animal_id'] ?>" <?= (int)$animal['animal_id'] === (int)$existingAnimalId ? 'selected' : '' ?>><?= htmlspecialchars($animal['animal_name'] . ' (' . $animal['animal_type'] . ')') ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="vet_id">Veterinarian <span>*</span></label>
                    <select id="vet_id" name="vet_id" required>
                        <option value="">-- Select Veterinarian --</option>
                        <?php while ($vet = mysqli_fetch_assoc($veterinarians)): ?>
                            <option value="<?= (int)$vet['vet_id'] ?>" <?= (int)$vet['vet_id'] === (int)$existingVetId ? 'selected' : '' ?>><?= htmlspecialchars($vet['vet_name'] . ' - ' . $vet['specialization']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="treatment_date">Treatment Date <span>*</span></label>
                    <input type="date" id="treatment_date" name="treatment_date" required value="<?= htmlspecialchars($existingDate) ?>">
                </div>
                <div class="form-group">
                    <label for="treatment_status">Treatment Status <span>*</span></label>
                    <select id="treatment_status" name="treatment_status" required>
                        <option value="Ongoing" <?= $existingStatus === 'Ongoing' ? 'selected' : '' ?>>Ongoing</option>
                        <option value="Completed" <?= $existingStatus === 'Completed' ? 'selected' : '' ?>>Completed</option>
                    </select>
                </div>
                <div class="form-group full-width">
                    <label for="diagnosis">Diagnosis <span>*</span></label>
                    <input id="diagnosis" name="diagnosis" type="text" required value="<?= htmlspecialchars($existingDiagnosis) ?>">
                </div>
                <div class="form-group full-width">
                    <label for="treatment_description">Treatment Description</label>
                    <textarea id="treatment_description" name="treatment_description" rows="4"><?= htmlspecialchars($existingDescription) ?></textarea>
                </div>
            </div>
        </div>
        <div class="form-actions">
            <a href="treatments.php" class="secondary-btn">Cancel</a>
            <button type="submit" class="primary-btn">✓ Update Treatment</button>
        </div>
    </form>
</section>
</main>
<footer class="site-footer"><p>© 2026 AnimalCare Hub | DBMS Mini Project</p></footer>
<script src="script.js"></script>
</body>
</html>
<?php mysqli_close($conn); ?>
