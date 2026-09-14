# AnimalCare Hub

## Animal Rescue, Veterinary Care, Vaccination & Adoption Management System

---

## 1. Student Details

**Student Name:** Pranay Jagdish Munde  
**Roll Number:** MLU24F116  
**Division:** B  
**Year:** Third Year  
**Course:** B.Tech CSE (AIML)  
**Subject:** Database Management System  
**Project Title:** AnimalCare Hub  

---

## 2. Problem Statement

Animal rescue centers and animal-care organizations need to maintain information about rescued animals, veterinary treatments, vaccinations, and adoption requests.

Managing these records manually can make searching, updating, and tracking animal information difficult.

**AnimalCare Hub** is a database-driven web application developed using HTML, CSS, JavaScript, PHP, and MySQL.

The system provides a centralized platform to manage animal records, veterinary information, treatments, vaccinations, and adoption requests.

---

## 3. Objective

The main objective of AnimalCare Hub is to develop a secure and database-driven web application that demonstrates important Database Management System concepts.

The project demonstrates:

- HTML forms
- Client-side validation
- Server-side validation
- PHP backend processing
- MySQL database connectivity
- INSERT operations
- SELECT operations
- Search and filtering
- UPDATE operations
- DELETE operations
- Primary keys
- Foreign keys
- Table relationships
- INNER JOIN operations
- Prepared statements
- Parameter binding
- SQL injection protection
- XSS protection using `htmlspecialchars()`
- Password hashing
- Session-based authentication

---

## 4. Main Features

### 4.1 Animal Management

The system allows the user to:

- Add animal records
- View animal records
- Edit animal information
- Delete animal records
- Search animals
- Filter animals by type
- Filter animals by health status
- Filter animals by adoption status

Animal information includes:

- Animal name
- Animal type
- Breed
- Age
- Gender
- Category
- Rescue date
- Health status
- Adoption status
- Description

---

### 4.2 Veterinary Management

The veterinary module stores:

- Veterinarian name
- Specialization
- Phone number
- Email
- Clinic name

The system allows veterinary records to be added, viewed, updated, and deleted using database operations.

---

### 4.3 Treatment Management

The treatment module records:

- Animal
- Veterinarian
- Treatment date
- Diagnosis
- Treatment description
- Treatment status

Each treatment record is connected to an animal and a veterinarian using foreign keys.

---

### 4.4 Vaccination Management

The vaccination module records:

- Animal
- Vaccine name
- Vaccination date
- Next due date
- Vaccination status

Each vaccination record is connected to an animal using a foreign key.

---

### 4.5 Adoption Management

The adoption module records:

- Animal
- Applicant name
- Email
- Phone
- City
- Request date
- Reason for adoption
- Request status

The adoption request status can be updated by the user.

---

### 4.6 User Management

The system provides:

- User registration
- User login
- User logout
- Password hashing
- Session-based authentication

Passwords are stored using secure password hashing rather than plain text.

---

### 4.7 Live Dashboard

The homepage provides a live operations dashboard.

It displays the current number of:

- Animals
- Veterinarians
- Treatments
- Vaccinations
- Adoption requests
- Pending adoption requests

The dashboard retrieves the latest database counts through a PHP API and automatically refreshes the information every 5 seconds.

---

## 5. Technology Stack

### Frontend

- HTML5
- CSS3
- JavaScript

### Backend

- PHP

### Database

- MySQL

### Local Development

- XAMPP PHP
- PHP Built-in Development Server
- MySQL Server

### Hosting

- InfinityFree

---

## 6. System Architecture

The application follows a simple client-server and database-driven architecture.

```text
                 User
                   |
                   v
          HTML / CSS / JavaScript
                   |
                   v
             PHP Backend
                   |
                   v
            MySQL Database
                   |
            SQL Operations
                   |
                   v
             PHP Processing
                   |
                   v
          HTML / JSON Response
                   |
                   v
                Browser