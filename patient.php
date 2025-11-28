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
            <button class="close-btn-patient" id="close-add-modal"><img class='btn-img' src="./img/close.svg"></button>
        </div>
        <div class="modal-message">
            <form id='add-patient-form' method='POST'>
                <!-- NAME -->
                <fieldset class='p-name-fieldset'>                   
                    <div class="forms-input">
                        <label for="add-p-firstname">First Name *</label>
                        <input type="text" name="PFirstName" id="add-p-firstname" pattern="^[A-Za-z.]+([ .][A-Za-z.]+)*$" title="Name must contain only letters and periods." maxlength="64" required>
                        <span class='error-message' id='add-fname-error-message'>Yipeee</span>
                    </div>     
                    <div class="forms-input">
                        <label for="add-p-middleinit">Middle Initial</label>
                        <input type="text" name="PMiddleInit" id="add-p-middleinit" pattern="^[A-Za-z]+$" maxlength="2" title="Initials must only be letters.">
                        <span class='error-message' id='add-mname-error-message'>Yipeee</span>
                    </div> 
                    <div class="forms-input">
                        <label for="add-p-lastname">Last Name *</label>
                        <input type="text" name="PLastName" id="add-p-lastname" pattern="^[A-Za-z.]+([ .][A-Za-z.]+)*$" maxlength="64" title="Name must contain only letters and periods." required>
                        <span class='error-message' id='add-lname-error-message'>Yipeee</span>
                    </div> 
                </fieldset>

                <!-- SEX -->
                <fieldset class='sex-fieldset'>
                    <div class="forms-input">
                        <label for="add-sex">Sex *</label>
                        <select name="Sex" id="add-sex" required>
                            <option value="" selected disabled> </option>
                            <option value="F">Female</option>
                            <option value="M">Male</option>
                            <option value="O">Other</option>
                        </select>
                    </div>     
                </fieldset>

                <!-- BIRTHDAY -->
                <fieldset class='bday-fieldset'>
                    <div class="forms-input">
                        <label for="add-bday">Birthday *</label>
                        <input name="Birthday" id="add-bday" type="date" min="1900-01-01" max="<?php echo date("Y-m-d"); ?>" required>
                        <span class='error-message' id='add-bdayerror-message'>Yipeee</span>
                    </div>     
                </fieldset>
                
                <!-- CONTACT -->
                <fieldset class='contactno-fieldset'>
                    <div class="forms-input">
                        <label for="add-contact">Contact Number *</label>
                        <div style="flex">
                        <input type="text" value="+639" readonly id="contactprefix">
                        <input type="tel" id="partcontact" name="PartContactNo" placeholder="123456789" pattern="[0-9]{9}" maxlength="9" title="Input must contain numbers only." required>
                        </div>
                        <input type="hidden" name="ContactNo" id="add-contact">
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

<!-- HEADER -->
<div class="header">
    <a id="hyperlink-logo" href="./index.php">
        <div class='header-img' id='logo'>
            <img id='logo-img' src='./img/logo.svg'> TBAClinic 
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

<!-- FILTER MODAL -->
<div id="filter-patient-modal" class="modal">
    <div class="modal-content">
        <div class="close-btn-div">
            <div>Filter Patient Results</div>
            <button class="close-btn-patient"><img class='btn-img' src="./img/close.svg"></button>
        </div>
        <div class="modal-message">
            <form id="filter-patient-form" method='POST'>
                <fieldset>
                    <div class="forms-input">
                        <label for="filter-first-name">First Name</label>
                        <input type="text" name="FirstName" id="filter-first-name" pattern="^[A-Za-z]+( [A-Za-z]+)*$" maxlength="64">
                    </div>
                    <div class="forms-input">
                        <label for="filter-middle-init">Middle Initial</label>
                        <input type="text" name="MiddleInit" id="filter-middle-init" pattern="^[A-Za-z]+( [A-Za-z]+)*$" maxlength="64">
                    </div>
                    <div class="forms-input">
                        <label for="filter-last-name">Last Name</label>
                        <input type="text" name="LastName" id="filter-last-name" pattern="^[A-Za-z]+( [A-Za-z]+)*$" maxlength="64">
                    </div>                        
                </fieldset>
                <fieldset>
                    <div class="forms-input">
                        <label for="filter-sex">Sex</label>
                        <input type="text" name="Sex" id="filter-sex" maxlength="64">
                    </div>
                    <div class="forms-input">
                        <label for="filter-bday">Birthday</label>
                        <input type="date" name="Birthday" id="filter-bday" maxlength="64">
                    </div>
                    <div class="forms-input">
                        <label for="filter-contact">Contact Number</label>
                        <input type="number" name="ContactNo" id="filter-contact" maxlength="64">
                    </div>
                </fieldset>
            </form>      
        </div>
        <div class='consultation-modal-actions'>
            <button type='submit' class='action' form="filter-patient-form">Filter</button>
        </div>
    </div>
</div>

<!-- SEARCH BAR AND ACTIONS -->
<div class="patient-table-container">
    <div><h2 class='consultation-history'>Patient Information</h2></div>
    <div class="patient-actions">
        <button type="button" class="patient action" id='add-patient-btn'><i class="fa-solid fa-plus"></i> <span>Add new patient</span></button>
        <button type="button" class='patient action' id='filter-patient-btn'><i class="fa-solid fa-filter"></i> <span>Filter</span></button>
    </div>
    <div class="patient-search">
        <input type="text" id="patient-searchbox" placeholder="Search patient name...">
    </div>
</div>

<!-- LIVE SEARCH RESULTS -->
<div id="patient-search-results"></div>

<div id="footer">
    hello world
</div>

<!-- SCRIPT -->
<script src="./scriptpatient.js"></script>

</body>
</html>
