<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

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
        header('Location: login.php?error=' . urlencode('Unable to initialize the account system.'));
        exit;
    }

    if ($email === '' || $password === '') {
        header('Location: login.php?error=' . urlencode('Please enter your email and password.'));
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header('Location: login.php?error=' . urlencode('Please enter a valid email address.'));
        exit;
    }

    $stmt = mysqli_prepare($conn, 'SELECT user_id, name, email, password, role FROM users WHERE email = ? LIMIT 1');
    if (!$stmt) {
        header('Location: login.php?error=' . urlencode('Unable to process login.'));
        exit;
    }
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $user_id, $name, $storedEmail, $storedPassword, $role);

    if (!mysqli_stmt_fetch($stmt)) {
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        header('Location: login.php?error=' . urlencode('Invalid email or password.'));
        exit;
    }

    mysqli_stmt_close($stmt);

    if (!password_verify($password, $storedPassword)) {
        mysqli_close($conn);
        header('Location: login.php?error=' . urlencode('Invalid email or password.'));
        exit;
    }

    $_SESSION['user_id'] = $user_id;
    $_SESSION['name'] = $name;
    $_SESSION['email'] = $storedEmail;
    $_SESSION['role'] = $role;

    mysqli_close($conn);
    header('Location: index.php?message=' . urlencode('Welcome back, ' . $name . '.'));
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AnimalCare Hub - Login</title>
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
                    <span class="auth-image-emoji">🐕</span>
                </div>
                <div class="image-card-grid">
                    <div class="image-card">
                        <span class="image-card-icon">🏥</span>
                        <span>Care Team</span>
                    </div>
                    <div class="image-card">
                        <span class="image-card-icon">📋</span>
                        <span>Records</span>
                    </div>
                </div>
            </div>
        </section>

        <section class="auth-panel auth-panel-form">
            <div class="auth-form-wrap">
                <div class="auth-heading">
                    <span class="hero-label">WELCOME BACK</span>
                    <h2>Sign in</h2>
                    <p>Access your AnimalCare Hub dashboard.</p>
                </div>

                <form class="auth-form" method="post" action="login.php">
                    <div class="form-group">
                        <label for="email"><span>Email address</span></label>
                        <input type="email" id="email" name="email" placeholder="admin@animalcarehub.com" required>
                    </div>

                    <div class="form-group">
                        <label for="password"><span>Password</span></label>
                        <input type="password" id="password" name="password" placeholder="••••••••" required>
                    </div>

                    <div class="form-row">
                        <label class="check-row">
                            <input type="checkbox" name="remember">
                            <span>Remember me</span>
                        </label>
                        <a class="tiny-link" href="#">Forgot password?</a>
                    </div>

                    <button type="submit" class="primary-btn auth-btn">Login to Dashboard</button>
                </form>

                <div class="auth-divider"><span>or</span></div>

                <div class="auth-extra">
                    <span>No account yet?</span>
                    <a href="register.php" class="secondary-btn small-btn">Create an account</a>
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
