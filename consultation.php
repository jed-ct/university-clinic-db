<?php
 include("database.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hello</title>
</head>
<body>
    <table border='1'>
        <tr>
            <th>Patient</th>
            <th>Diagnosis</th>
            <th>Prescription</th>
            <th>Consultation Date</th>
            <th>Consultation Time</th>
            <th>Doctor</th>
        </tr>
        <?php
            $sql = "SELECT * FROM CONSULTATION";
            $result = $conn->query($sql); 
            
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row["PatientID"] . "</td>
                        <td>" . $row["Diagnosis"] . "</td>
                        <td>" . $row["Prescription"] . "</td>
                        <td>" . $row["ConsultationDate"] . "</td>
                        <td>" . $row["ConsultationTime"] . "</td>
                        <td>" . $row["DoctorID"] . "</td>
                        </tr>";
            }
        ?>
    </table>
</body>
</html>