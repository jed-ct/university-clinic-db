<?php
include("database.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultation History</title>
    <link rel="stylesheet" href="./style.css">
    <script src="https://kit.fontawesome.com/ea8c838e77.js" crossorigin="anonymous"></script>
</head>
<body>

<?php
if (empty($_GET)) {
    header("Location: ?page=1");
    exit;
}
?>

<div id="add-consultation-modal" class="modal">
    <div class="modal-content">
        <div class="close-btn-div">
            <div>Add new consultation</div>
            <button class="close-btn" id="close-modal"><img class='btn-img' src="./img/close.svg"></button>
        </div>
        <div class="modal-message">
            <form id='add-consultation-form' method='POST'>

                <fieldset id='date-time-fieldset'>
                    <legend>Date and Time</legend>
                    <div id='is-current-date-time-container'>
                        <input type='checkbox' id="is-current-date-time" checked>
                        <label for="is-current-date-time">Current time and date</label>
                    </div>
                    <div id='set-date-time-container'>
                        <label for="setConsultationDate">Date</label>
                        <input type="date" name="ConsultationDate" id="set-consultation-date" disabled>

                        <label for="setConsultationTime">Time</label>
                        <input type="time" name="ConsultationTime" id="set-consultation-time" disabled> 
                    </div>  
                </fieldset>

                <fieldset id='patient-fieldset'>
                    <legend>Patient</legend>
                    <div class="forms-input">
                        <label for="patient_first_name">*First Name</label>
                        <input type="text" name="patientFirstName" id="patient_first_name" pattern="^[A-Za-z]+( [A-Za-z]+)*$" maxlength="64">
                    </div>
                    <div class="forms-input">
                        <label for="patient_mi">MI</label>
                        <input type="text" name="patientMiddleInit" id="patient_mi" pattern="^[A-Za-z]+( [A-Za-z]+)*$" maxlength="2">
                    </div>
                    <div class="forms-input">
                        <label for="patient_last_name">*Last Name</label>
                        <input type="text" name="patientLastName" id="patient_last_name" pattern="^[A-Za-z]+( [A-Za-z]+)*$" maxlength="64">
                    </div>        
                </fieldset>

                <fieldset id='consultation-fieldset'>
                    <legend>Consultation</legend>
                        <div class="forms-input">
                            <label for="diagnosis">*Diagnosis</label>
                            <input type="text" name="Diagnosis" id="diagnosis" maxlength="64">
                        </div>
                        <div class="forms-input">
                            <label for="prescription">Prescription</label>
                            <input type="text" name="Diagnosis" id="diagnosis" maxlength="64">
                        </div>
                        <div class="forms-input">
                            <label for="remarks">Remarks</label>
                            <input type='text' name='Remarks' id='remarks' maxlength='256'>
                        </div>
                </fieldset>
            
                <fieldset id='doctor-fieldset'>
                    <legend>Doctor</legend>
                    <div class="forms-input">
                        <label for="patient_first_name">*First Name</label>
                        <input type="text" name="patientFirstName" id="patient_first_name" pattern="^[A-Za-z]+( [A-Za-z]+)*$" maxlength="64">
                    </div>
                    <div class="forms-input">
                        <label for="patient_mi">MI</label>
                        <input type="text" name="patientMiddleInit" id="patient_mi" pattern="^[A-Za-z]+( [A-Za-z]+)*$" maxlength="2">
                    </div>
                    <div class="forms-input">
                        <label for="patient_last_name">*Last Name</label>
                        <input type="text" name="patientLastName" id="patient_last_name" pattern="^[A-Za-z]+( [A-Za-z]+)*$" maxlength="64">
                    </div>        
                </fieldset>

            </form>
        </div>
        <div class='consultation-modal-actions'>
            <button class='action add' data-id=''>Add</button>
        </div>

    </div>
</div>

<div id="filter-consultation-modal" class="modal">
    <div class="modal-content">
        <div class="close-btn-div">
            <div>Filter Consultation</div>
            <button class="close-btn" id="close-modal"><img class='btn-img' src="./img/close.svg"></button>
        </div>
        <div class="modal-message">
            <form>
                <fieldset style='display: flex; gap: 10px;'>
                    <legend>By Date</legend>
                    <div class="forms-input">
                        <label for="patient_mi">Start Date</label>
                        <input type="date" name="patientMiddleInit">
                    </div>
                    <div class="forms-input">
                        <label for="patient_mi">End Date</label>
                        <input type="date" name="patientMiddleInit">
                    </div>
                </fieldset>
            </form>      
        </div>
        <div class='consultation-modal-actions'>
            <button class='action' data-id=''>Filter</button>
        </div>

    </div>
</div>

<div id="consultation-modal" class="modal">
    <div class="modal-content">
        <div class="close-btn-div">
            <div>Consultation Details</div>
            <button class="close-btn" id="close-modal"><img class='btn-img' src="./img/close.svg"></button>
        </div>
        <div class="modal-message">
            <h4>Date: <span id="consultation-date"></span></h4>
            <h4>Time: <span id="consultation-time"></span></h4>
            <h4>Patient First Name: <span id="patient-first-name">weeeeeeeeeeeeeeeeee</span></h4>
            <h4>Patient MI: <span id="patient-middle-initial"></span></h4>
            <h4>Patient Last Name: <span id="patient-last-name"></span></h4>
            <h4>Patient Age: <span id="patient-age"></span></h4>
            <h4>Diagnosis: <span id="view-diagnosis"></span></h4>
            <h4>Remarks: <span id="view-remarks"></span></h4>
            <h4>Prescription: <span id="prescription"></span></h4>
            <h4>Doctor: <span id="doctor-name"></span></h4>
        </div>
        <div class='consultation-modal-actions'>
            <button class='action edit' data-id=''>Edit</button>
            <button class='action delete' data-id=''>Delete</button>
        </div>

    </div>
</div>

<div id="edit-consultation-modal" class="modal">
    <div class="modal-content">
        <div class="close-btn-div">
            <button class="close-btn" id="close-modal"><img class='btn-img' src="./img/close.svg"></button>
        </div>
        <div class="modal-message">

        </div>
        <div class='consultation-modal-actions'>
            <button class='action add' data-id=''>Add</button>
        </div>

    </div>
</div>

<div id="delete-confirmation-modal" class='modal'>
    <div class='modal-content'>
        <div class="close-btn-div">
            <div></div>
            <button class="close-btn" id="close-modal"><img class='btn-img' src="./img/close.svg"></button>
        </div>
        <div class='modal-message'>
            Are you sure you want to delete this consultation? This cannot be undone.
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



    <div class="consultations-table-container">
        <div><h2 class='consultation-history'>Consultation History</h2></div>
            <div class="consultation-search">
        <input type="text" id="consultation-searchbox" placeholder="Search by patient or doctor"> 
    </div>
        <div class="consultations-actions">
            <button class="consultations action" id='add-consultation-btn'><i class="fa-solid fa-plus"></i> <span>Add new consultation</span></button>
            <button class='consultations action' id='filter-consultation-btn'><i class="fa-solid fa-filter"></i> <span>Filter</span></button>
        </div>

        <table id='consultations-table' class="consultations-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Patient</th>
                    <th>Doctor</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $sql = "SELECT CONSULTATION.ConsultationID, CONSULTATION.ConsultDateTime,
                    CONCAT(
                        PATIENT.PatientFirstName, ' ',
                        IFNULL(CONCAT(PATIENT.PatientMiddleInit, '. '), ''),
                        PATIENT.PatientLastName
                    ) AS PatientFullName,
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
                            <td data-label='Patient'>" . $row["PatientFullName"] . "</td>
                            <td data-label='Doctor'>" . $row["DoctorFullName"] . "</td>
                            <td style='width:1%; white-space:nowrap;'>
                                <button href='#' class='action view' data-id='" . $row["ConsultationID"] . "'>View</button>
                            </td>
                        </tr>";
                    }
                ?>
            </tbody>
        </table>
        <div class="pagination">
            <a href="#" class="prev">&laquo;</a>
            <a href="consultation.php?page=1" class="active">1</a>
            <a href="#">2</a>
            <a href="#">3</a>
            <a href="#" class="next">&raquo;</a>
        </div>
    </div>

    <div id="footer">
        basta contact info
    </div>

    <script src="./script.js"></script>
</body>
</html>
