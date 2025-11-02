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
    <div class="header">
        <a class="logo" href="./index.php">UPB HSO</a>
        <ul class="links">
            <li><a href="./index.php">Home</a></li>
            <li><a href="./consultation.php">Consultations</a></li>
            <li><a href="./patient.php">Patients</a></li>
            <li><a href="./staff.php">Staff</a></li>
            <li><a href="#footer">Contact</a></li>
        </ul>
    </div>

    <div class="pending-consultations-container">
        <h3>Pending Consultations</h3>
    </div>

    <h3>Consultation History</h3>

    <div class="consultations-table-container">
        <div class="consultations-actions">
            <a>Add new consultation</a>
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
                            <td>
                                <a class='action view'>View</a>
                                <a class='action edit'>Edit</a>
                                <a class='action delete'>Delete</a>
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
</body>
</html>
