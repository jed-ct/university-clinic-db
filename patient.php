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
                <button type="submit" name = "search" style="display:none;"></button>
                </form>
        </div>  
</div>

<?php
    if (isset($_POST['search'])) {
        $searchname = htmlspecialchars($_POST['patientsearch']);
        $search_term = "%" . $searchname . "%";
        $sql = "SELECT * FROM `PATIENT` WHERE 
            `PatientFirstName` LIKE ? 
            OR `PatientLastName` LIKE ? 
            OR CONCAT(PatientFirstName, ' ', PatientLastName) LIKE ? 
            OR CONCAT(PatientFirstName, ' ', PatientMiddleInit, ' ', PatientLastName) LIKE ? 
            OR CONCAT(PatientFirstName, ' ', PatientMiddleInit) LIKE ? 
            OR CONCAT(PatientMiddleInit, ' ', PatientLastName) LIKE ? 
            OR CONCAT(PatientLastName,' ', PatientFirstName) LIKE ? 
            ORDER BY `PatientID`";

        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssssss", 
            $search_term, $search_term, $search_term, $search_term, 
            $search_term, $search_term, $search_term);
        mysqli_stmt_execute($stmt);
        $query = mysqli_stmt_get_result($stmt);
?>

<div class = "patient-search-results">
    <h2>Search Results for <?php echo $searchname; ?></h2>
    <hr/>
    <?php
        while ($fetch = mysqli_fetch_array($query)) {
    ?>
    <div style="word-wrap:break-word;">
        <a href="get_patient.php?id=<?php echo $fetch['PatientID']?>">
            <h4 class = "link-to-other" ><?php echo htmlspecialchars($fetch['PatientFirstName'])?> <?php echo htmlspecialchars($fetch['PatientLastName'])?></h4>
        </a>
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
        fuckshet fuck
    </div>
</body>
</html>