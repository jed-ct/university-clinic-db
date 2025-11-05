<?php
include("database.php");

if (isset($_GET['id'])) {
    
    $id = intval($_GET['id']); // sanitize input
    $sql = "SELECT CONSULTATION.ConsultationID, 
               DATE_FORMAT(CONSULTATION.ConsultDateTime, '%M %e, %Y') AS ConsultDate, 
               DATE_FORMAT(CONSULTATION.ConsultDateTime, '%l:%i %p') AS ConsultTime,
               PATIENT.PatientFirstName, PATIENT.PatientMiddleInit, PATIENT.PatientLastName,
               TIMESTAMPDIFF(YEAR, PATIENT.PatientBirthday, CONSULTATION.ConsultDateTime) AS PatientAge,
               DIAGNOSIS.Diagnosis, PRESCRIPTION.Prescription, CONSULTATION.Remarks,
               DOCTOR.DocFirstName, DOCTOR.DocMiddleInit, DOCTOR.DocLastName
        FROM CONSULTATION
        INNER JOIN PATIENT ON PATIENT.PatientID = CONSULTATION.PatientID
        INNER JOIN DOCTOR ON DOCTOR.DoctorID = CONSULTATION.DoctorID
        INNER JOIN DIAGNOSIS ON DIAGNOSIS.DiagnosisID = CONSULTATION.DiagnosisID
        INNER JOIN PRESCRIPTION ON PRESCRIPTION.PrescriptionID = CONSULTATION.PrescriptionID
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
