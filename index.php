<?php
 include("database.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HSO Database</title>
    <link rel="stylesheet" href="./style.css">
    <script src="https://kit.fontawesome.com/ea8c838e77.js" crossorigin="anonymous"></script>
</head>
<body>

<?php include('header.php') ?>

<div class="about-container">
    <div class="section-title">About the Clinic</div>
    <div class="description">
    Welcome to the database website of TBA Clinic. This platform is designed to streamline the clinic's operations by providing efficient management of patients, doctors, and consultations. Our goal is to deliver fast, organized, and reliable access to clinic data for staff and administrators.
    </div>
    <div class="section-title" style="margin-top: 40px;">Clinic Statistics</div>
    <div class="stats">
        <div class="stat-card">
            <div class="stat-number" id='total-consultations'>0</div>
            <div class="stat-label">Total Consultations</div>
        </div>
        <div class="stat-card">
            <div class="stat-number" id='total-doctors'>0</div>
            <div class="stat-label">Total Doctors</div>
        </div>
        <div class="stat-card">
            <div class="stat-number" id='total-patients'>0</div>
            <div class="stat-label">Total Patients</div>
        </div>
    </div>
    <div class="actions">
        <div class="section-title">Quick Actions</div>
        <div class="action-buttons">
            <button class="action-btn"><i class="fa-solid fa-book-medical"></i> Add Consultation</button>
            <button class="action-btn"><i class="fa-solid fa-user-doctor"></i> Add Doctor</button>
            <button class="action-btn"><i class="fa-solid fa-user"></i> Add Patient</button>
        </div>
    </div>
</div>

<?php include('footer.php') ?>

<script src='./script-homepage.js'></script>
</body>
</html>