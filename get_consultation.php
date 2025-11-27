<?php
include("database.php");

if (isset($_GET['id'])) {
    
    $id = intval($_GET['id']); // sanitize input
    $sql = "SELECT CONSULTATION.ConsultationID, 
               DATE_FORMAT(CONSULTATION.ConsultDateTime, '%b %e, %Y') AS ConsultDate, 
               DATE_FORMAT(CONSULTATION.ConsultDateTime, '%l:%i %p') AS ConsultTime, PATIENT.PatientID, CONCAT(
                PATIENT.PatientFirstName, ' ',
                IFNULL(CONCAT(PATIENT.PatientMiddleInit, '. '), ''),
                PATIENT.PatientLastName
                ) AS PatientFullName,
               TIMESTAMPDIFF(YEAR, PATIENT.PatientBirthday, CONSULTATION.ConsultDateTime) AS PatientAge,
               CONSULTATION.Diagnosis, CONSULTATION.Prescription, CONSULTATION.Remarks, DOCTOR.DoctorID,
                CONCAT(
                    DOCTOR.DocFirstName, ' ',
                    IFNULL(CONCAT(DOCTOR.DocMiddleInit, '. '), ''),
                    DOCTOR.DocLastName
                ) AS DoctorFullName
        FROM CONSULTATION
        INNER JOIN PATIENT ON PATIENT.PatientID = CONSULTATION.PatientID
        INNER JOIN DOCTOR ON DOCTOR.DoctorID = CONSULTATION.DoctorID
        WHERE CONSULTATION.ConsultationID = $id
        LIMIT 1";

    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $data = $result->fetch_assoc();
        echo json_encode($data);
    } else {
        echo json_encode(["error" => "Consultation not found."]);
    }
}
?>
