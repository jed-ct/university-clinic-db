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
    $filterStartDate = isset($_GET['StartDate']) ? trim($_GET['StartDate']) : '';
    $filterEndDate = isset($_GET['EndDate']) ? trim($_GET['EndDate']) : '';
    $filterPatientName = isset($_GET['PatientName']) ? trim($_GET['PatientName']) : '';
    $filterDoctorName = isset($_GET['DoctorName']) ? trim($_GET['DoctorName']) : '';
    $filterDiagnosis = isset($_GET['Diagnosis']) ? trim($_GET['Diagnosis']) : '';
    $filterPrescription = isset($_GET['Prescription']) ? trim($_GET['Prescription']) : '';
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $isTableFiltered = false;
    function getPaginationURL($previousOrNext) {
        global $page;
        $next_page = $page + 1;
        $previous_page = $page - 1;
        $current_params = $_GET;
        if ($previousOrNext == "previous") {
            $current_params['page'] = $previous_page;
        }
        else {
            $current_params['page'] = $next_page;
        }
        $query_string = http_build_query($current_params);
        return "/university-clinic-db/consultation.php?" . $query_string;

    }

?>

<!-- ADD CONSULTATION MODAL -->
<div id="add-consultation-modal" class="modal">
    <div class="modal-content">
        <div class="close-btn-div">
            <div>Add new consultation</div>
            <button class="close-btn"><img class='btn-img' src="./img/close.svg"></button>
        </div>

        <div class="modal-message">
            <form id='add-consultation-form' action='./add_consultation.php' method='POST' autocomplete="off">

                <fieldset class='date-time-fieldset'>
                    <legend>Date and Time</legend>

                    <div id='is-current-date-time-container'>
                        <input type='checkbox' id="is-current-date-time" checked>
                        <label for="is-current-date-time">Current time and date</label>
                    </div>

                    <div id='set-date-time-container'>
                        <label>Date</label>
                        <input type="date" name="ConsultationDate" id="set-consultation-date" min='2024-01-01' disabled>

                        <label>Time</label>
                        <input type="time" name="ConsultationTime" id="set-consultation-time" disabled> 
                    </div>
                    <span class='error-message' id='add-datetime-error-message'>Yipeee</span>  
                </fieldset>

                <fieldset class='patient-fieldset'>
                    <legend>Patient</legend>
                    <div class="forms-input">
                        <label for="patient-name">Patient Name *</label>
                        <input type="text" name="PatientName" id="add-patient-name" pattern="^[A-Za-z.]+([ .][A-Za-z.]+)*$" maxlength="64">
                        <span class='error-message' id='add-patient-error-message'>Yipeee</span>
                    </div>
                </fieldset>

                <fieldset class='consultation-fieldset'>
                    <legend>Consultation</legend>

                    <div class="forms-input">
                        <label for="add-diagnosis">Diagnosis *</label>
                        <input type="text" name="Diagnosis" id="add-diagnosis" maxlength="64">
                        <span class='error-message' id='add-diagnosis-error-message'>Yipeee</span>
                    </div>

                    <div class="forms-input">
                        <label for="add-prescription">Prescription *</label>
                        <input type="text" name="Prescription" id="add-prescription" maxlength="64">
                        <span class='error-message' id='add-prescription-error-message'>Yipeee</span>
                    </div>

                    <div class="forms-input">
                        <label for="add-remarks">Remarks</label>
                        <input type='text' name='Remarks' id='add-remarks' maxlength='256'>
                    </div>
                </fieldset>
            
                <fieldset class='doctor-fieldset'>
                    <legend>Doctor</legend>
                    <div class="forms-input">
                        <label for="add-doctor-name">Doctor Name *</label>
                        <input type="text" name="DoctorName" id="add-doctor-name" pattern="^[A-Za-z.]+([ .][A-Za-z.]+)*$" maxlength="64">
                        <span class='error-message' id='add-doctor-error-message'>Yipeee</span>
                    </div>     
                </fieldset>

            </form>
        </div>

        <div class='consultation-modal-actions'>
            <button class='action add' type='submit' form='add-consultation-form'>Add</button>
        </div>
    </div>
