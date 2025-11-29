<?php 
include('database.php');

    $sqlConsultations = "SELECT * FROM CONSULTATION";
    $sqlDoctors = "SELECT * FROM DOCTOR";
    $sqlPatients = "SELECT * FROM PATIENT";


    echo json_encode([
        "totalConsultations" => ($conn->query($sqlConsultations))->num_rows,
        "totalDoctors" => ($conn->query($sqlDoctors))->num_rows,
        "totalPatients" => ($conn->query($sqlPatients))->num_rows
    ]);
    
?>