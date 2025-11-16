<?php
 include("database.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Information</title>
    <link rel="stylesheet" href="./style.css">
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
     <script src="https://kit.fontawesome.com/ea8c838e77.js" crossorigin="anonymous"></script>
</head>
<body>
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

 

    <?php
                if(ISSET($_REQUEST['id'])){
                    $query = mysqli_query($conn, "SELECT * FROM `Patient` WHERE `PatientID` = '$_REQUEST[id]'") or die(mysqli_error($conn));
                    $fetch = mysqli_fetch_array($query);
                    $patientID = $fetch['PatientID'];
                    $consultQuery = mysqli_query(
                        $conn,
                        "SELECT * FROM Consultation WHERE PatientID = '$patientID' ORDER BY ConsultDateTime DESC"
                    ) or die(mysqli_error($conn));
    ?>
    <div class="patient-information-container">
        <div id="patient-information">
            <div class="patient-actions">
                <button  class="patient action" id='edit-patient-btn'><i class="fa-solid fa-pen-to-square"></i> <span>Edit patient information</span></button>
                <button class="patient action" id='delete-patient-btn'><i class="fa-solid fa-trash"></i> <span>Delete patient</span></button>
            </div>

            <h2 style="text-align: center;">Patient Details</h2>
                <div id="patient-information-table"><table class = "table table-striped">
                    <tr><th>Patient ID</th> <td><?php echo $fetch['PatientID']?></td> </tr>
                    <tr><th>Name</th> <td><?php echo $fetch['PatientFirstName']?> <?php echo $fetch['PatientMiddleInit']?>. <?php echo $fetch['PatientLastName']?></td> </tr>
                    <tr><th>Sex</th> <td><?php echo $fetch['PatientSex']?></td> </tr>
                    <tr><th>Birthday</th> <td><?php echo $fetch['PatientBirthday']?></td> </tr>
                    <tr><th>Contact Number</th> <td><?php echo $fetch['PatientContactNo']?></td> </tr>
                </table>
            </div>
            <?php }?>
        </div>

        <div id="consult-information">
            <h2 style="text-align: center;">Consultation History</h2>
                <div id="consult-information-table"> <table class = "table table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Diagnosis</th>
                            <th>Prescription</th>
                            <th>Remarks</th>
                            <th>Doctor</th>
                        </tr>
                    </thead>

         <?php
                $sql = "SELECT CONSULTATION.ConsultationID, CONSULTATION.ConsultDateTime, CONSULTATION.Diagnosis, 
                CONSULTATION.Prescription, CONSULTATION.Remarks,
                CONCAT(
                    DOCTOR.DocFirstName, ' ',
                    IFNULL(CONCAT(DOCTOR.DocMiddleInit, '. '), ''),
                    DOCTOR.DocLastName
                ) AS DoctorFullName
                FROM PATIENT
                INNER JOIN CONSULTATION ON PATIENT.PatientID = CONSULTATION.PatientID
                INNER JOIN DOCTOR ON DOCTOR.DoctorID = CONSULTATION.DoctorID
                ORDER BY CONSULTATION.ConsultDateTime DESC;";
                
                $result = $conn->query($sql); 
                
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td data-label='Date'>" . date("M j, Y", strtotime($row["ConsultDateTime"])) . "</td>
                        <td data-label='Time'>" . date("g:i A", strtotime($row["ConsultDateTime"])) . "</td>
                        <td data-label='Diagnosis'>" . $row["Diagnosis"] . "</td>
                        <td data-label='Prescription'>" . $row["Prescription"] . "</td>
                        <td data-label='Remarks'>" . $row["Remarks"] . "</td>
                        <td data-label='Doctor'>" . $row["DoctorFullName"] . "</td>
                    </tr>";
                }
            ?>
    </table>
            </div>
        </div>
    </div>


    <div id="footer">
        basta contact info
    </div>
    
</body>
</html>
