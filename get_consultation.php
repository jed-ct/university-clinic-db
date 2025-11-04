<?php
include("database.php");

if (isset($_GET['id'])) {
    
    $id = intval($_GET['id']); // sanitize input
    echo $id;
    // $sql = "SELECT CONSULTATION.ConsultationID, CONSULTATION.ConsultDateTime,
    //                PATIENT.PatientFirstName, PATIENT.PatientMiddleInit, PATIENT.PatientLastName,
    //                DOCTOR.DocFirstName, DOCTOR.DocMiddleInit, DOCTOR.DocLastName,
    //                CONSULTATION.Diagnosis, CONSULTATION.Prescription
    //         FROM CONSULTATION
    //         INNER JOIN PATIENT ON PATIENT.PatientID = CONSULTATION.PatientID
    //         INNER JOIN DOCTOR ON DOCTOR.DoctorID = CONSULTATION.DoctorID
    //         WHERE CONSULTATION.ConsultationID = $id
    //         LIMIT 1";

    // $result = $conn->query($sql);

    // if ($result && $result->num_rows > 0) {
    //     $data = $result->fetch_assoc();
    //     echo json_encode($data);
    // } else {
    //     echo json_encode(["error" => "Consultation not found."]);
    // }
}
?>
