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
    <table border='1'>
        <tr>
            <th>Date</th>
            <th>Time</th>
            <th>Patient</th>
            <th>Doctor</th>
        </tr>
        <?php
            $sql = "SELECT CONSULTATION.ConsultationID, CONSULTATION.ConsultDateTime, PATIENT.PatientFirstName, PATIENT.PatientMiddleInit, PATIENT.PatientLastName, DOCTOR.DocFirstName, DOCTOR.DocMiddleInit, DOCTOR.DocLastName
                    FROM PATIENT INNER JOIN CONSULTATION
                    ON PATIENT.PatientID = CONSULTATION.PatientID
                    INNER JOIN DOCTOR
                    ON DOCTOR.DoctorID = CONSULTATION.DoctorID
                    ORDER BY CONSULTATION.ConsultDateTime DESC";
            $result = $conn->query($sql); 
            
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . date("M j, Y", strtotime($row["ConsultDateTime"])) . "</td> 
                        <td>" . date("g:i A", strtotime($row["ConsultDateTime"])) . "</td> 
                        <td>" . $row["PatientFirstName"] . " " . $row["PatientMiddleInit"] . ". " . $row["PatientLastName"] . "</td>
                        <td>" . $row["DocFirstName"] . " " . $row["DocMiddleInit"] . ". " . $row["DocLastName"] . "</td>
                        </tr>";
            }
        ?>
    </table>
    <div id="footer">
        basta contact info
    </div>
</body>
</html>