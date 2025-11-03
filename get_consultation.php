<?php
include("database.php");

if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(["error" => "Missing consultation ID"]);
    exit;
}

$id = intval($_GET['id']);

$sql = "SELECT CONSULTATION.ConsultationID, CONSULTATION.ConsultDateTime, CONSULTATION.Diagnosis, CONSULTATION.Prescription,
               PATIENT.PatientFirstName, PATIENT.PatientMiddleInit, PATIENT.PatientLastName,
               DOCTOR.DocFirstName, DOCTOR.DocMiddleInit, DOCTOR.DocLastName
        FROM CONSULTATION
        INNER JOIN PATIENT ON PATIENT.PatientID = CONSULTATION.PatientID
        INNER JOIN DOCTOR ON DOCTOR.DoctorID = CONSULTATION.DoctorID
        WHERE CONSULTATION.ConsultationID = $id";

$result = $conn->query($sql);

if (!$result) {
    http_response_code(500);
    echo json_encode(["error" => "Database query failed: " . $conn->error]);
    exit;
}

if ($result->num_rows === 0) {
    http_response_code(404);
    echo json_encode(["error" => "Consultation not found"]);
    exit;
}

$row = $result->fetch_assoc();
header("Content-Type: application/json");
echo json_encode($row);
?>
