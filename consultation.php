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
        return "/clinic_db/consultation.php?" . $query_string;

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
                        <div id="add-patient-autosuggest" class="autosuggest-box"></div>
                    </div>
                </fieldset>

                <fieldset class='consultation-fieldset'>
                    <legend>Consultation</legend>

                    <div class="forms-input">
                        <label for="add-diagnosis">Diagnosis *</label>
                        <input type="text" name="Diagnosis" id="add-diagnosis" maxlength="64">
                        <span class='error-message' id='add-diagnosis-error-message'>Yipeee</span>
                        <div id="add-diagnosis-autosuggest" class="autosuggest-box"></div>
                    </div>

                    <div class="forms-input">
                        <label for="add-prescription">Prescription *</label>
                        <input type="text" name="Prescription" id="add-prescription" maxlength="64">
                        <span class='error-message' id='add-prescription-error-message'>Yipeee</span>
                        <div id="add-prescription-autosuggest" class="autosuggest-box"></div>
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
                        <div id="add-doctor-autosuggest" class="autosuggest-box"></div>
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
            <form id='filter-consultation-form' autocomplete='off'>

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
                    <legend>By Treatment</legend>

                    <div class="forms-input">
                        <label for="filter-diagnosis">Diagnosis</label>
                        <input type="text" name="Diagnosis" id="filter-diagnosis" maxlength="64">
                        <span class='error-message' id='filter-diagnosis-error-message'></span>
                        <div id="filter-diagnosis-autosuggest" class="autosuggest-box"></div>
                    </div>

                    <div class="forms-input">
                        <label for="filter-prescription">Prescription</label>
                        <input type="text" name="Prescription" id="filter-prescription" maxlength="64">
                        <span class='error-message' id='filter-prescription-error-message'></span>
                        <div id="filter-prescription-autosuggest" class="autosuggest-box"></div>
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
           <form id='view-consultation-form' method='POST' autocomplete='off'>
                <fieldset class='date-time-fieldset'>
                    <legend>Date and Time</legend>
                    <div id='set-date-time-container'>
                        <label>Date:</label>
                        <div class='view-data' id='view-consultation-date'>Oct 5, 2025</div>

                        <label>Time:</label>
                        <div class='view-data' id='view-consultation-time'>9:35 PM</div>
                    </div> 
                </fieldset>

                <fieldset class='patient-fieldset'>
                    <legend>Patient</legend>
                    <div class="forms-input">
                        <label for="view-patient-name">Patient Name:</label>
                        <div class='view-data' id='view-patient-name'>Jedric C. Tuquero <span class='view-id' id='view-patient-id'>a</span></div>
                    </div>
                </fieldset>

                <fieldset class='consultation-fieldset'>
                    <legend>Consultation</legend>

                    <div class="forms-input">
                        <label for="view-diagnosis">Diagnosis:</label>
                        <div class='view-data' id='view-diagnosis'>Diarrhea</div>
                    </div>

                    <div class="forms-input">
                        <label for="view-prescription">Prescription:</label>
                        <div class='view-data' id='view-prescription'>Diarrhea</div>
                    </div>

                    <div class="forms-input">
                        <label for="view-remarks">Remarks:</label>
                        <div class='view-data' id='view-remarks'>Diarrhea</div>
                    </div>
                </fieldset>
            
                <fieldset class='doctor-fieldset'>
                    <legend>Doctor</legend>
                    <div class="forms-input">
                        <label for="view-doctor-name">Doctor Name:</label>
                        <div class='view-data' id='view-doctor-name'>Gregory A. House <span class='view-id' id='view-doctor-id'>a</span></div>
                    </div>     
                </fieldset>

            </form>
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
           <form id='edit-consultation-form' method='POST' action='./edit_consultation.php' autocomplete='off'>
                <fieldset class='date-time-fieldset'>
                    <legend>Date and Time</legend>
                    <div id='set-date-time-container'>
                        <label>Date</label>
                        <input type="date" name="ConsultationDate" id="edit-consultation-date" min='2024-01-01' value='2025-01-02'>

                        <label>Time</label>
                        <input type="time" name="ConsultationTime" id="edit-consultation-time"> 
                    </div>
                    <span class='error-message' id='edit-datetime-error-message'></span>    
                </fieldset>

                <fieldset class='patient-fieldset'>
                    <legend>Patient</legend>
                    <div class="forms-input">
                        <label for="edit-patient-name">Patient Name *</label>
                        <input type="text" name="PatientName" id="edit-patient-name" pattern="^[A-Za-z.]+([ .][A-Za-z.]+)*$" maxlength="64">
                        <span class='error-message' id='edit-patient-error-message'>Yipeee</span>
                        <div id="edit-patient-autosuggest" class="autosuggest-box"></div>
                    </div>
                </fieldset>

                <fieldset class='consultation-fieldset'>
                    <legend>Consultation</legend>

                    <div class="forms-input">
                        <label for="edit-diagnosis">Diagnosis *</label>
                        <input type="text" name="Diagnosis" id="edit-diagnosis" maxlength="64">
                        <span class='error-message' id='edit-diagnosis-error-message'>Yipeee</span>
                        <div id="edit-prescription-autosuggest" class="autosuggest-box"></div>
                    </div>

                    <div class="forms-input">
                        <label for="edit-prescription">Prescription *</label>
                        <input type="text" name="Prescription" id="edit-prescription" maxlength="64">
                        <span class='error-message' id='edit-prescription-error-message'>Yipeee</span>
                        <div id="edit-prescription-autosuggest" class="autosuggest-box"></div>
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
                        <span class='error-message' id='edit-doctor-error-message'>Yipeee</span>
                        <div id="edit-doctor-autosuggest" class="autosuggest-box"></div>
                    </div>     
                </fieldset>

            </form>
        </div>

        <div class='consultation-modal-actions'>
            <button id='confirm-edit-btn' class='action add' type='submit' form='edit-consultation-form' data-id=''>Edit</button>
        </div>

    </div>
