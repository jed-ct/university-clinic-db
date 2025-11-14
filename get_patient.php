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
    
    <div>
        <?php
            if(ISSET($_REQUEST['id'])){
                $query = mysqli_query($conn, "SELECT * FROM `Patient` WHERE `PatientID` = '$_REQUEST[id]'") or die(mysqli_error($conn));
                $fetch = mysqli_fetch_array($query);
        ?>

        <h3><?php echo $fetch['PatientFirstName']?> <?php echo $fetch['PatientLastName']?></h3>
        <p>This page contains information about the patient hehehehehhehe</p>

        <?php
            }
        ?>
    </div>
    
    <div id="footer">
        basta contact info
    </div>
    
</body>
</html>