</div>

<!-- FILTER CONSULTATION MODAL -->
<div id="filter-consultation-modal" class="modal">
    <div class="modal-content">

        <div class="close-btn-div">
            <div>Filter Consultation</div>
            <button class="close-btn"><img class='btn-img' src="./img/close.svg"></button>
        </div>

        <div class="modal-message">
            <form id='filter-consultation-form' method='GET' action='consultation.php' autocomplete='off'>

                <fieldset>
                    <legend>By Date</legend>
                    <div style='display: flex; gap: 10px;'>
                        <div class="forms-input">
                            <label for="filter-start-date">Start Date</label>
                            <input type="date" id="filter-start-date" min='2024-01-01' name="StartDate">
                        </div>
                        <div class="forms-input">
                            <label for="filter-end-date">End Date</label>
                            <input type="date" id="filter-end-date" min='2024-01-01' name="EndDate">
                        </div>
                    </div>
                    <span class='error-message' id='filter-date-error-message'></span>
                </fieldset>

                <fieldset>
                    <legend>By Person</legend>

                    <div class="forms-input">
                        <label for="filter-patient">Patient</label>
                        <input type="text" name="PatientName" id="filter-patient" pattern="^[A-Za-z]+( [A-Za-z]+)*$" maxlength="64">
                        <span class='error-message' id='filter-patient-error-message'></span>
                    </div>

                    <div class="forms-input">
                        <label for="filter-doctor">Doctor</label>
                        <input type="text" name="DoctorName" id="filter-doctor" pattern="^[A-Za-z]+( [A-Za-z]+)*$" maxlength="64">
                        <span class='error-message' id='filter-doctor-error-message'></span>
                    </div>                        
                </fieldset>

                <fieldset>
                    <legend>By Treatment</legend>

                    <div class="forms-input">
                        <label for="filter-diagnosis">Diagnosis</label>
                        <input type="text" name="Diagnosis" id="filter-diagnosis" maxlength="64">
                        <span class='error-message' id='filter-diagnosis-error-message'></span>
                    </div>

                    <div class="forms-input">
                        <label for="filter-prescription">Prescription</label>
                        <input type="text" name="Prescription" id="filter-prescription" maxlength="64">
                        <span class='error-message' id='filter-prescription-error-message'></span>
                    </div>                                 
                </fieldset>

            </form>      
        </div>

        <div class='consultation-modal-actions'>
            <button class='action filter' type='submit' form='filter-consultation-form'>Filter</button>
        </div>

    </div>
</div>

<!-- VIEW CONSULTATION MODAL -->
<div id="consultation-modal" class="modal">
    <div class="modal-content">

        <div class="close-btn-div">
            <div>Consultation Details</div>
            <button class="close-btn"><img class='btn-img' src="./img/close.svg"></button>
        </div>

        <div class="modal-message">
            <h4>Date: <span id="view-consultation-date"></span></h4>
            <h4>Time: <span id="view-consultation-time"></span></h4>
            <h4>Patient First Name: <span id="view-patient-first-name"></span></h4>
            <h4>Patient MI: <span id="view-patient-middle-initial"></span></h4>
            <h4>Patient Last Name: <span id="view-patient-last-name"></span></h4>
            <h4>Patient Age: <span id="view-patient-age"></span></h4>
            <h4>Diagnosis: <span id="view-diagnosis"></span></h4>
            <h4>Remarks: <span id="view-remarks"></span></h4>
            <h4>Prescription: <span id="view-prescription"></span></h4>
            <h4>Doctor: <span id="view-doctor-name"></span></h4>
        </div>

        <div class='consultation-modal-actions'>
            <button class='action edit' data-id=''>Edit</button>
            <button class='action delete' data-id=''>Delete</button>
        </div>

    </div>
