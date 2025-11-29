<?php
include('database.php');

$query = isset($_GET['query']) ? $_GET['query'] : '';
$orderBy = $_GET['orderBy'];
$orderDir = $_GET['orderDir'];



$tableData = '';
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

if ($query) {
    $sql .= " AND (CONCAT(
        PATIENT.PatientFirstName, ' ',
        IFNULL(CONCAT(PATIENT.PatientMiddleInit, '. '), ''),
        PATIENT.PatientLastName
    ) LIKE '%$query%' OR CONCAT(
        DOCTOR.DocFirstName, ' ',
        IFNULL(CONCAT(DOCTOR.DocMiddleInit, '. '), ''),
        DOCTOR.DocLastName
    ) LIKE '%$query%')";
}

if ($orderBy && $orderDir) {
    $sql .= " ORDER BY $orderBy $orderDir";
}

$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    $tableData .= "<tr>
                <td data-label='Date'>" . date("M j, Y", strtotime($row["ConsultDateTime"])) . "</td>
                <td data-label='Time'>" . date("g:i A", strtotime($row["ConsultDateTime"])) . "</td>
                <td data-label='Patient'>" . $row["PatientFullName"] . "</td>
                <td data-label='Diagnosis'>" . $row["Diagnosis"] . "</td>
                <td data-label='Doctor'>" . $row["DoctorFullName"] . "</td>
                <td style='width:1%; white-space:nowrap;'>
                    <button class='action view' onclick=viewConsultation(" . $row["ConsultationID"] . ")><img src='./img/view.svg' class='action-icon'></button>
                    <button class='action edit' data-id='" . $row["ConsultationID"]  . "'><img src='./img/edit.svg' class='action-icon'></button>
                    <button class='action delete' data-id='" . $row["ConsultationID"]  . "'><img src='./img/delete.svg' class='action-icon'></button>
                </td>
            </tr>";
}

echo $tableData;
?>