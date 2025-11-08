<?php
include("database.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultation History</title>
    <link rel="stylesheet" href="./style.css">
    <script src="https://kit.fontawesome.com/ea8c838e77.js" crossorigin="anonymous"></script>
</head>
<body>

<div id="add-consultation-modal" class="modal">
    <div class="modal-content">
        <div class="close-btn-div">
            <button class="close-btn" id="close-modal"><img class='btn-img' src="./img/close.svg"></button>
        </div>
        <div class="modal-message">

        </div>
        <div class='consultation-modal-actions'>
            <button class='action add' data-id=''>Add</button>
        </div>

    </div>
</div>

<div id="filter-consultation-modal" class="modal">
    <div class="modal-content">
        <div class="close-btn-div">
            <button class="close-btn" id="close-modal"><img class='btn-img' src="./img/close.svg"></button>
        </div>
        <div class="modal-message">

        </div>
        <div class='consultation-modal-actions'>
            <button class='action' data-id=''>Filter</button>
        </div>

    </div>
</div>

<div id="consultation-modal" class="modal">
    <div class="modal-content">
        <div class="close-btn-div">
            <button class="close-btn" id="close-modal"><img class='btn-img' src="./img/close.svg"></button>
        </div>
        <div class="modal-message">
            <h3>Consultation Details</h3>
            <h4>Date: <span id="consultation-date"></span></h4>
            <h4>Time: <span id="consultation-time"></span></h4>
            <h4>Patient First Name: <span id="patient-first-name">weeeeeeeeeeeeeeeeee</span></h4>
            <h4>Patient MI: <span id="patient-middle-initial"></span></h4>
            <h4>Patient Last Name: <span id="patient-last-name"></span></h4>
            <h4>Patient Age: <span id="patient-age"></span></h4>
            <h4>Diagnosis: <span id="diagnosis"></span></h4>
            <h4>Remarks: <span id="remarks"></span></h4>
            <h4>Prescription: <span id="prescription"></span></h4>
            <h4>Doctor: <span id="doctor-name"></span></h4>
        </div>
        <div class='consultation-modal-actions'>
            <button class='action edit' data-id=''>Edit</button>
            <button class='action delete' data-id=''>Delete</button>
        </div>

    </div>
</div>

<div id="edit-consultation-modal" class="modal">
    <div class="modal-content">
        <div class="close-btn-div">
            <button class="close-btn" id="close-modal"><img class='btn-img' src="./img/close.svg"></button>
        </div>
        <div class="modal-message">

        </div>
        <div class='consultation-modal-actions'>
            <button class='action add' data-id=''>Add</button>
        </div>

    </div>
</div>

<div id="delete-confirmation-modal" class='modal'>
    <div class='modal-content'>
        <div class="close-btn-div">
            <button class="close-btn" id="close-modal"><img class='btn-img' src="./img/close.svg"></button>
        </div>
        <div class='modal-message'>
            Are you sure you want to delete this consultation? This cannot be undone.
        </div>
        <div class='consultation-modal-actions'>
            <button class='action confirm-delete'>Yes</button>
            <button class='action cancel-delete'>No</button>
        </div>
    </div>
</div>

    <div class="header">
        <a id="hyperlink-logo" href="./index.php">
            <div class='header-img' id='logo'>
                <img id='logo-img' src='./img/logo.svg'>
                TBAClinic
            </div>
        </a>
        <ul class="links">
            <li><a href="./index.php">Home</a></li>
            <li><a href="./consultation.php">Consultations</a></li>
            <li><a href="./patient.php">Patients</a></li>
            <li><a href="./staff.php">Staff</a></li>
            <li><a href="#footer">Contact</a></li>
        </ul>
        <button id='mobile-menu-btn'><img class='header-img' src='./img/menu.svg'></button>
    </div>


    <div class="consultations-table-container">
        <div><h2 class='consultation-history'>Consultation History</h2></div>
        <div class="consultations-actions">
            <button class="consultations action" id='add-consultation-btn' href="#"><i class="fa-solid fa-plus"></i> <span>Add new consultation</span></button>
            <button class='consultations action' id='filter-consultation-btn'><i class="fa-solid fa-filter"></i> <span>Filter</span></button>
        </div>

        <table class="consultations-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Patient</th>
                    <th>Doctor</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $sql = "SELECT CONSULTATION.ConsultationID, CONSULTATION.ConsultDateTime,
                                   PATIENT.PatientFirstName, PATIENT.PatientMiddleInit, PATIENT.PatientLastName,
                                   DOCTOR.DocFirstName, DOCTOR.DocMiddleInit, DOCTOR.DocLastName
                            FROM PATIENT
                            INNER JOIN CONSULTATION ON PATIENT.PatientID = CONSULTATION.PatientID
                            INNER JOIN DOCTOR ON DOCTOR.DoctorID = CONSULTATION.DoctorID
                            ORDER BY CONSULTATION.ConsultDateTime DESC";
                    $result = $conn->query($sql); 
                    
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td data-label='Date'>" . date("M j, Y", strtotime($row["ConsultDateTime"])) . "</td>
                            <td data-label='Time'>" . date("g:i A", strtotime($row["ConsultDateTime"])) . "</td>
                            <td data-label='Patient'>" . $row["PatientFirstName"] . " " . $row["PatientMiddleInit"] . ". " . $row["PatientLastName"] . "</td>
                            <td data-label='Doctor'>" . $row["DocFirstName"] . " " . $row["DocMiddleInit"] . ". " . $row["DocLastName"] . "</td>
                            <td style='width:1%; white-space:nowrap;'>
                                <button href='#' class='action view' data-id='" . $row["ConsultationID"] . "'>View</button>
                            </td>
                        </tr>";
                    }
                ?>
            </tbody>
        </table>
    </div>

    <div id="footer">
        basta contact info
    </div>

    <script src="./script.js"></script>
</body>
</html>
