<?php
require 'config.php';

$animals = mysqli_query(
    $conn,
    "SELECT animal_id, animal_name, animal_type
     FROM animals
     ORDER BY animal_name ASC"
);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>AnimalCare Hub - Add Vaccination</title>

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
            <li><a href="veterinarians.php">🩺 Veterinary</a></li>
            <li><a href="treatments.php">🩹 Treatments</a></li>
            <li><a href="vaccinations.php" class="active">💉 Vaccinations</a></li>
            <li><a href="adoption.php">🏠 Adoption</a></li>
            <li><a href="search.php">🔍 Search</a></li>
        </ul>
    </div>
</nav>


<main class="page-container">

    <section class="form-page">

        <div class="form-heading">

            <div class="page-title-content">

                <span class="title-icon">💉</span>

                <div>
                    <h2>Add Vaccination</h2>

                    <p>
                        Record vaccination details for an animal.
                    </p>
                </div>

            </div>


            <a
                href="vaccinations.php"
                class="secondary-btn"
            >
                ← Back to Vaccinations
            </a>

        </div>


        <form
            action="save_vaccination.php"
            method="POST"
        >

            <!-- Animal Information -->

            <div class="form-section">

                <h3>Animal Information</h3>

                <p class="section-description">
                    Select the animal receiving the vaccination.
                </p>


                <div class="form-grid">

                    <div class="form-group">

                        <label for="animal_id">
                            Animal <span>*</span>
                        </label>


                        <select
                            id="animal_id"
                            name="animal_id"
                            required
                        >

                            <option value="">
                                -- Select Animal --
                            </option>


                            <?php while ($animal = mysqli_fetch_assoc($animals)): ?>

                                <option
                                    value="<?php
                                    echo (int)$animal['animal_id'];
                                    ?>"
                                >

                                    <?php
                                    echo htmlspecialchars(
                                        $animal['animal_name']
                                        . " ("
                                        . $animal['animal_type']
                                        . ")"
                                    );
                                    ?>

                                </option>

                            <?php endwhile; ?>

                        </select>

                    </div>


                    <div class="form-group">

                        <label for="vaccine_name">
                            Vaccine Name <span>*</span>
                        </label>


                        <input
                            type="text"
                            id="vaccine_name"
                            name="vaccine_name"
                            placeholder="e.g. Rabies"
                            required
                            maxlength="100"
                        >

                    </div>

                </div>

            </div>


            <!-- Vaccination Details -->

            <div class="form-section">

                <h3>Vaccination Details</h3>

                <p class="section-description">
                    Enter vaccination date, next due date and current status.
                </p>


                <div class="form-grid">

                    <div class="form-group">

                        <label for="vaccination_date">
                            Vaccination Date <span>*</span>
                        </label>


                        <input
                            type="date"
                            id="vaccination_date"
                            name="vaccination_date"
                            required
                        >

                    </div>


                    <div class="form-group">

                        <label for="next_due_date">
                            Next Due Date
                        </label>


                        <input
                            type="date"
                            id="next_due_date"
                            name="next_due_date"
                        >

                    </div>


                    <div class="form-group">

                        <label for="vaccination_status">
                            Vaccination Status <span>*</span>
                        </label>


                        <select
                            id="vaccination_status"
                            name="vaccination_status"
                            required
                        >

                            <option value="">
                                -- Select Status --
                            </option>

                            <option value="Completed">
                                Completed
                            </option>

                            <option value="Due Soon">
                                Due Soon
                            </option>

                            <option value="Pending">
                                Pending
                            </option>

                        </select>

                    </div>

                </div>

            </div>


            <!-- Form Buttons -->

            <div class="form-actions">

                <a
                    href="vaccinations.php"
                    class="secondary-btn"
                >
                    Cancel
                </a>


                <button
                    type="reset"
                    class="reset-btn"
                >
                    Clear
                </button>


                <button
                    type="submit"
                    class="primary-btn"
                >
                    + Save Vaccination
                </button>

            </div>

        </form>

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