</div>

<!-- DELETE CONFIRMATION MODAL -->
<div id="delete-confirmation-modal" class='modal'>
    <div class='modal-content'>

        <div class="close-btn-div">
            <div>Delete Consultation</div>
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
    <ul class="links" id='header-links'>
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
        <div>
            <button class='consultations action' id='filter-consultation-btn'><i class="fa-solid fa-filter"></i> <span>Filter</span></button>
            <input type="text" id="consultation-searchbox" placeholder="Filter by patient or doctor">
        </div>
        <button class="consultations action" id='add-consultation-btn'><i class="fa-solid fa-plus"></i> <span>Add new consultation</span></button>
    </div>

<table id='consultations-table' class='consultations-table'>
    <thead>
        <tr>
            <th data-col="ConsultDateTime" class="sortable active desc">Date</th>
            <th>Time</th>

            <th data-col="CONCAT(PATIENT.PatientFirstName, ' ', IFNULL(CONCAT(PATIENT.PatientMiddleInit, '. '), ''), PATIENT.PatientLastName)" class="sortable">Patient</th>
            <th data-col="Diagnosis" class="sortable">Diagnosis</th>
            <th data-col="CONCAT(DOCTOR.DocFirstName, ' ', IFNULL(CONCAT(DOCTOR.DocMiddleInit, '. '), ''), DOCTOR.DocLastName)" class="sortable">Doctor</th>

            <th></th>
        </tr>
    </thead>
    <tbody id='consultations-table-body'>
    </tbody>
