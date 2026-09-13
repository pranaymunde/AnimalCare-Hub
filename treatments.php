<?php
require 'config.php';

$sql = "SELECT 
            t.treatment_id,
            t.treatment_date,
            t.diagnosis,
            t.treatment_description,
            t.treatment_status,
            a.animal_name,
            v.vet_name
        FROM treatments t
        INNER JOIN animals a ON t.animal_id = a.animal_id
        INNER JOIN veterinarians v ON t.vet_id = v.vet_id
        ORDER BY t.treatment_id DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>AnimalCare Hub - Treatments</title>

    <link rel="stylesheet" href="style.css">
</head>

<body>

<header class="main-header">
    <div class="container header-content">
        <div class="logo-area">
            <div class="logo-icon">🐾</div>
            <div>
                <h1>AnimalCare Hub</h1>
                <p>Animal Rescue & Care Management System</p>
            </div>
        </div>
        <div class="header-auth">
            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="top-profile-panel">
                    <div class="top-profile-avatar">👤</div>
                    <div class="top-profile-text">
                        <span class="top-profile-name"><?= htmlspecialchars($_SESSION['name'] ?? 'Care Team Member') ?></span>
                        <span class="top-profile-role"><?= htmlspecialchars($_SESSION['role'] ?? 'Team Member') ?></span>
                    </div>
                </div>
                <div class="top-profile-links">
                    <a href="logout.php" class="top-logout-link">Logout</a>
                </div>
            <?php else: ?>
                <div class="top-profile-panel">
                    <div class="top-profile-avatar">👤</div>
                    <div class="top-profile-text">
                        <span class="top-profile-name">Guest</span>
                        <span class="top-profile-role">Visitor</span>
                    </div>
                </div>
                <div class="top-profile-links">
                    <a href="login.php" class="top-login-link">Login</a>
                    <a href="register.php" class="top-register-link">Sign Up</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
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

    <section class="page-title">

        <div class="page-title-content">

            <span class="title-icon">🩺</span>

            <div>
                <h2>Treatment Records</h2>
                <p>Track animal treatments, diagnoses and veterinary care.</p>
            </div>

        </div>

        <a href="add_treatment.php" class="primary-btn">
            + Add Treatment
        </a>

    </section>


    <section class="data-card">

        <div class="card-header">

            <div>
                <h3>Animal Treatment History</h3>
                <p>View treatment records connected with animals and veterinarians.</p>
            </div>

            <span class="record-count">
                <?php echo mysqli_num_rows($result); ?> Records
            </span>

        </div>


        <div class="table-wrapper">

            <table class="data-table">

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Animal</th>
                        <th>Veterinarian</th>
                        <th>Date</th>
                        <th>Diagnosis</th>
                        <th>Treatment</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>

                <?php if (mysqli_num_rows($result) > 0): ?>

                    <?php while ($row = mysqli_fetch_assoc($result)): ?>

                        <tr>

                            <td>
                                <span class="id-badge">
                                    #<?php echo (int)$row['treatment_id']; ?>
                                </span>
                            </td>

                            <td>
                                <strong>
                                    🐾 <?php echo htmlspecialchars($row['animal_name']); ?>
                                </strong>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row['vet_name']); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row['treatment_date']); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row['diagnosis']); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row['treatment_description']); ?>
                            </td>

                            <td>

                                <?php
                                $status = $row['treatment_status'];

                                if (strtolower($status) === 'completed') {
                                    $status_class = 'status-completed';
                                } elseif (strtolower($status) === 'ongoing') {
                                    $status_class = 'status-ongoing';
                                } else {
                                    $status_class = 'status-default';
                                }
                                ?>

                                <span class="status-badge <?php echo $status_class; ?>">
                                    <?php echo htmlspecialchars($status); ?>
                                </span>

                            </td>

                            <td class="action-cell">
                                <a class="edit-btn" href="update_treatment.php?treatment_id=<?= (int)$row['treatment_id'] ?>">✏ Edit</a>
                                <form action="delete_treatment.php" method="POST" class="inline-delete" onsubmit="return confirm('Delete this treatment record?');">
                                    <input type="hidden" name="treatment_id" value="<?= (int)$row['treatment_id'] ?>">
                                    <button class="delete-btn" type="submit">🗑 Delete</button>
                                </form>
                            </td>

                        </tr>

                    <?php endwhile; ?>

                <?php else: ?>

                    <tr>

                        <td colspan="7" class="empty-state">

                            <div class="empty-icon">🩺</div>

                            <h3>No Treatment Records</h3>

                            <p>
                                Add a treatment record to start tracking veterinary care.
                            </p>

                            <a href="add_treatment.php" class="primary-btn">
                                + Add Treatment
                            </a>

                        </td>

                    </tr>

                <?php endif; ?>

                </tbody>

            </table>

        </div>

    </section>

</main>


<footer class="main-footer">

    <p>
        © 2026 AnimalCare Hub | Animal Rescue & Veterinary Management System
    </p>

</footer>

<script src="script.js"></script>
</body>
</html>

<?php
mysqli_close($conn);
?>