</div>

<!-- EDIT MODAL -->
<div id="edit-consultation-modal" class="modal">
    <div class="modal-content">

        <div class="close-btn-div">
            <div>Edit Consultation</div>
            <button class="close-btn"><img class='btn-img' src="./img/close.svg"></button>
        </div>

        <div class="modal-message">
           <form id='edit-consultation-form' method='POST'>
                <fieldset class='date-time-fieldset'>
                    <legend>Date and Time</legend>
                    <div id='set-date-time-container'>
                        <label>Date</label>
                        <input type="date" name="ConsultationDate" id="edit-consultation-date" min='2024-01-01' value='2025-01-02'>

                        <label>Time</label>
                        <input type="time" name="ConsultationTime" id="edit-consultation-time"> 
                    </div>  
                </fieldset>

                <fieldset class='patient-fieldset'>
                    <legend>Patient</legend>
                    <div class="forms-input">
                        <label for="edit-patient-name">Patient Name *</label>
                        <input type="text" name="PatientName" id="edit-patient-name" pattern="^[A-Za-z.]+([ .][A-Za-z.]+)*$" maxlength="64">
                        <span class='error-message' id='add-patient-error-message'>Yipeee</span>
                    </div>
                </fieldset>

                <fieldset class='consultation-fieldset'>
                    <legend>Consultation</legend>

                    <div class="forms-input">
                        <label for="edit-diagnosis">Diagnosis *</label>
                        <input type="text" name="Diagnosis" id="edit-diagnosis" maxlength="64">
                        <span class='error-message' id='add-diagnosis-error-message'>Yipeee</span>
                    </div>

                    <div class="forms-input">
                        <label for="edit-prescription">Prescription *</label>
                        <input type="text" name="Prescription" id="edit-prescription" maxlength="64">
                        <span class='error-message' id='add-prescription-error-message'>Yipeee</span>
                    </div>

                    <div class="forms-input">
                        <label for="edit-remarks">Remarks</label>
                        <input type='text' name='Remarks' id='edit-remarks' maxlength='256'>
                    </div>
                </fieldset>
            
                <fieldset class='doctor-fieldset'>
                    <legend>Doctor</legend>
                    <div class="forms-input">
                        <label for="edit-doctor-name">Doctor Name *</label>
                        <input type="text" name="DoctorName" id="edit-doctor-name" pattern="^[A-Za-z.]+([ .][A-Za-z.]+)*$" maxlength="64">
                        <span class='error-message' id='add-doctor-error-message'>Yipeee</span>
                    </div>     
                </fieldset>

            </form>
        </div>

        <div class='consultation-modal-actions'>
            <button class='action add' data-id=''>Edit</button>
        </div>

    </div>
</div>

<!-- DELETE CONFIRMATION MODAL -->
<div id="delete-confirmation-modal" class='modal'>
    <div class='modal-content'>

        <div class="close-btn-div">
            <div></div>
            <button class="close-btn"><img class='btn-img' src="./img/close.svg"></button>
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

<!-- HEADER -->
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