</table>    
    <?php
    // $sql = "SELECT CONSULTATION.ConsultationID, CONSULTATION.ConsultDateTime,
    // CONCAT(
    //     PATIENT.PatientFirstName, ' ',
    //     IFNULL(CONCAT(PATIENT.PatientMiddleInit, '. '), ''),
    //     PATIENT.PatientLastName
    // ) AS PatientFullName,
    // CONCAT(
    //     DOCTOR.DocFirstName, ' ',
    //     IFNULL(CONCAT(DOCTOR.DocMiddleInit, '. '), ''),
    //     DOCTOR.DocLastName
    // ) AS DoctorFullName,
    // CONSULTATION.Diagnosis, CONSULTATION.Prescription
    // FROM PATIENT
    // INNER JOIN CONSULTATION ON PATIENT.PatientID = CONSULTATION.PatientID
    // INNER JOIN DOCTOR ON DOCTOR.DoctorID = CONSULTATION.DoctorID
    // WHERE 1=1";

    // $result = $conn->query($sql); 
    // $totalUnfilteredTableRow = $result->num_rows;

    // if ($filterStartDate && $filterEndDate) {
    //     $sql .= " AND DATE(ConsultDateTime) BETWEEN '$filterStartDate' AND '$filterEndDate'";
    //     $isTableFiltered = true;
    // }
    // else if ($filterStartDate) {
    //     $sql .= " AND DATE(ConsultDateTime) >= '$filterStartDate'";
    //     $isTableFiltered = true;
    // }
    // else if ($filterEndDate) {
    //     $sql .= " AND DATE(ConsultDateTime) <= '$filterEndDate'";
    //     $isTableFiltered = true;
    // }
    
    // if ($filterDiagnosis) {
    //     $sql .= " AND CONSULTATION.Diagnosis LIKE '%$filterDiagnosis%'";
    //     $isTableFiltered = true;
    // }

    // if ($filterPrescription) {
    //     $sql .= " AND CONSULTATION.Prescription LIKE '%$filterPrescription%'";
    //     $isTableFiltered = true;
    // }
    // $result = $conn->query($sql);

    // $totalCurrentTableRow = $result->num_rows;
    // if (!$isTableFiltered) {
    //     $totalCurrentTableRow = $totalUnfilteredTableRow;
    // }
    
    // $sql .= " ORDER BY CONSULTATION.ConsultDateTime DESC LIMIT 10";
    // if ($page) {
    //     $offset = ($page - 1) * 10;
    //     $sql .= " OFFSET $offset";
    // }

    // $result = $conn->query($sql); 

    // if ($result->num_rows === 0) {
    //     echo "<div style='font-size: 1.5rem'>Query not found</div>";
    // }
    // else {
    //     echo "
    // <table id='consultations-table' class='consultations-table'>
    //     <thead>
    //         <tr>
    //             <th>Date</th>
    //             <th>Time</th>
    //             <th>Patient</th>
    //             <th>Diagnosis</th>
    //             <th>Doctor</th>
    //             <th></th>
    //         </tr>
    //     </thead>

    //     <tbody>";
    
    //     while ($row = $result->fetch_assoc()) {
    //         echo "<tr>
    //             <td data-label='Date'>" . date("M j, Y", strtotime($row["ConsultDateTime"])) . "</td>
    //             <td data-label='Time'>" . date("g:i A", strtotime($row["ConsultDateTime"])) . "</td>
    //             <td data-label='Patient'>" . $row["PatientFullName"] . "</td>
    //             <td data-label='Diagnosis'>" . $row["Diagnosis"] . "</td>
    //             <td data-label='Doctor'>" . $row["DoctorFullName"] . "</td>
    //             <td style='width:1%; white-space:nowrap;'>
    //                 <button class='action view' data-id='" . $row["ConsultationID"] . "'><img src='./img/view.svg' class='action-icon'></button>
    //                 <button class='action edit' data-id='" . $row["ConsultationID"]  . "'><img src='./img/edit.svg' class='action-icon'></button>
    //                 <button class='action delete' data-id='" . $row["ConsultationID"]  . "'><img src='./img/delete.svg' class='action-icon'></button>
    //             </td>
    //         </tr>";
    //     }
    //     }

    // echo "</tbody>
    // </table>";
    
    // $maxPage = ceil($totalCurrentTableRow / 10);

    // if ($totalCurrentTableRow > 10) {
    //  echo   '<div class="pagination">';
    //     if ($page > 1) {
    //         echo '<a href="' . getPaginationURL("previous") . '" class="prev"> < </a>';
    //     }
    //         echo '<div>Page <span>' . $page . '</span> of <span>' . $maxPage . '</span></div>';
    //     if ($page < $maxPage) {
    //         echo '<a href="'. getPaginationURL("next") . '" class="next"> ></a>';
    //     }
    //     echo '</div>';
    // }
?>


</div>

<div id="footer">
     © 2025 TBA Clinic · tbaclinic@univ.edu.ph · (074) 442 1983
</div>

<script src="./script.js"></script>

</body>
</html>
