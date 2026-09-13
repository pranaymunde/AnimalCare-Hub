<?php
require 'config.php';

$sql = "SELECT * FROM veterinarians ORDER BY vet_id DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AnimalCare Hub - Veterinarians</title>
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
            <li><a href="veterinarians.php" class="active">🩺 Veterinary</a></li>
            <li><a href="treatments.php">🩹 Treatments</a></li>
            <li><a href="vaccinations.php">💉 Vaccinations</a></li>
            <li><a href="adoption.php">🏠 Adoption</a></li>
            <li><a href="search.php">🔍 Search</a></li>
        </ul>
    </div>
</nav>

<main class="page-container">

    <section class="page-title">
        <div class="page-title-content">
            <span class="title-icon">👨‍⚕️</span>
            <div>
                <h2>Veterinarians</h2>
                <p>Manage veterinary professionals and their specializations.</p>
            </div>
        </div>

        <a href="add_veterinarian.php" class="primary-btn">
            + Add Veterinarian
        </a>
    </section>


    <section class="data-card">

        <div class="card-header">
            <div>
                <h3>Veterinarian Directory</h3>
                <p>Registered veterinarians available for animal treatment.</p>
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
                        <th>Veterinarian</th>
                        <th>Specialization</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Clinic</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>

                <?php if (mysqli_num_rows($result) > 0): ?>

                    <?php while ($row = mysqli_fetch_assoc($result)): ?>

                        <tr>

                            <td>
                                <span class="id-badge">
                                    #<?php echo (int)$row['vet_id']; ?>
                                </span>
                            </td>

                            <td>
                                <div class="person-info">
                                    <div class="person-avatar">
                                        👨‍⚕️
                                    </div>

                                    <div>
                                        <strong>
                                            <?php echo htmlspecialchars($row['vet_name']); ?>
                                        </strong>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <span class="info-badge">
                                    <?php echo htmlspecialchars($row['specialization']); ?>
                                </span>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row['phone']); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row['email']); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row['clinic_name']); ?>
                            </td>

                            <td class="action-cell">
                                <a class="edit-btn" href="update_veterinarian.php?vet_id=<?= (int)$row['vet_id'] ?>">✏ Edit</a>
                                <form action="delete_veterinarian.php" method="POST" class="inline-delete" onsubmit="return confirm('Delete this veterinarian and all linked treatment records?');">
                                    <input type="hidden" name="vet_id" value="<?= (int)$row['vet_id'] ?>">
                                    <button class="delete-btn" type="submit">🗑 Delete</button>
                                </form>
                            </td>

                        </tr>

                    <?php endwhile; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="6" class="empty-state">
                            <div class="empty-icon">👨‍⚕️</div>
                            <h3>No Veterinarians Found</h3>
                            <p>Add your first veterinarian to the system.</p>

                            <a href="add_veterinarian.php" class="primary-btn">
                                + Add Veterinarian
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
    <p>© 2026 AnimalCare Hub | Animal Rescue & Veterinary Management System</p>
</footer>

<script src="script.js"></script>
</body>
</html>

<?php
mysqli_close($conn);
?>