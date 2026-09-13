<?php
require 'config.php';

$animals = mysqli_query(
    $conn,
    "SELECT animal_id, animal_name, animal_type
     FROM animals
     WHERE adoption_status = 'Available'
     ORDER BY animal_name ASC"
);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>AnimalCare Hub - New Adoption Request</title>

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
            <li><a href="vaccinations.php">💉 Vaccinations</a></li>
            <li><a href="adoption.php" class="active">🏠 Adoption</a></li>
            <li><a href="search.php">🔍 Search</a></li>
        </ul>
    </div>
</nav>


<main class="page-container">

    <section class="form-page">

        <div class="form-heading">

            <div class="page-title-content">

                <span class="title-icon">🏠</span>

                <div>
                    <h2>New Adoption Request</h2>
                    <p>Submit an adoption application for an available animal.</p>
                </div>

            </div>

            <a href="adoption.php" class="secondary-btn">
                ← Back to Adoptions
            </a>

        </div>


        <form action="save_adoption.php" method="POST">

            <div class="form-section">

                <h3>Animal Selection</h3>

                <p class="section-description">
                    Select the animal the applicant wants to adopt.
                </p>

                <div class="form-grid">

                    <div class="form-group full-width">

                        <label for="animal_id">
                            Animal <span>*</span>
                        </label>

                        <select
                            id="animal_id"
                            name="animal_id"
                            required
                        >

                            <option value="">
                                -- Select Available Animal --
                            </option>

                            <?php while ($animal = mysqli_fetch_assoc($animals)): ?>

                                <option value="<?php echo (int)$animal['animal_id']; ?>">

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

                </div>

            </div>


            <div class="form-section">

                <h3>Applicant Information</h3>

                <p class="section-description">
                    Enter the applicant's contact information.
                </p>

                <div class="form-grid">

                    <div class="form-group">

                        <label for="applicant_name">
                            Applicant Name <span>*</span>
                        </label>

                        <input
                            type="text"
                            id="applicant_name"
                            name="applicant_name"
                            placeholder="e.g. Rahul Joshi"
                            required
                            maxlength="100"
                            pattern="[A-Za-z .]+"
                        >

                    </div>


                    <div class="form-group">

                        <label for="email">
                            Email Address <span>*</span>
                        </label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            placeholder="e.g. rahul@example.com"
                            required
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
                        >

                    </div>


                    <div class="form-group">

                        <label for="city">
                            City <span>*</span>
                        </label>

                        <input
                            type="text"
                            id="city"
                            name="city"
                            placeholder="e.g. Amravati"
                            required
                            maxlength="100"
                        >

                    </div>

                </div>

            </div>


            <div class="form-section">

                <h3>Adoption Details</h3>

                <p class="section-description">
                    Provide the request date and reason for adoption.
                </p>

                <div class="form-grid">

                    <div class="form-group">

                        <label for="request_date">
                            Request Date <span>*</span>
                        </label>

                        <input
                            type="date"
                            id="request_date"
                            name="request_date"
                            required
                        >

                    </div>


                    <div class="form-group">

                        <label for="request_status">
                            Request Status <span>*</span>
                        </label>

                        <select
                            id="request_status"
                            name="request_status"
                            required
                        >

                            <option value="Pending">
                                Pending
                            </option>

                            <option value="Approved">
                                Approved
                            </option>

                            <option value="Rejected">
                                Rejected
                            </option>

                        </select>

                    </div>


                    <div class="form-group full-width">

                        <label for="reason">
                            Reason for Adoption
                        </label>

                        <textarea
                            id="reason"
                            name="reason"
                            rows="5"
                            maxlength="1000"
                            placeholder="Explain why you want to adopt this animal..."
                        ></textarea>

                    </div>

                </div>

            </div>


            <div class="form-actions">

                <a href="adoption.php" class="secondary-btn">
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
                    + Submit Adoption Request
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