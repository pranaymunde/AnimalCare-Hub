<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = trim($_POST['role'] ?? 'Care Team');

    // First-time local setup: create the authentication table if needed.
    // No user input is used in this SQL statement.
    $createUsers = mysqli_query($conn, "CREATE TABLE IF NOT EXISTS users (
        user_id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(150) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        role VARCHAR(50) NOT NULL DEFAULT 'Care Team',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB");

    if (!$createUsers) {
        header('Location: register.php?error=' . urlencode('Unable to initialize the account system.'));
        exit;
    }

    if ($name === '' || $email === '' || $password === '') {
        header('Location: register.php?error=' . urlencode('Please fill all registration fields.'));
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header('Location: register.php?error=' . urlencode('Please enter a valid email address.'));
        exit;
    }

    if (strlen($password) < 6) {
        header('Location: register.php?error=' . urlencode('Password must be at least 6 characters.'));
        exit;
    }

    $role = in_array($role, ['Care Team', 'Veterinarian', 'Administrator', 'Volunteer'], true)
        ? $role
        : 'Care Team';

    $existing = mysqli_prepare($conn, 'SELECT user_id FROM users WHERE email = ? LIMIT 1');
    if (!$existing) {
        header('Location: register.php?error=' . urlencode('Unable to process registration.'));
        exit;
    }
    mysqli_stmt_bind_param($existing, 's', $email);
    mysqli_stmt_execute($existing);
    mysqli_stmt_store_result($existing);

    if (mysqli_stmt_num_rows($existing) > 0) {
        mysqli_stmt_close($existing);
        mysqli_close($conn);
        header('Location: register.php?error=' . urlencode('An account already exists for this email.'));
        exit;
    }

    mysqli_stmt_close($existing);

    $passwordHash = password_hash($password, PASSWORD_BCRYPT);

    $stmt = mysqli_prepare($conn, 'INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)');
    if (!$stmt) {
        header('Location: register.php?error=' . urlencode('Unable to create account.'));
        exit;
    }
    mysqli_stmt_bind_param($stmt, 'ssss', $name, $email, $passwordHash, $role);

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        header('Location: login.php?message=' . urlencode('Account created successfully. Please login.'));
        exit;
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    header('Location: register.php?error=' . urlencode('Unable to create account.'));
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AnimalCare Hub - Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="auth-body">

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
            <li><a href="adoption.php">🏠 Adoption</a></li>
            <li><a href="search.php">🔍 Search</a></li>
        </ul>
    </div>
</nav>

<main class="auth-page">
    <section class="auth-shell">
        <section class="auth-panel auth-panel-image">
            <div class="auth-image-wrap">
                <span class="image-badge">AnimalCare</span>
                <div class="auth-image">
                    <span class="auth-image-emoji">🐾</span>
                </div>
                <div class="image-card-grid">
                    <div class="image-card">
                        <span class="image-card-icon">🌍</span>
                        <span>Multi-language</span>
                    </div>
                    <div class="image-card">
                        <span class="image-card-icon">⚡</span>
                        <span>Realtime Flow</span>
                    </div>
                </div>
            </div>
        </section>

        <section class="auth-panel auth-panel-form">
            <div class="auth-form-wrap">
                <div class="auth-heading">
                    <span class="hero-label">CREATE ACCOUNT</span>
                    <h2>Register</h2>
                    <p>Create a staff account for AnimalCare Hub.</p>
                </div>

                <form class="auth-form" method="post" action="register.php">
                    <div class="form-grid auth-grid">
                        <div class="form-group">
                            <label for="name">Full name</label>
                            <input type="text" id="name" name="name" placeholder="John Doe" required>
                        </div>

                        <div class="form-group">
                            <label for="role">Role</label>
                            <select id="role" name="role" required>
                                <option value="">Select role</option>
                                <option>Care Team</option>
                                <option>Veterinarian</option>
                                <option>Administrator</option>
                                <option>Volunteer</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="email">Email address</label>
                            <input type="email" id="email" name="email" placeholder="name@animalcarehub.com" required>
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" placeholder="••••••••" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <label class="check-row">
                            <input type="checkbox" name="terms" required>
                            <span>I accept the privacy policy</span>
                        </label>
                    </div>

                    <button type="submit" class="primary-btn auth-btn">Create Account</button>
                </form>

                <div class="auth-divider"><span>or</span></div>

                <div class="auth-extra">
                    <span>Already registered?</span>
                    <a href="login.php" class="secondary-btn small-btn">Login here</a>
                </div>
            </div>
        </section>
    </section>
</main>

<footer class="main-footer">
    <div class="container footer-content">
        <div class="footer-left">
            <h3>AnimalCare Hub</h3>
            <p>Rescue • Treatment • Vaccination • Adoption</p>
        </div>
        <div class="footer-right">
            <p>© 2026 AnimalCare Hub</p>
        </div>
    </div>
</footer>

<script src="script.js"></script>
</body>
</html>
