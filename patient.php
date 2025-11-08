<?php
 include("database.php");
?>
<!DOCTYPE html>
<html lang="en">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Information</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body>
    <div class="header">
        <a id="hyperlink-logo" href="./index.php">
            <div id='logo'>
                <img id='logo-img' src='./img/logo.svg'>
                HSO
            </div>
        </a>
        <ul class="links">
            <li><a href="./index.php">Home</a></li>
            <li><a href="./consultation.php">Consultations</a></li>
            <li><a href="./patient.php">Patients</a></li>
            <li><a href="./staff.php">Staff</a></li>
            <li><a href="#footer">Contact</a></li>
        </ul>
    </div>

<div class="search-container">
    <form method="POST" value="<?php echo isset($_POST['patientsearch']) ? $_POST['patientsearch'] : '' ?>">
      <input type="text" placeholder="Search patient..." name="patientsearch">
      <button type="submit" name = "search"><i class="fa fa-search"></i></button>
    </form>
</div>

<?php
    if(ISSET($_POST['search'])){ 
    $searchname = $_POST['patientsearch'];
?>

<div>
    <h2>Search Results for <?php echo $_POST["patientsearch"]; ?></h2>
    <hr/>
    <?php
        $query = mysqli_query($conn, "SELECT * FROM `PATIENT` WHERE `PatientFirstName` LIKE '%$searchname%' OR `PatientLastName` LIKE '%$searchname%' ORDER BY `PatientID`") or die(mysqli_error($conn));
        while($fetch = mysqli_fetch_array($query)){
    ?>
    <div style="word-wrap:break-word;">
        <a href="get_patient.php?id=<?php echo $fetch['PatientID']?>"><h4><?php echo $fetch['PatientFirstName']?> <?php echo $fetch['PatientLastName']?></h4></a>
    </div>
    <hr />
    <?php
        }
    ?>
</div>
<?php
}
?>

    <div id="footer">
        BLABLABLABLABLABLABL
    </div>
</body>
</html>