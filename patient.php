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

 <div class="consultations-table-container">
        <div><h2 class='consultation-history'>Patient Information</h2></div>
        <div class="consultations-actions">
            <button class="consultations action" id='add-consultation-btn'><i class="fa-solid fa-plus"></i> <span>Add new patient</span></button>
            <button class='consultations action' id='filter-consultation-btn'><i class="fa-solid fa-filter"></i> <span>Filter</span></button>
        </div>
        <div class="patient-search">
                <form method="POST" value="<?php echo isset($_POST['patientsearch']) ? $_POST['patientsearch'] : '' ?>">
                <input type="text" id ="patient-searchbox" placeholder="Search patient name..." name="patientsearch">
                <button type="submit" name = "search"></button>
                </form>
        </div>
        
</div>

<?php
    if(ISSET($_POST['search'])){ 
    $searchname = $_POST['patientsearch'];
?>

<div class = "patient-search-results">
    <h2>Search Results for <?php echo $_POST["patientsearch"]; ?></h2>
    <hr/>
    <?php
        $query = mysqli_query($conn, "SELECT * FROM `PATIENT` WHERE 
        `PatientFirstName` LIKE '%$searchname%' 
        OR `PatientLastName` LIKE '%$searchname%' 
        OR CONCAT(PatientFirstName, ' ', PatientLastName) LIKE '%$searchname%' 
        OR CONCAT(PatientFirstName, ' ', PatientMiddleInit, ' ', PatientLastName) LIKE '%$searchname%' 
        OR CONCAT(PatientFirstName, ' ', PatientMiddleInit) LIKE '%$searchname%' 
        OR CONCAT(PatientMiddleInit, ' ', PatientLastName) LIKE '%$searchname%' 
        OR CONCAT(PatientLastName,' ', PatientFirstName) LIKE '%$searchname%' 
        ORDER BY `PatientID`") or die(mysqli_error($conn));
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