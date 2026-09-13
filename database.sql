CREATE DATABASE animalcare_hub;

USE animalcare_hub;


-- =========================================
-- ANIMALS TABLE
-- =========================================

CREATE TABLE animals (
    animal_id INT AUTO_INCREMENT PRIMARY KEY,
    animal_name VARCHAR(100) NOT NULL,
    animal_type VARCHAR(50) NOT NULL,
    breed VARCHAR(100),
    age INT NOT NULL,
    gender VARCHAR(20) NOT NULL,
    category VARCHAR(30) NOT NULL,
    rescue_date DATE,
    health_status VARCHAR(50) NOT NULL,
    adoption_status VARCHAR(30) NOT NULL DEFAULT 'Available',
    description TEXT
) ENGINE=InnoDB;


-- =========================================
-- VETERINARIANS TABLE
-- =========================================

CREATE TABLE veterinarians (
    vet_id INT AUTO_INCREMENT PRIMARY KEY,
    vet_name VARCHAR(100) NOT NULL,
    specialization VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    email VARCHAR(150) UNIQUE,
    clinic_name VARCHAR(150)
) ENGINE=InnoDB;


-- =========================================
-- TREATMENTS TABLE
-- =========================================

CREATE TABLE treatments (
    treatment_id INT AUTO_INCREMENT PRIMARY KEY,
    animal_id INT NOT NULL,
    vet_id INT NOT NULL,
    treatment_date DATE NOT NULL,
    diagnosis VARCHAR(255) NOT NULL,
    treatment_description TEXT NOT NULL,
    treatment_status VARCHAR(30) NOT NULL DEFAULT 'In Progress',

    FOREIGN KEY (animal_id)
        REFERENCES animals(animal_id),

    FOREIGN KEY (vet_id)
        REFERENCES veterinarians(vet_id)
) ENGINE=InnoDB;


-- =========================================
-- VACCINATIONS TABLE
-- =========================================

CREATE TABLE vaccinations (
    vaccination_id INT AUTO_INCREMENT PRIMARY KEY,
    animal_id INT NOT NULL,
    vaccine_name VARCHAR(100) NOT NULL,
    vaccination_date DATE NOT NULL,
    next_due_date DATE NOT NULL,
    vaccination_status VARCHAR(30) NOT NULL DEFAULT 'Completed',

    FOREIGN KEY (animal_id)
        REFERENCES animals(animal_id)
) ENGINE=InnoDB;


-- =========================================
-- ADOPTION REQUESTS TABLE
-- =========================================

CREATE TABLE adoption_requests (
    request_id INT AUTO_INCREMENT PRIMARY KEY,
    animal_id INT NOT NULL,
    applicant_name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    city VARCHAR(100) NOT NULL,
    request_date DATE NOT NULL,
    reason TEXT NOT NULL,
    request_status VARCHAR(30) NOT NULL DEFAULT 'Pending',

    FOREIGN KEY (animal_id)
        REFERENCES animals(animal_id)
) ENGINE=InnoDB;


-- =========================================
-- USERS TABLE FOR LOGIN / REGISTER
-- =========================================

CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(160) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(60) NOT NULL DEFAULT 'Care Team',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;


-- =========================================
-- SAMPLE ANIMAL DATA
-- =========================================

INSERT INTO animals
(animal_name, animal_type, breed, age, gender, category,
rescue_date, health_status, adoption_status, description)
VALUES

('Buddy', 'Dog', 'Golden Retriever', 3, 'Male',
 'Pet', '2021-05-01', 'Healthy', 'Available',
 'Friendly and playful dog.'),

('Mittens', 'Cat', 'Siamese', 2, 'Female',
 'Rescue', '2022-03-15', 'Healthy', 'Available',
 'Loves to cuddle and play.'),

('Tweety', 'Bird', 'Canary', 1, 'Male',
 'Pet', '2023-01-10', 'Healthy', 'Available',
 'Sings beautifully.'),

('Thumper', 'Rabbit', 'Holland Lop', 4, 'Female',
 'Shelter', '2020-11-20', 'Under Treatment', 'Pending',
 'Needs special care.'),

('Coco', 'Dog', 'Poodle', 5, 'Female',
 'Zoo/Exotic', '2019-07-30', 'Healthy', 'Available',
 'Intelligent and hypoallergenic.');


-- =========================================
-- SAMPLE VETERINARIAN DATA
-- =========================================

INSERT INTO veterinarians
(vet_name, specialization, phone, email, clinic_name)
VALUES

('Dr. Smith', 'General Practice',
 '123-456-7890',
 'drsmith@example.com',
 'Happy Paws Clinic'),

('Dr. Johnson', 'Surgery',
 '234-567-8901',
 'drjohnson@example.com',
 'Animal Care Center'),

('Dr. Lee', 'Dentistry',
 '345-678-9012',
 'drlee@example.com',
 'Pet Health Clinic');


-- =========================================
-- SAMPLE TREATMENT DATA
-- =========================================

INSERT INTO treatments
(animal_id, vet_id, treatment_date,
diagnosis, treatment_description, treatment_status)
VALUES

(1, 1, '2023-02-01',
 'Routine Checkup',
 'General health checkup and vaccinations.',
 'In Progress'),

(2, 2, '2023-02-15',
 'Dental Cleaning',
 'Teeth cleaning and examination.',
 'Completed'),

(3, 1, '2023-03-01',
 'Feather Loss',
 'Examination for feather loss and treatment.',
 'In Progress'),

(4, 1, '2023-03-10',
 'Ear Infection',
 'Treatment for ear infection.',
 'Completed'),

(5, 2, '2023-03-20',
 'Routine Checkup',
 'General health checkup.',
 'In Progress');


-- =========================================
-- SAMPLE VACCINATION DATA
-- =========================================

INSERT INTO vaccinations
(animal_id, vaccine_name,
vaccination_date, next_due_date, vaccination_status)
VALUES

(1, 'Rabies',
 '2023-01-01', '2024-01-01', 'Completed'),

(2, 'Feline Distemper',
 '2023-01-15', '2024-01-15', 'Completed'),

(3, 'Canary Pox',
 '2023-02-01', '2024-02-01', 'Completed'),

(4, 'Myxomatosis',
 '2023-02-10', '2024-02-10', 'Completed'),

(5, 'Parvovirus',
 '2023-03-01', '2024-03-01', 'Completed');


-- =========================================
-- SAMPLE ADOPTION DATA
-- =========================================

INSERT INTO adoption_requests
(animal_id, applicant_name, email, phone,
city, request_date, reason, request_status)
VALUES

(1, 'Alice',
 'alice@example.com',
 '555-1234',
 'New York',
 '2023-03-01',
 'Looking for a companion.',
 'Pending'),

(2, 'Bob',
 'bob@example.com',
 '555-5678',
 'Los Angeles',
 '2023-03-05',
 'Love cats.',
 'Pending'),

(3, 'Charlie',
 'charlie@example.com',
 '555-8765',
 'Chicago',
 '2023-03-10',
 'Want a pet bird.',
 'Pending'),

(4, 'Diana',
 'diana@example.com',
 '555-4321',
 'Houston',
 '2023-03-15',
 'Looking for a rabbit for my kids.',
 'Pending'),

(5, 'Eve',
 'eve@example.com',
 '555-6789',
 'Phoenix',
 '2023-03-20',
 'Interested in adopting a dog.',
 'Pending');