<!-- CONSULTATION TABLE -->
<div class="consultations-table-container">

    <div><h2 class='consultation-history'>Consultation History</h2></div>

    <div class="consultations-actions">
        <button class='consultations action' id='filter-consultation-btn'><i class="fa-solid fa-filter"></i> <span>Filter</span></button>
        <button class="consultations action" id='add-consultation-btn'><i class="fa-solid fa-plus"></i> <span>Add new consultation</span></button>
    </div>

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
    ) AS DoctorFullName,
    CONSULTATION.Diagnosis, CONSULTATION.Prescription
    FROM PATIENT
    INNER JOIN CONSULTATION ON PATIENT.PatientID = CONSULTATION.PatientID
    INNER JOIN DOCTOR ON DOCTOR.DoctorID = CONSULTATION.DoctorID
    WHERE 1=1";

    $result = $conn->query($sql); 
    $totalUnfilteredTableRow = $result->num_rows;

    if ($filterStartDate && $filterEndDate) {
        $sql .= " AND DATE(ConsultDateTime) BETWEEN '$filterStartDate' AND '$filterEndDate'";
        $isTableFiltered = true;
    }
    else if ($filterStartDate) {
        $sql .= " AND DATE(ConsultDateTime) >= '$filterStartDate'";
        $isTableFiltered = true;
    }
    else if ($filterEndDate) {
        $sql .= " AND DATE(ConsultDateTime) <= '$filterEndDate'";
        $isTableFiltered = true;
    }

    if ($filterPatientName) {
        $sql .= " AND CONCAT(
        PATIENT.PatientFirstName, ' ',
        IFNULL(CONCAT(PATIENT.PatientMiddleInit, '. '), ''),
        PATIENT.PatientLastName
    ) LIKE '%$filterPatientName%'";
    $isTableFiltered = true;
    }
    
    if ($filterDoctorName) {
        $sql .= " AND CONCAT(
        DOCTOR.DocFirstName, ' ',
        IFNULL(CONCAT(DOCTOR.DocMiddleInit, '. '), ''),
        DOCTOR.DocLastName
    ) LIKE '%$filterDoctorName%'";
    $isTableFiltered = true;
    }
    
    if ($filterDiagnosis) {
        $sql .= " AND CONSULTATION.Diagnosis LIKE '%$filterDiagnosis%'";
        $isTableFiltered = true;
    }

    if ($filterPrescription) {
        $sql .= " AND CONSULTATION.Prescription LIKE '%$filterPrescription%'";
        $isTableFiltered = true;
    }
    $result = $conn->query($sql);

    $totalCurrentTableRow = $result->num_rows;
    if (!$isTableFiltered) {
        $totalCurrentTableRow = $totalUnfilteredTableRow;
    }
    
    $sql .= " ORDER BY CONSULTATION.ConsultDateTime DESC LIMIT 10";
    if ($page) {
        $offset = ($page - 1) * 10;
        $sql .= " OFFSET $offset";
    }

    $result = $conn->query($sql); 

    if ($result->num_rows === 0) {
        echo "<div style='font-size: 1.5rem'>Query not found</div>";
    }
    else {
        echo "
    <table id='consultations-table' class='consultations-table'>
        <thead>
            <tr>
                <th>Date</th>
                <th>Time</th>
                <th>Patient</th>
                <th>Doctor</th>
                <th></th>
            </tr>
        </thead>

        <tbody>";
    
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td data-label='Date'>" . date("M j, Y", strtotime($row["ConsultDateTime"])) . "</td>
                <td data-label='Time'>" . date("g:i A", strtotime($row["ConsultDateTime"])) . "</td>
                <td data-label='Patient'>" . $row["PatientFullName"] . "</td>
                <td data-label='Doctor'>" . $row["DoctorFullName"] . "</td>
                <td style='width:1%; white-space:nowrap;'>
                    <button class='action view' data-id='" . $row["ConsultationID"] . "'>View</button>
                </td>
            </tr>";
        }
        }

    echo "</tbody>
    </table>";
    
    $maxPage = $totalCurrentTableRow % 10 != 0 ? $totalCurrentTableRow % 10 + 1 : $totalCurrentTableRow / 10;

    if ($totalCurrentTableRow > 10) {
     echo   '<div class="pagination">
            <a href="' . getPaginationURL("previous") . '" class="prev"> < </a>
            <div>Page <span>' . $page . '</span> of <span>' . $maxPage . '</span></div>
            <a href="'. getPaginationURL("next") . '" class="next"> ></a>
        </div>';
    }
?>


</div>

<div id="footer">
    basta contact info
</div>

<script src="./script.js"></script>

</body>
</html>
