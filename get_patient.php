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
    </div>


    <div id="footer">
        basta contact info
    </div>
    
</body>
</html>
