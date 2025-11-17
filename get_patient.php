<?php
 include("database.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Information</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <link rel="stylesheet" href="./style.css">
        <script src="https://kit.fontawesome.com/ea8c838e77.js" crossorigin="anonymous"></script>
</head>
<body>

    <!-- DELETE CONFIRMATION MODAL -->
    <div id="delete-patient-modal" class='modal'>
    <div class='modal-content'>
        <div class="close-btn-div">
            <div></div>
            <button class="close-btn"><img class='btn-img' src="./img/close.svg"></button>
        </div>

        <div class='modal-message'>
            Are you sure you want to delete this patient? This action cannot be undone.
        </div>

        <div class='consultation-modal-actions'>
            <button class='action confirm-delete'>Yes</button>
            <button class='close-btn action'>No</button>
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

<!--PATIENT DETAILS-->
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
                <button type="button" class="action edit-ptnt" ><i class="fa-solid fa-pen-to-square"></i> <span>Edit patient information</span></button>
                <button type="button" class="action delete-ptnt"  data-toggle="modal" data-target="#staticBackdrop"><i class="fa-solid fa-trash"></i> <span>Delete patient</span></button>
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

<!--CONSULTATION HISTORY-->
        <div id="consult-information">
            <h2 style="text-align: center;">Consultation History</h2>
                <div id="consult-information-table"> 
                    <?php $sql = " SELECT c.ConsultationID, c.ConsultDateTime,c.Diagnosis,c.Prescription,c.Remarks,
                        CONCAT(d.DocFirstName, ' ', IFNULL(CONCAT(d.DocMiddleInit, '. '), ''),d.DocLastName) AS DoctorFullName
                        FROM Consultation c
                        INNER JOIN Doctor d ON d.DoctorID = c.DoctorID
                        WHERE c.PatientID = '$patientID'
                        ORDER BY c.ConsultDateTime DESC";
                        $result = $conn->query($sql);
                        ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th class="col-date">Date</th>
                            <th class="col-time">Time</th>
                            <th class="col-diag">Diagnosis</th>
                            <th class="col-prescr">Prescription</th>
                            <th class="col-remrks">Remarks</th>
                            <th class="col-doc">Doctor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()) { ?>
                            <tr>
                                <td class="col-date">
                                    <?= date("M j, Y", strtotime($row["ConsultDateTime"])) ?>
                                </td>

                                <td class="col-time">
                                    <?= date("g:i A", strtotime($row["ConsultDateTime"])) ?>
                                </td>

                                <td class="col-diag"><?= $row["Diagnosis"] ?></td>
                                <td class="col-prescr"><?= $row["Prescription"] ?></td>

                                <td class="col-remrks">
                                    <?= $row["Remarks"] ?>
                                </td>

                                <td class="col-doc"><?= $row["DoctorFullName"] ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>


    <div id="footer">
        basta contact info
    </div>

<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<script src="./script.js"></script>
</body>
</html>
