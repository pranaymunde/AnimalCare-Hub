<?php
require 'config.php';

$error = trim($_GET['error'] ?? '');
$message = trim($_GET['message'] ?? '');
$vaccination_id = filter_var($_GET['vaccination_id'] ?? null, FILTER_VALIDATE_INT);

if (!$vaccination_id || $vaccination_id < 1) {
    mysqli_close($conn);
    header('Location: vaccinations.php?error=' . urlencode('Invalid vaccination ID.'));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vaccination_id = filter_var($_POST['vaccination_id'] ?? null, FILTER_VALIDATE_INT);
    $animal_id = filter_var($_POST['animal_id'] ?? null, FILTER_VALIDATE_INT);
    $vaccine_name = trim($_POST['vaccine_name'] ?? '');
    $vaccination_date = trim($_POST['vaccination_date'] ?? '');
    $next_due_date = trim($_POST['next_due_date'] ?? '');
    $vaccination_status = trim($_POST['vaccination_status'] ?? '');

    if (!$vaccination_id || !$animal_id || $vaccine_name === '' || $vaccination_date === '' || $next_due_date === '' || $vaccination_status === '') {
        mysqli_close($conn);
        header('Location: update_vaccination.php?vaccination_id=' . (int)$vaccination_id . '&error=' . urlencode('Please fill all required fields.'));
        exit;
    }

    if (!in_array($vaccination_status, ['Completed', 'Due Soon', 'Pending'], true)) {
        mysqli_close($conn);
        header('Location: update_vaccination.php?vaccination_id=' . (int)$vaccination_id . '&error=' . urlencode('Invalid vaccination status.'));
        exit;
    }

    $stmt = mysqli_prepare($conn, 'UPDATE vaccinations SET animal_id = ?, vaccine_name = ?, vaccination_date = ?, next_due_date = ?, vaccination_status = ? WHERE vaccination_id = ?');
    if (!$stmt) {
        mysqli_close($conn);
        header('Location: update_vaccination.php?vaccination_id=' . (int)$vaccination_id . '&error=' . urlencode('Unable to process request.'));
        exit;
    }

    mysqli_stmt_bind_param($stmt, 'issssi', $animal_id, $vaccine_name, $vaccination_date, $next_due_date, $vaccination_status, $vaccination_id);
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        header('Location: vaccinations.php?message=' . urlencode('Vaccination record updated successfully.'));
        exit;
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    header('Location: update_vaccination.php?vaccination_id=' . (int)$vaccination_id . '&error=' . urlencode('Unable to update vaccination record.'));
    exit;
}

$stmt = mysqli_prepare($conn, 'SELECT vaccination_id, animal_id, vaccine_name, vaccination_date, next_due_date, vaccination_status FROM vaccinations WHERE vaccination_id = ? LIMIT 1');
if (!$stmt) {
    mysqli_close($conn);
    header('Location: vaccinations.php?error=' . urlencode('Unable to load vaccination.'));
    exit;
}

mysqli_stmt_bind_param($stmt, 'i', $vaccination_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $existingVaccinationId, $existingAnimalId, $existingVaccineName, $existingVaccinationDate, $existingNextDueDate, $existingStatus);
if (!mysqli_stmt_fetch($stmt)) {
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    header('Location: vaccinations.php?error=' . urlencode('Vaccination not found.'));
    exit;
}
mysqli_stmt_close($stmt);

$animals = mysqli_query($conn, 'SELECT animal_id, animal_name, animal_type FROM animals ORDER BY animal_name ASC');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AnimalCare Hub - Edit Vaccination</title>
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
            <li><a href="treatments.php">🩹 Treatments</a></li>
            <li><a href="vaccinations.php" class="active">💉 Vaccinations</a></li>
            <li><a href="adoption.php">🏠 Adoption</a></li>
            <li><a href="search.php">🔍 Search</a></li>
        </ul>
    </div>
</nav>
<main class="page-container">
<section class="form-page">
    <div class="form-heading">
        <div class="page-title-content">
            <span class="title-icon">💉</span>
            <div>
                <h2>Edit Vaccination Record</h2>
                <p>Update vaccine details and schedule information.</p>
            </div>
        </div>
        <a href="vaccinations.php" class="secondary-btn">← Back to Vaccinations</a>
    </div>
    <?php if ($error): ?><div class="flash error">⚠ <?= htmlspecialchars($error) ?></div><?php endif; ?>
    <?php if ($message): ?><div class="flash success">✓ <?= htmlspecialchars($message) ?></div><?php endif; ?>
    <form action="update_vaccination.php" method="POST" class="animal-form">
        <input type="hidden" name="vaccination_id" value="<?= (int)$existingVaccinationId ?>">
        <div class="form-section">
            <h3>Vaccination Information</h3>
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
                    <label for="vaccine_name">Vaccine Name <span>*</span></label>
                    <input id="vaccine_name" name="vaccine_name" type="text" required value="<?= htmlspecialchars($existingVaccineName) ?>">
                </div>
                <div class="form-group">
                    <label for="vaccination_date">Vaccination Date <span>*</span></label>
                    <input id="vaccination_date" name="vaccination_date" type="date" required value="<?= htmlspecialchars($existingVaccinationDate) ?>">
                </div>
                <div class="form-group">
                    <label for="next_due_date">Next Due Date <span>*</span></label>
                    <input id="next_due_date" name="next_due_date" type="date" required value="<?= htmlspecialchars($existingNextDueDate) ?>">
                </div>
                <div class="form-group">
                    <label for="vaccination_status">Vaccination Status <span>*</span></label>
                    <select id="vaccination_status" name="vaccination_status" required>
                        <option value="Completed" <?= $existingStatus === 'Completed' ? 'selected' : '' ?>>Completed</option>
                        <option value="Due Soon" <?= $existingStatus === 'Due Soon' ? 'selected' : '' ?>>Due Soon</option>
                        <option value="Pending" <?= $existingStatus === 'Pending' ? 'selected' : '' ?>>Pending</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-actions">
            <a href="vaccinations.php" class="secondary-btn">Cancel</a>
            <button type="submit" class="primary-btn">✓ Update Vaccination</button>
        </div>
    </form>
</section>
</main>
<footer class="site-footer"><p>© 2026 AnimalCare Hub | DBMS Mini Project</p></footer>
<script src="script.js"></script>
</body>
</html>
<?php mysqli_close($conn); ?>
