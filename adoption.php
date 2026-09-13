<?php
require 'config.php';

$sql = "SELECT
            ar.request_id,
            ar.applicant_name,
            ar.email,
            ar.phone,
            ar.city,
            ar.request_date,
            ar.reason,
            ar.request_status,
            a.animal_name,
            a.animal_type
        FROM adoption_requests ar
        INNER JOIN animals a
            ON ar.animal_id = a.animal_id
        ORDER BY ar.request_id DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>AnimalCare Hub - Adoption Requests</title>

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
            <li><a href="treatments.php">🩹 Treatments</a></li>
            <li><a href="vaccinations.php">💉 Vaccinations</a></li>
            <li><a href="adoption.php" class="active">🏠 Adoption</a></li>
            <li><a href="search.php">🔍 Search</a></li>
        </ul>
    </div>
</nav>


<main class="page-container">

    <section class="page-title">

        <div class="page-title-content">

            <span class="title-icon">🏠</span>

            <div>

                <h2>Adoption Requests</h2>

                <p>
                    Manage applications from people interested in adoption.
                </p>

            </div>

        </div>


        <a
            href="add_adoption.php"
            class="primary-btn"
        >
            + New Adoption Request
        </a>

    </section>


    <section class="data-card">

        <div class="card-header">

            <div>

                <h3>Adoption Applications</h3>

                <p>
                    View applicants and their requested animals.
                </p>

            </div>


            <span class="record-count">

                <?php echo mysqli_num_rows($result); ?>

                Records

            </span>

        </div>


        <div class="table-wrapper">

            <table class="data-table">

                <thead>

                    <tr>

                        <th>ID</th>
                        <th>Animal</th>
                        <th>Applicant</th>
                        <th>Contact</th>
                        <th>City</th>
                        <th>Request Date</th>
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

                                    #<?php
                                    echo (int)$row['request_id'];
                                    ?>

                                </span>

                            </td>


                            <td>

                                <strong>

                                    🐾

                                    <?php
                                    echo htmlspecialchars(
                                        $row['animal_name']
                                    );
                                    ?>

                                </strong>

                                <br>

                                <small>

                                    <?php
                                    echo htmlspecialchars(
                                        $row['animal_type']
                                    );
                                    ?>

                                </small>

                            </td>


                            <td>

                                <strong>

                                    <?php
                                    echo htmlspecialchars(
                                        $row['applicant_name']
                                    );
                                    ?>

                                </strong>

                                <br>

                                <small>

                                    <?php
                                    echo htmlspecialchars(
                                        $row['email']
                                    );
                                    ?>

                                </small>

                            </td>


                            <td>

                                <?php
                                echo htmlspecialchars(
                                    $row['phone']
                                );
                                ?>

                            </td>


                            <td>

                                <?php
                                echo htmlspecialchars(
                                    $row['city']
                                );
                                ?>

                            </td>


                            <td>

                                <?php
                                echo htmlspecialchars(
                                    $row['request_date']
                                );
                                ?>

                            </td>


                            <td>

                                <?php

                                $status =
                                    $row['request_status'];

                                if (
                                    strtolower($status)
                                    === 'approved'
                                ) {

                                    $status_class =
                                        'status-completed';

                                } elseif (
                                    strtolower($status)
                                    === 'pending'
                                ) {

                                    $status_class =
                                        'status-ongoing';

                                } else {

                                    $status_class =
                                        'status-default';
                                }

                                ?>

                                <span
                                    class="status-badge
                                    <?php echo $status_class; ?>"
                                >

                                    <?php
                                    echo htmlspecialchars($status);
                                    ?>

                                </span>

                            </td>

                            <td class="action-cell">
                                <a class="edit-btn" href="update_adoption.php?request_id=<?= (int)$row['request_id'] ?>">✏ Edit</a>
                                <form action="delete_adoption.php" method="POST" class="inline-delete" onsubmit="return confirm('Delete this adoption request?');">
                                    <input type="hidden" name="request_id" value="<?= (int)$row['request_id'] ?>">
                                    <button class="delete-btn" type="submit">🗑 Delete</button>
                                </form>
                            </td>

                        </tr>

                    <?php endwhile; ?>

                <?php else: ?>

                    <tr>

                        <td
                            colspan="7"
                            class="empty-state"
                        >

                            <div class="empty-icon">
                                🏠
                            </div>

                            <h3>
                                No Adoption Requests
                            </h3>

                            <p>
                                No adoption applications have
                                been submitted yet.
                            </p>


                            <a
                                href="add_adoption.php"
                                class="primary-btn"
                            >
                                + New Adoption Request
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
        © 2026 AnimalCare Hub |
        Animal Rescue & Veterinary Management System
    </p>

</footer>


<script src="script.js"></script>

</body>

</html>

<?php
mysqli_close($conn);
?>