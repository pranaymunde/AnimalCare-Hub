<?php
require 'config.php';
$animal_id = filter_var($_GET['animal_id'] ?? null, FILTER_VALIDATE_INT);
if (!$animal_id || $animal_id < 1) { header('Location: animals.php?error=' . urlencode('Invalid animal ID.')); exit; }
$stmt = mysqli_prepare($conn, 'SELECT * FROM animals WHERE animal_id = ? LIMIT 1');
if (!$stmt) { header('Location: animals.php?error=' . urlencode('Unable to load animal record.')); exit; }
mysqli_stmt_bind_param($stmt, 'i', $animal_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$animal = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);
if (!$animal) { mysqli_close($conn); header('Location: animals.php?error=' . urlencode('Animal record not found.')); exit; }
function e($v) { return htmlspecialchars((string)($v ?? ''), ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>AnimalCare Hub - Edit Animal</title><link rel="stylesheet" href="style.css"></head>
<body>
<header class="main-header"><div class="container header-content"><div class="logo-area"><div class="logo-icon">🐾</div><div><h1>AnimalCare Hub</h1><p>Animal Rescue & Care Management System</p></div></div><div class="header-auth"><?php if (isset($_SESSION['user_id'])): ?><div class="top-profile-panel"><div class="top-profile-avatar">👤</div><div class="top-profile-text"><span class="top-profile-name"><?= htmlspecialchars($_SESSION['name'] ?? 'Care Team Member') ?></span><span class="top-profile-role"><?= htmlspecialchars($_SESSION['role'] ?? 'Team Member') ?></span></div></div><div class="top-profile-links"><a href="logout.php" class="top-logout-link">Logout</a></div><?php else: ?><div class="top-profile-panel"><div class="top-profile-avatar">👤</div><div class="top-profile-text"><span class="top-profile-name">Guest</span><span class="top-profile-role">Visitor</span></div></div><div class="top-profile-links"><a href="login.php" class="top-login-link">Login</a><a href="register.php" class="top-register-link">Sign Up</a></div><?php endif; ?></div></div></header>
<nav class="navbar"><div class="container nav-inner"><ul class="nav-list"><li><a href="index.php">Home</a></li><li><a href="animals.php" class="active">🐾 Animals</a></li><li><a href="veterinarians.php">🩺 Veterinary</a></li><li><a href="treatments.php">🩹 Treatments</a></li><li><a href="vaccinations.php">💉 Vaccinations</a></li><li><a href="adoption.php">🏠 Adoption</a></li><li><a href="search.php">🔍 Search</a></li></ul></div></nav>
<main><div class="container"><section class="form-page"><div class="form-heading"><span class="page-label">ANIMAL DATABASE</span><h2>Edit Animal Record</h2><p>Update the details for <strong><?= e($animal['animal_name']) ?></strong>. Changes are saved using a prepared SQL UPDATE.</p></div>
<form action="update_animal.php" method="POST" class="animal-form"><input type="hidden" name="animal_id" value="<?= (int)$animal['animal_id'] ?>">
<div class="form-section"><h3>Basic Information</h3><div class="form-grid">
<div class="form-group"><label for="animal_name">Animal Name <span>*</span></label><input id="animal_name" name="animal_name" type="text" required minlength="2" maxlength="100" value="<?= e($animal['animal_name']) ?>"></div>
<div class="form-group"><label for="animal_type">Animal Type <span>*</span></label><select id="animal_type" name="animal_type" required><option value="">Select type</option><?php foreach(['Dog','Cat','Bird','Rabbit','Other'] as $v): ?><option value="<?= e($v) ?>" <?= $animal['animal_type']===$v?'selected':'' ?>><?= e($v) ?></option><?php endforeach; ?></select></div>
<div class="form-group"><label for="breed">Breed</label><input id="breed" name="breed" type="text" maxlength="100" value="<?= e($animal['breed']) ?>"></div>
<div class="form-group"><label for="age">Age <span>*</span></label><input id="age" name="age" type="number" min="0" max="100" required value="<?= e($animal['age']) ?>"></div>
<div class="form-group"><label for="gender">Gender <span>*</span></label><select id="gender" name="gender" required><?php foreach(['Male','Female'] as $v): ?><option value="<?= e($v) ?>" <?= $animal['gender']===$v?'selected':'' ?>><?= e($v) ?></option><?php endforeach; ?></select></div>
<div class="form-group"><label for="category">Category <span>*</span></label><select id="category" name="category" required><?php foreach(['Pet','Rescue','Shelter','Zoo/Exotic'] as $v): ?><option value="<?= e($v) ?>" <?= $animal['category']===$v?'selected':'' ?>><?= e($v) ?></option><?php endforeach; ?></select></div>
</div></div>
<div class="form-section"><h3>Health & Adoption</h3><div class="form-grid">
<div class="form-group"><label for="rescue_date">Rescue Date</label><input id="rescue_date" name="rescue_date" type="date" value="<?= e($animal['rescue_date']) ?>"></div>
<div class="form-group"><label for="health_status">Health Status <span>*</span></label><select id="health_status" name="health_status" required><?php foreach(['Healthy','Under Treatment','Recovered','Needs Attention','Recovering'] as $v): ?><option value="<?= e($v) ?>" <?= $animal['health_status']===$v?'selected':'' ?>><?= e($v) ?></option><?php endforeach; ?></select></div>
<div class="form-group"><label for="adoption_status">Adoption Status <span>*</span></label><select id="adoption_status" name="adoption_status" required><?php foreach(['Available','Pending','Adopted','Not Available'] as $v): ?><option value="<?= e($v) ?>" <?= $animal['adoption_status']===$v?'selected':'' ?>><?= e($v) ?></option><?php endforeach; ?></select></div>
<div class="form-group full-width"><label for="description">Description</label><textarea id="description" name="description" rows="4" maxlength="1000"><?= e($animal['description']) ?></textarea></div>
</div></div><div class="form-actions"><a href="animals.php" class="secondary-btn">← Back</a><button type="submit" class="primary-btn">✓ Update Animal</button></div></form></section></div></main><footer class="site-footer"><p>© 2026 AnimalCare Hub | DBMS Mini Project</p></footer><script src="script.js"></script></body></html>
<?php mysqli_close($conn); ?>
