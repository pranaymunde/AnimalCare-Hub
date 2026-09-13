<?php
require 'config.php';

$searchKeyword = trim($_GET['q'] ?? '');
$animalTypeFilter = trim($_GET['type'] ?? '');
$healthStatusFilter = trim($_GET['health'] ?? '');
$adoptionStatusFilter = trim($_GET['adoption'] ?? '');

$sql = 'SELECT animal_id, animal_name, animal_type, breed, age, gender,
               category, health_status, adoption_status
        FROM animals WHERE 1=1';
$params = [];
$types = '';

if ($searchKeyword !== '') {
    $sql .= ' AND (animal_name LIKE ? OR breed LIKE ?)';
    $like = '%' . $searchKeyword . '%';
    $params[] = $like;
    $params[] = $like;
    $types .= 'ss';
}
if ($animalTypeFilter !== '') {
    $sql .= ' AND animal_type = ?';
    $params[] = $animalTypeFilter;
    $types .= 's';
}
if ($healthStatusFilter !== '') {
    $sql .= ' AND health_status = ?';
    $params[] = $healthStatusFilter;
    $types .= 's';
}
if ($adoptionStatusFilter !== '') {
    $sql .= ' AND adoption_status = ?';
    $params[] = $adoptionStatusFilter;
    $types .= 's';
}
$sql .= ' ORDER BY animal_id DESC';

$stmt = mysqli_prepare($conn, $sql);
if (!$stmt) {
    die('Unable to process search.');
}
if ($params) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$count = mysqli_num_rows($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>AnimalCare Hub - Search Animals</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<header class="main-header">
  <div class="container header-content">
    <div class="logo-area"><div class="logo-icon">🐾</div><div><h1>AnimalCare Hub</h1><p>Animal Rescue & Care Management System</p></div></div>
    <div class="header-auth"><?php if (isset($_SESSION['user_id'])): ?><div class="top-profile-panel"><div class="top-profile-avatar">👤</div><div class="top-profile-text"><span class="top-profile-name"><?= htmlspecialchars($_SESSION['name'] ?? 'Care Team Member') ?></span><span class="top-profile-role"><?= htmlspecialchars($_SESSION['role'] ?? 'Team Member') ?></span></div></div><div class="top-profile-links"><a href="logout.php" class="top-logout-link">Logout</a></div><?php else: ?><div class="top-profile-panel"><div class="top-profile-avatar">👤</div><div class="top-profile-text"><span class="top-profile-name">Guest</span><span class="top-profile-role">Visitor</span></div></div><div class="top-profile-links"><a href="login.php" class="top-login-link">Login</a><a href="register.php" class="top-register-link">Sign Up</a></div><?php endif; ?></div>
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
      <li><a href="search.php" class="active">🔍 Search</a></li>
    </ul>
  </div>
</nav>
<main>
<div class="container">
  <section class="page-banner search-banner">
    <div><span class="page-label">ANIMAL DATABASE</span><h2>Search & Filter Animals</h2><p>Search by animal name or breed and narrow results using the filters below.</p></div>
    <div class="banner-icon">🔎</div>
  </section>

  <section class="data-card search-card">
    <form method="GET" action="search.php" class="search-form-grid">
      <div class="form-group"><label for="q">Name / Breed</label><input id="q" name="q" type="search" value="<?= htmlspecialchars($searchKeyword) ?>" placeholder="e.g. Bruno or Labrador"></div>
      <div class="form-group"><label for="type">Animal Type</label><select id="type" name="type"><option value="">All Types</option><?php foreach(['Dog','Cat','Bird','Rabbit','Other'] as $v): ?><option value="<?= $v ?>" <?= $animalTypeFilter === $v ? 'selected' : '' ?>><?= $v ?></option><?php endforeach; ?></select></div>
      <div class="form-group"><label for="health">Health Status</label><select id="health" name="health"><option value="">All Health Status</option><?php foreach(['Healthy','Under Treatment','Recovered','Needs Attention','Recovering'] as $v): ?><option value="<?= $v ?>" <?= $healthStatusFilter === $v ? 'selected' : '' ?>><?= $v ?></option><?php endforeach; ?></select></div>
      <div class="form-group"><label for="adoption">Adoption Status</label><select id="adoption" name="adoption"><option value="">All Adoption Status</option><?php foreach(['Available','Pending','Adopted','Not Available'] as $v): ?><option value="<?= $v ?>" <?= $adoptionStatusFilter === $v ? 'selected' : '' ?>><?= $v ?></option><?php endforeach; ?></select></div>
      <div class="search-actions"><button class="primary-btn" type="submit">🔍 Search</button><a class="secondary-btn" href="search.php">↺ Clear</a></div>
    </form>
  </section>

  <section class="data-card">
    <div class="section-heading"><div><span>RESULTS</span><h2><?= $count ?> matching record<?= $count === 1 ? '' : 's' ?></h2></div><a href="add_animal.php" class="primary-btn small-btn">+ Add Animal</a></div>
    <div class="table-wrapper"><table><thead><tr><th>ID</th><th>Name</th><th>Type</th><th>Breed</th><th>Age</th><th>Gender</th><th>Category</th><th>Health</th><th>Adoption</th><th>Actions</th></tr></thead><tbody>
    <?php if ($count): while ($row = mysqli_fetch_assoc($result)): ?>
      <tr><td><?= (int)$row['animal_id'] ?></td><td><strong><?= htmlspecialchars($row['animal_name']) ?></strong></td><td><?= htmlspecialchars($row['animal_type']) ?></td><td><?= htmlspecialchars($row['breed'] ?? '') ?></td><td><?= (int)$row['age'] ?></td><td><?= htmlspecialchars($row['gender']) ?></td><td><?= htmlspecialchars($row['category']) ?></td><td><span class="status-badge"><?= htmlspecialchars($row['health_status']) ?></span></td><td><span class="status-badge"><?= htmlspecialchars($row['adoption_status']) ?></span></td><td class="action-cell"><a class="edit-btn" href="edit_animal.php?animal_id=<?= (int)$row['animal_id'] ?>">Edit</a><form action="delete_animal.php" method="POST" class="inline-delete" onsubmit="return confirm('Delete this animal and its related records?');"><input type="hidden" name="animal_id" value="<?= (int)$row['animal_id'] ?>"><button class="delete-btn" type="submit">Delete</button></form></td></tr>
    <?php endwhile; else: ?><tr><td colspan="10" class="empty-state">No matching animals found. Try changing the filters.</td></tr><?php endif; ?>
    </tbody></table></div>
  </section>
</div>
</main>
<footer class="site-footer"><p>© 2026 AnimalCare Hub | DBMS Mini Project</p></footer>
<script src="script.js"></script>
</body></html>
<?php mysqli_stmt_close($stmt); mysqli_close($conn); ?>
