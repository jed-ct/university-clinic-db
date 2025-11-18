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
<!-- ADD MODAL -->
<div id="add-patient-modal" class="modal">
    <div class="modal-content">
        <div class="close-btn-div">
            <div>Add new patient</div>
            <button class="close-btn-patient"><img class='btn-img' src="./img/close.svg"></button>
        </div>

        <div class="modal-message">
            <form id='add-patient-form' method='POST'>

           <!--NAME-->
                <fieldset class='p-name-fieldset'>                   
                    <div class="forms-input">
                        <label for="add-p-firstname">First Name *</label>
                        <input type="text" name="PFirstName" id="add-p-firstname" pattern="^[A-Za-z.]+([ .][A-Za-z.]+)*$" maxlength="64" required>
                        <span class='error-message' id='add-fname-error-message'>Yipeee</span>
                    </div>     

                    <div class="forms-input">
                        <label for="add-p-middleinit">Middle Initial</label>
                        <input type="text" name="PMiddleInit" id="add-p-middleinit" pattern="^[A-Za-z.]+([ .][A-Za-z.]+)*$" maxlength="2">
                        <span class='error-message' id='add-mname-error-message'>Yipeee</span>
                    </div> 

                    <div class="forms-input">
                        <label for="add-p-lastname">Last Name *</label>
                        <input type="text" name="PLastName" id="add-p-lastname" pattern="^[A-Za-z]+$" maxlength="64" required>
                        <span class='error-message' id='add-lname-error-message'>Yipeee</span>
                    </div> 
                </fieldset>

            <!--SEX-->
                <fieldset class='sex-fieldset'>
                    <div class="forms-input">
                        <label for="add-sex">Sex *</label>
                        <input type="text" name="Sex" id="add-sex" pattern="^[A-Za-z]+$" maxlength="1" required>
                        <span class='error-message' id='add-sex-error-message'>Yipeee</span>
                    </div>     
                </fieldset>

            <!--BIRTHDAY-->
                <fieldset class='bday-fieldset'>
                    <div class="forms-input">
                        <label for="add-bday">Birthday *</label>
                        <input  name="Birthday" id="add-bday" type="date" min="1900-01-01" required>
                        <span class='error-message' id='add-bdayerror-message'>Yipeee</span>
                    </div>     
                </fieldset>
                
            <!--CONTACT-->
                <fieldset class='contactno-fieldset'>
                    <div class="forms-input">
                        <label for="add-contact">Contact Number *</label>
                        <input name="ContactNo" id="add-contact" type="number" maxlength="11" required>
                        <span class='error-message' id='add-contact-error-message'>Yipeee</span>
                    </div>     
                </fieldset>
            </form>
        </div>

        <div class='consultation-modal-actions'>
            <button class='action add' type='submit' form='add-patient-form'>Add</button>
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

<!-- SEARCH BAR AND FUNCTION -->
 <div class="consultations-table-container">
        <div><h2 class='consultation-history'>Patient Information</h2></div>
        <div class="consultations-actions">
            <button type="button" class="consultations action" id='add-patient-btn'><i class="fa-solid fa-plus"></i> <span>Add new patient</span></button>
            <button type="button" class='consultations action' id='filter-patient-btn'><i class="fa-solid fa-filter"></i> <span>Filter</span></button>
        </div>
        <div class="patient-search">
                <form method="POST" value="<?php echo isset($_POST['patientsearch']) ? $_POST['patientsearch'] : '' ?>">
                <input type="text" id ="patient-searchbox" placeholder="Search patient name..." name="patientsearch">
                <button type="submit" name = "search" style="display:none;"></button>
                </form>
        </div>  
</div>



<!-- SEARCH RESULTS -->
<?php
    if (isset($_POST['search'])) {
        $searchname = htmlspecialchars($_POST['patientsearch']);
        if (!preg_match("/^[A-Za-z.-]+(?:[ .-][A-Za-z.-]+)*$/", $searchname)) {
        echo "<p class ='error-search'> Your search contained invalid or null symbols. Please try again.</p>";
        } else {

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
    <h2 class="ellips-truncate"> Search Results for <?php echo $searchname; ?></h2>
    <hr/>
    <?php
        $count = 0;
        while ($fetch = mysqli_fetch_array($query)) {
             
    ?>
    <div style="word-wrap:break-word;">
        <a href="get_patient.php?id=<?php echo $fetch['PatientID']?>">
            <h4 class = "link-to-other" ><?php echo htmlspecialchars($fetch['PatientFirstName'])?> <?php echo htmlspecialchars($fetch['PatientMiddleInit'])?> <?php echo htmlspecialchars($fetch['PatientLastName'])?></h4>
        </a>
    </div>
    <hr />
    <?php
        $count++; }
        if ($count == 0) {
    ?>
        <br><p>No patients found</p>
        <br>
        <hr />
        
    <?php
        }
    ?>
</div>
<?php
    }}
?>

    <div id="footer">
        hello world
    </div>

<script src="./scriptpatient.js"></script>    
</body>
</html>