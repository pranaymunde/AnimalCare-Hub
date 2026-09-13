<?php
require 'config.php';

$animals = mysqli_query(
    $conn,
    "SELECT animal_id, animal_name, animal_type
     FROM animals
     ORDER BY animal_name ASC"
);

$veterinarians = mysqli_query(
    $conn,
    "SELECT vet_id, vet_name, specialization
     FROM veterinarians
     ORDER BY vet_name ASC"
);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>AnimalCare Hub - Add Treatment</title>

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
            <li><a href="treatments.php" class="active">🩹 Treatments</a></li>
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

                <span class="title-icon">🩺</span>

                <div>
                    <h2>Add Treatment Record</h2>
                    <p>Record veterinary treatment and diagnosis for an animal.</p>
                </div>

            </div>

            <a href="treatments.php" class="secondary-btn">
                ← Back to Treatments
            </a>

        </div>


        <form action="save_treatment.php" method="POST">

            <!-- Animal & Veterinarian -->

            <div class="form-section">

                <h3>Patient & Veterinarian</h3>

                <p class="section-description">
                    Select the animal receiving treatment and the responsible veterinarian.
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

                                <option value="<?php echo (int)$animal['animal_id']; ?>">

                                    <?php
                                    echo htmlspecialchars(
                                        $animal['animal_name'] .
                                        " (" .
                                        $animal['animal_type'] .
                                        ")"
                                    );
                                    ?>

                                </option>

                            <?php endwhile; ?>

                        </select>

                    </div>


                    <div class="form-group">

                        <label for="vet_id">
                            Veterinarian <span>*</span>
                        </label>

                        <select
                            id="vet_id"
                            name="vet_id"
                            required
                        >

                            <option value="">
                                -- Select Veterinarian --
                            </option>

                            <?php while ($vet = mysqli_fetch_assoc($veterinarians)): ?>

                                <option value="<?php echo (int)$vet['vet_id']; ?>">

                                    <?php
                                    echo htmlspecialchars(
                                        $vet['vet_name'] .
                                        " - " .
                                        $vet['specialization']
                                    );
                                    ?>

                                </option>

                            <?php endwhile; ?>

                        </select>

                    </div>


                    <div class="form-group">

                        <label for="treatment_date">
                            Treatment Date <span>*</span>
                        </label>

                        <input
                            type="date"
                            id="treatment_date"
                            name="treatment_date"
                            required
                        >

                    </div>


                    <div class="form-group">

                        <label for="treatment_status">
                            Treatment Status <span>*</span>
                        </label>

                        <select
                            id="treatment_status"
                            name="treatment_status"
                            required
                        >

                            <option value="">
                                -- Select Status --
                            </option>

                            <option value="Ongoing">
                                Ongoing
                            </option>

                            <option value="Completed">
                                Completed
                            </option>

                        </select>

                    </div>

                </div>

            </div>


            <!-- Diagnosis -->

            <div class="form-section">

                <h3>Medical Details</h3>

                <p class="section-description">
                    Enter the diagnosis and treatment information.
                </p>


                <div class="form-grid">

                    <div class="form-group full-width">

                        <label for="diagnosis">
                            Diagnosis <span>*</span>
                        </label>

                        <input
                            type="text"
                            id="diagnosis"
                            name="diagnosis"
                            placeholder="e.g. Skin allergy"
                            required
                            maxlength="255"
                        >

                    </div>


                    <div class="form-group full-width">

                        <label for="treatment_description">
                            Treatment Description
                        </label>

                        <textarea
                            id="treatment_description"
                            name="treatment_description"
                            rows="5"
                            maxlength="1000"
                            placeholder="Describe the treatment, medication, advice or follow-up..."
                        ></textarea>

                    </div>

                </div>

            </div>


            <!-- Buttons -->

            <div class="form-actions">

                <a href="treatments.php" class="secondary-btn">
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
                    + Save Treatment
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

<?php
mysqli_close($conn);
?>