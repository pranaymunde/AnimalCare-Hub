<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>AnimalCare Hub - Add Veterinarian</title>

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

    <section class="form-page">

        <div class="form-heading">

            <div class="page-title-content">

                <span class="title-icon">👨‍⚕️</span>

                <div>
                    <h2>Add Veterinarian</h2>
                    <p>Register a veterinary professional in AnimalCare Hub.</p>
                </div>

            </div>

            <a href="veterinarians.php" class="secondary-btn">
                ← Back to Veterinarians
            </a>

        </div>


        <form action="save_veterinarian.php" method="POST">

            <div class="form-section">

                <h3>Veterinarian Information</h3>
                <p class="section-description">
                    Enter the basic details of the veterinarian.
                </p>


                <div class="form-grid">

                    <div class="form-group">

                        <label for="vet_name">
                            Veterinarian Name <span>*</span>
                        </label>

                        <input
                            type="text"
                            id="vet_name"
                            name="vet_name"
                            placeholder="e.g. Dr. Amit Sharma"
                            required
                            maxlength="100"
                            pattern="[A-Za-z .]+"
                            title="Please enter a valid name using letters, spaces and dots."
                        >

                    </div>


                    <div class="form-group">

                        <label for="specialization">
                            Specialization
                        </label>

                        <input
                            type="text"
                            id="specialization"
                            name="specialization"
                            placeholder="e.g. Veterinary Medicine"
                            maxlength="100"
                        >

                    </div>


                    <div class="form-group">

                        <label for="phone">
                            Phone Number <span>*</span>
                        </label>

                        <input
                            type="tel"
                            id="phone"
                            name="phone"
                            placeholder="e.g. 9876543210"
                            required
                            maxlength="20"
                            pattern="[0-9+ ()-]{10,20}"
                            title="Enter a valid phone number."
                        >

                    </div>


                    <div class="form-group">

                        <label for="email">
                            Email Address
                        </label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            placeholder="e.g. doctor@example.com"
                            maxlength="100"
                        >

                    </div>


                    <div class="form-group full-width">

                        <label for="clinic_name">
                            Clinic Name
                        </label>

                        <input
                            type="text"
                            id="clinic_name"
                            name="clinic_name"
                            placeholder="e.g. PetCare Veterinary Clinic"
                            maxlength="150"
                        >

                    </div>

                </div>

            </div>


            <div class="form-actions">

                <a href="veterinarians.php" class="secondary-btn">
                    Cancel
                </a>

                <button type="reset" class="reset-btn">
                    Clear
                </button>

                <button type="submit" class="primary-btn">
                    + Save Veterinarian
                </button>

            </div>

        </form>

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