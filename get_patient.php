<?php
 include("database.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UPB HSO Database</title>
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
    
    <div>
        <?php
            if(ISSET($_REQUEST['id'])){
                $query = mysqli_query($conn, "SELECT * FROM `Patient` WHERE `PatientID` = '$_REQUEST[id]'") or die(mysqli_error());
                $fetch = mysqli_fetch_array($query);
        ?>

        <h3><?php echo $fetch['PatientFirstName']?> <?php echo $fetch['PatientLastName']?></h3>
        <p><?php echo nl2br($fetch['PatientBirthday'])?></p>

        <?php
            }
        ?>
    </div>
    
    <div id="footer">
        basta contact info
    </div>
    
</body>
</html>