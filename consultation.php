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
</head>
<body>

<div id="consultation-modal" class="modal">
    <div class="modal-content">
        <div class="close-btn-div">
            <button class="close-btn" id="close-modal"><img src="./img/close.svg"></button>
        </div>
        <div class="consultation-details">
            <h3>Consultation Details</h3>
            <h4>Date: <span id="consultation-date"></span></h4>
            <h4>Time: <span id="consultation-time"></span></h4>
            <h4>Patient First Name: <span id="patient-first-name">weeeeeeeeeeeeeeeeee</span></h4>
            <h4>Patient MI: <span id="patient-middle-initial"></span></h4>
            <h4>Patient Last Name: <span id="patient-last-name"></span></h4>
            <h4>Patient Age: <span id="patient-age"></span></h4>
            <h4>Diagnosis: <span id="diagnosis"></span></h4>
            <h4>Prescription: <span id="prescription"></span></h4>
            <h4>Doctor: <span id="doctor-name"></span></h4>
        </div>
        <div class='consultation-modal-actions'>
            <button class='action edit'>Edit</button>
            <button class='action delete'>Delete</button>
        </div>
    </div>
</div>

    <div class="header">
        <a class="logo" href="./index.php">HSO</a>
        <ul class="links">
            <li><a href="./index.php">Home</a></li>
            <li><a href="./consultation.php">Consultations</a></li>
            <li><a href="./patient.php">Patients</a></li>
            <li><a href="./staff.php">Staff</a></li>
            <li><a href="#footer">Contact</a></li>
        </ul>
    </div>


    <div class="consultations-table-container">
        <div><h2 class='consultation-history'>Consultation History</h2></div>
        <div class="consultations-actions">
            <a class="consultations action" href="#">Add new consultation</a>
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
                            <td>" . date("M j, Y", strtotime($row["ConsultDateTime"])) . "</td>
                            <td>" . date("g:i A", strtotime($row["ConsultDateTime"])) . "</td>
                            <td>" . $row["PatientFirstName"] . " " . $row["PatientMiddleInit"] . ". " . $row["PatientLastName"] . "</td>
                            <td>" . $row["DocFirstName"] . " " . $row["DocMiddleInit"] . ". " . $row["DocLastName"] . "</td>
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
