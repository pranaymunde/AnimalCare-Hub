<?php
require 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>AnimalCare Hub - Home</title>

    <link rel="stylesheet" href="style.css">
</head>

<body>

<!-- ================= HEADER ================= -->

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


<!-- ================= NAVBAR ================= -->

<nav class="navbar">

    <div class="container">

        <ul class="nav-list">

            <li>
                <a href="index.php" class="active"><span>Home</span></a>
            </li>

            <li>
                <a href="animals.php"><span>🐾 Animals</span></a>
            </li>

            <li>
                <a href="veterinarians.php"><span>🩺 Veterinary</span></a>
            </li>

            <li>
                <a href="treatments.php"><span>🩹 Treatments</span></a>
            </li>

            <li>
                <a href="vaccinations.php"><span>💉 Vaccinations</span></a>
            </li>

            <li>
                <a href="adoption.php"><span>🏠 Adoption</span></a>
            </li>

            <li>
                <a href="search.php"><span>🔍 Search</span></a>
            </li>
</ul>

    </div>

</nav>


<!-- ================= MAIN ================= -->

<main>

    <div class="container">

        <section class="live-panel">

            <div class="live-panel-header">

                <div>
                    <span class="page-label">REAL-TIME SYSTEM</span>
                    <h2>Live Operations Status</h2>
                </div>

                <div class="live-status">
                    <span class="live-dot"></span>
                    <span class="live-text">Live feed</span>
                    <span class="live-sync" id="live-last-sync">Updated just now</span>
                </div>

            </div>

            <div class="live-metrics" id="live-dashboard">

                <div class="live-metric">
                    <span class="live-metric-label">Animals</span>
                    <span class="live-metric-value" data-live-count="Animals">0</span>
                </div>

                <div class="live-metric">
                    <span class="live-metric-label">Veterinarians</span>
                    <span class="live-metric-value" data-live-count="Veterinarians">0</span>
                </div>

                <div class="live-metric">
                    <span class="live-metric-label">Treatments</span>
                    <span class="live-metric-value" data-live-count="Treatments">0</span>
                </div>

                <div class="live-metric">
                    <span class="live-metric-label">Vaccinations</span>
                    <span class="live-metric-value" data-live-count="Vaccinations">0</span>
                </div>

                <div class="live-metric">
                    <span class="live-metric-label">Adoptions</span>
                    <span class="live-metric-value" data-live-count="Adoptions">0</span>
                </div>

                <div class="live-metric urgent">
                    <span class="live-metric-label">Pending Adoptions</span>
                    <span class="live-metric-value" id="pending-adoptions-count">0</span>
                </div>

            </div>

        </section>

        <!-- HERO -->

        <section class="hero-section">

            <div class="hero-content">

                <span class="hero-label">
                    DATABASE MANAGEMENT SYSTEM
                </span>

                <h2><span>Welcome to</span> <span>AnimalCare Hub</span></h2>

                <p>A centralized database-driven system for managing animal rescue, veterinary treatments, vaccinations, and adoption requests.</p>

                <div class="hero-buttons">

                    <a href="animals.php" class="primary-btn">
                        View Animal Records
                    </a>

                    <a href="add_animal.php" class="secondary-btn">
                        + Add Animal
                    </a>

                </div>

            </div>

        </section>


        <!-- SECTION TITLE -->

        <section class="section-heading">

            <span>OUR MODULES</span>

            <h2>Manage AnimalCare Easily</h2>

            <p>Access all major modules of the AnimalCare Hub system.</p>

        </section>


        <!-- FEATURE CARDS -->

        <section class="features-grid">


            <!-- ANIMALS -->

            <div class="feature-card">

                <div class="feature-icon animal-icon">
                    🐾
                </div>

                <h3>Animal Records</h3>

                <p>Store and manage information about rescued, registered and adoptable animals.</p>

                <a href="animals.php">View Records →</a>

            </div>


            <!-- VETERINARY -->

            <div class="feature-card">

                <div class="feature-icon vet-icon">
                    🩺
                </div>

                <h3>Veterinary Care</h3>

                <p>Manage veterinarians, diagnoses, treatments and animal healthcare information.</p>

                <a href="veterinarians.php">Veterinary Records →</a>

            </div>


            <!-- VACCINATION -->

            <div class="feature-card">

                <div class="feature-icon vaccine-icon">
                    💉
                </div>

                <h3>Vaccinations</h3>

                <p>Track vaccination dates, vaccine names and upcoming vaccination due dates.</p>

                <a href="vaccinations.php">View Vaccinations →</a>

            </div>


            <!-- ADOPTION -->

            <div class="feature-card">

                <div class="feature-icon adoption-icon">
                    🏠
                </div>

                <h3>Adoption Requests</h3>

                <p>Manage adoption applications and monitor pending, approved and rejected requests.</p>

                <a href="adoption.php">View Requests →</a>

            </div>

        </section>


        <!-- DATABASE SECTION -->

        <section class="database-section">

            <div class="database-text">

                <span>RELATIONAL DATABASE</span>

                <h2>Everything Connected in One System</h2>

                <p>
                    AnimalCare Hub uses a relational MySQL database
                    to connect animal records with veterinary care,
                    vaccination records and adoption requests.
                </p>

                <a href="search.php" class="primary-btn">
                    Search Records
                </a>

            </div>


            <div class="database-box">

                <div class="db-item">
                    <strong>Animals</strong>
                    <small>Animal Records</small>
                </div>

                <div class="connection">↕</div>

                <div class="db-item">
                    <strong>Veterinary</strong>
                    <small>Treatments</small>
                </div>

                <div class="connection">↕</div>

                <div class="db-item">
                    <strong>Vaccinations</strong>
                    <small>Health Records</small>
                </div>

                <div class="connection">↕</div>

                <div class="db-item">
                    <strong>Adoption</strong>
                    <small>Requests</small>
                </div>

            </div>

        </section>


        <!-- QUICK ACTIONS -->

        <section class="quick-section">

            <div class="section-heading small-heading">

                <span>QUICK ACTIONS</span>

                <h2>Manage Records</h2>

            </div>


            <div class="quick-grid">

                <a href="add_animal.php" class="quick-card">
                    <strong>+ Add Animal</strong>
                    <span>Create a new animal record</span>
                </a>

                <a href="add_veterinarian.php" class="quick-card">
                    <strong>+ Add Veterinarian</strong>
                    <span>Register a veterinarian</span>
                </a>

                <a href="add_treatment.php" class="quick-card">
                    <strong>+ Add Treatment</strong>
                    <span>Record animal treatment</span>
                </a>

                <a href="add_vaccination.php" class="quick-card">
                    <strong>+ Add Vaccination</strong>
                    <span>Record vaccination details</span>
                </a>

            </div>

        </section>

    </div>

</main>


<!-- ================= FOOTER ================= -->

<footer class="main-footer">

    <div class="container footer-content">

        <div>
            <h3>🐾 AnimalCare Hub</h3>

            <p>Animal Rescue & Care Management System</p>
        </div>

        <div class="footer-right">

            <p>DBMS Mini Project</p>

            <p>
                © 2026 AnimalCare Hub
            </p>

        </div>

    </div>

</footer>

<script src="script.js"></script>


<script src="script.js"></script>
</body>
</html>