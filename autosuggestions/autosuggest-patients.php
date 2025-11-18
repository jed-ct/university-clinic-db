<?php 
    include('../database.php');
    $name = $_GET['name'];
    $sql = "SELECT CONCAT(
        PATIENT.PatientFirstName, ' ',
        IFNULL(CONCAT(PATIENT.PatientMiddleInit, '. '), ''),
        PATIENT.PatientLastName
    ) AS PatientFullName
FROM PATIENT
WHERE CONCAT(
        PATIENT.PatientFirstName, ' ',
        IFNULL(CONCAT(PATIENT.PatientMiddleInit, '. '), ''),
        PATIENT.PatientLastName
    ) LIKE '%$name%'
    	ORDER BY CONCAT(
        PATIENT.PatientFirstName, ' ',
        IFNULL(CONCAT(PATIENT.PatientMiddleInit, '. '), ''),
        PATIENT.PatientLastName) ASC
LIMIT 3;";
    $result = $conn->query($sql);

    if (!$result) {
        die("SQL Error: " . $conn->error . "\nQuery: " . $sql);
    }

    $patients = [];
    while ($row = $result->fetch_assoc()) {
        $patients[] = $row['PatientFullName'];
    }
    header('Content-Type: application/json');
    echo json_encode($patients);

?>