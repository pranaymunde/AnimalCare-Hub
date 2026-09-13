<?php
require 'config.php';

$stmt = mysqli_prepare($conn, 'SELECT animal_id, animal_name, animal_type, breed, age, gender, category, health_status, adoption_status FROM animals ORDER BY animal_id DESC');
if (!$stmt) die('Unable to load animal records.');
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$message = trim($_GET['message'] ?? '');
$error = trim($_GET['error'] ?? '');
?>
<!DOCTYPE html>
<html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>AnimalCare Hub - Animals</title><link rel="stylesheet" href="style.css"></head>
<body>
<header class="main-header"><div class="container header-content"><div class="logo-area"><div class="logo-icon">🐾</div><div><h1>AnimalCare Hub</h1><p>Animal Rescue & Care Management System</p></div></div><div class="header-auth"><?php if (isset($_SESSION['user_id'])): ?><div class="top-profile-panel"><div class="top-profile-avatar">👤</div><div class="top-profile-text"><span class="top-profile-name"><?= htmlspecialchars($_SESSION['name'] ?? 'Care Team Member') ?></span><span class="top-profile-role"><?= htmlspecialchars($_SESSION['role'] ?? 'Team Member') ?></span></div></div><div class="top-profile-links"><a href="logout.php" class="top-logout-link">Logout</a></div><?php else: ?><div class="top-profile-panel"><div class="top-profile-avatar">👤</div><div class="top-profile-text"><span class="top-profile-name">Guest</span><span class="top-profile-role">Visitor</span></div></div><div class="top-profile-links"><a href="login.php" class="top-login-link">Login</a><a href="register.php" class="top-register-link">Sign Up</a></div><?php endif; ?></div></div></header>
<nav class="navbar"><div class="container nav-inner"><ul class="nav-list"><li><a href="index.php">Home</a></li><li><a href="animals.php" class="active">🐾 Animals</a></li><li><a href="veterinarians.php">🩺 Veterinary</a></li><li><a href="treatments.php">🩹 Treatments</a></li><li><a href="vaccinations.php">💉 Vaccinations</a></li><li><a href="adoption.php">🏠 Adoption</a></li><li><a href="search.php">🔍 Search</a></li></ul></div></nav>
<main><div class="container">
<?php if ($message): ?><div class="flash success">✓ <?= htmlspecialchars($message) ?></div><?php endif; ?>
<?php if ($error): ?><div class="flash error">⚠ <?= htmlspecialchars($error) ?></div><?php endif; ?>
<section class="page-banner"><div><span class="page-label">ANIMAL DATABASE</span><h2>Animal Records</h2><p>Add, edit, search and safely manage all registered animal records.</p></div><div class="banner-icon">🐕</div></section>
<section class="data-card"><div class="section-heading"><div><span>RECORDS</span><h2>All Animals</h2></div><div class="toolbar"><a class="secondary-btn" href="search.php">🔍 Search</a><a class="primary-btn small-btn" href="add_animal.php">+ Add Animal</a></div></div>
<div class="table-wrapper"><table><thead><tr><th>ID</th><th>Name</th><th>Type</th><th>Breed</th><th>Age</th><th>Gender</th><th>Category</th><th>Health</th><th>Adoption</th><th>Actions</th></tr></thead><tbody>
<?php if (mysqli_num_rows($result)): while ($row = mysqli_fetch_assoc($result)): ?><tr><td><?= (int)$row['animal_id'] ?></td><td><strong><?= htmlspecialchars($row['animal_name']) ?></strong></td><td><?= htmlspecialchars($row['animal_type']) ?></td><td><?= htmlspecialchars($row['breed'] ?? '') ?></td><td><?= (int)$row['age'] ?></td><td><?= htmlspecialchars($row['gender']) ?></td><td><?= htmlspecialchars($row['category']) ?></td><td><span class="status-badge"><?= htmlspecialchars($row['health_status']) ?></span></td><td><span class="status-badge"><?= htmlspecialchars($row['adoption_status']) ?></span></td><td class="action-cell"><a class="edit-btn" href="edit_animal.php?animal_id=<?= (int)$row['animal_id'] ?>">✏ Edit</a><form action="delete_animal.php" method="POST" class="inline-delete" onsubmit="return confirm('Delete this animal and its related treatment, vaccination and adoption records?');"><input type="hidden" name="animal_id" value="<?= (int)$row['animal_id'] ?>"><button class="delete-btn" type="submit">🗑 Delete</button></form></td></tr><?php endwhile; else: ?><tr><td colspan="10" class="empty-state">No animal records yet. Click “Add Animal” to create the first record.</td></tr><?php endif; ?>
</tbody></table></div></section></div></main><footer class="site-footer"><p>© 2026 AnimalCare Hub | DBMS Mini Project</p></footer><script src="script.js"></script></body></html>
<?php mysqli_stmt_close($stmt); mysqli_close($conn); ?>
