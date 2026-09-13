<?php
require 'config.php';

$error = trim($_GET['error'] ?? '');
$message = trim($_GET['message'] ?? '');

$request_id = filter_var($_GET['request_id'] ?? ($_POST['request_id'] ?? null), FILTER_VALIDATE_INT);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $request_id = filter_var($_POST['request_id'] ?? null, FILTER_VALIDATE_INT);
    $applicant_name = trim($_POST['applicant_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $request_date = trim($_POST['request_date'] ?? '');
    $reason = trim($_POST['reason'] ?? '');
    $request_status = trim($_POST['request_status'] ?? '');

    $allowed_status = ['Pending', 'Approved', 'Rejected', 'Completed'];

    if (!$request_id || $request_id < 1 || $applicant_name === '' || $email === '' || $phone === '' || $city === '' || $request_date === '' || $reason === '' || !in_array($request_status, $allowed_status, true)) {
        mysqli_close($conn);
        header('Location: update_adoption.php?request_id=' . (int)$request_id . '&error=' . urlencode('Please fill all required fields.'));
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        mysqli_close($conn);
        header('Location: update_adoption.php?request_id=' . (int)$request_id . '&error=' . urlencode('Invalid email address.'));
        exit;
    }

    if (!preg_match('/^[A-Za-z .]+$/', $applicant_name)) {
        mysqli_close($conn);
        header('Location: update_adoption.php?request_id=' . (int)$request_id . '&error=' . urlencode('Invalid applicant name.'));
        exit;
    }

    if (!preg_match('/^[0-9+ ()-]{10,20}$/', $phone)) {
        mysqli_close($conn);
        header('Location: update_adoption.php?request_id=' . (int)$request_id . '&error=' . urlencode('Invalid phone number.'));
        exit;
    }

    $stmt = mysqli_prepare($conn, 'UPDATE adoption_requests SET applicant_name = ?, email = ?, phone = ?, city = ?, request_date = ?, reason = ?, request_status = ? WHERE request_id = ?');
    if (!$stmt) {
        mysqli_close($conn);
        header('Location: update_adoption.php?request_id=' . (int)$request_id . '&error=' . urlencode('Unable to process request.'));
        exit;
    }

    mysqli_stmt_bind_param($stmt, 'sssssssi', $applicant_name, $email, $phone, $city, $request_date, $reason, $request_status, $request_id);
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        header('Location: adoption.php?message=' . urlencode('Adoption request updated successfully.'));
        exit;
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    header('Location: update_adoption.php?request_id=' . (int)$request_id . '&error=' . urlencode('Unable to update adoption request.'));
    exit;
}

if (!$request_id || $request_id < 1) {
    mysqli_close($conn);
    header('Location: adoption.php?error=' . urlencode('Invalid adoption request ID.'));
    exit;
}

$stmt = mysqli_prepare($conn, 'SELECT request_id, animal_id, applicant_name, email, phone, city, request_date, reason, request_status FROM adoption_requests WHERE request_id = ? LIMIT 1');
if (!$stmt) {
    mysqli_close($conn);
    header('Location: adoption.php?error=' . urlencode('Unable to load request.'));
    exit;
}

mysqli_stmt_bind_param($stmt, 'i', $request_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $existingRequestId, $existingAnimalId, $existingApplicantName, $existingEmail, $existingPhone, $existingCity, $existingRequestDate, $existingReason, $existingStatus);
if (!mysqli_stmt_fetch($stmt)) {
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    header('Location: adoption.php?error=' . urlencode('Adoption request not found.'));
    exit;
}
mysqli_stmt_close($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AnimalCare Hub - Update Adoption Request</title>
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
            <li><a href="veterinarians.php">🩺 Veterinary</a></li>
            <li><a href="treatments.php">🩹 Treatments</a></li>
            <li><a href="vaccinations.php">💉 Vaccinations</a></li>
            <li><a href="adoption.php" class="active">🏠 Adoption</a></li>
            <li><a href="search.php">🔍 Search</a></li>
        </ul>
    </div>
</nav>
<main class="page-container">
<section class="form-page">
    <div class="form-heading">
        <div class="page-title-content">
            <span class="title-icon">🏡</span>
            <div>
                <h2>Edit Adoption Request</h2>
                <p>Update applicant details and the adoption status.</p>
            </div>
        </div>
        <a href="adoption.php" class="secondary-btn">← Back to Adoption</a>
    </div>
    <?php if ($error): ?><div class="flash error">⚠ <?= htmlspecialchars($error) ?></div><?php endif; ?>
    <?php if ($message): ?><div class="flash success">✓ <?= htmlspecialchars($message) ?></div><?php endif; ?>
    <form action="update_adoption.php" method="POST" class="animal-form">
        <input type="hidden" name="request_id" value="<?= (int)$existingRequestId ?>">
        <div class="form-section">
            <h3>Applicant Information</h3>
            <div class="form-grid">
                <div class="form-group"><label for="applicant_name">Applicant Name <span>*</span></label><input id="applicant_name" name="applicant_name" type="text" required value="<?= htmlspecialchars($existingApplicantName) ?>"></div>
                <div class="form-group"><label for="email">Email <span>*</span></label><input id="email" name="email" type="email" required value="<?= htmlspecialchars($existingEmail) ?>"></div>
                <div class="form-group"><label for="phone">Phone <span>*</span></label><input id="phone" name="phone" type="text" required value="<?= htmlspecialchars($existingPhone) ?>"></div>
                <div class="form-group"><label for="city">City <span>*</span></label><input id="city" name="city" type="text" required value="<?= htmlspecialchars($existingCity) ?>"></div>
                <div class="form-group"><label for="request_date">Request Date <span>*</span></label><input id="request_date" name="request_date" type="date" required value="<?= htmlspecialchars($existingRequestDate) ?>"></div>
                <div class="form-group">
                    <label for="request_status">Adoption Status <span>*</span></label>
                    <select id="request_status" name="request_status" required>
                        <option value="Pending" <?= $existingStatus === 'Pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="Approved" <?= $existingStatus === 'Approved' ? 'selected' : '' ?>>Approved</option>
                        <option value="Rejected" <?= $existingStatus === 'Rejected' ? 'selected' : '' ?>>Rejected</option>
                        <option value="Completed" <?= $existingStatus === 'Completed' ? 'selected' : '' ?>>Completed</option>
                    </select>
                </div>
                <div class="form-group full-width">
                    <label for="reason">Reason <span>*</span></label>
                    <textarea id="reason" name="reason" rows="4" required><?= htmlspecialchars($existingReason) ?></textarea>
                </div>
            </div>
        </div>
        <div class="form-actions">
            <a href="adoption.php" class="secondary-btn">Cancel</a>
            <button type="submit" class="primary-btn">✓ Update Adoption Request</button>
        </div>
    </form>
</section>
</main>
<footer class="site-footer"><p>© 2026 AnimalCare Hub | DBMS Mini Project</p></footer>
<script src="script.js"></script>
</body>
</html>
<?php mysqli_close($conn); ?>