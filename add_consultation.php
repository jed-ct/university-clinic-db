<?php 
    date_default_timezone_set('Asia/Manila');
    
    include("database.php");
    function searchIDFromTable($tableName, $queryColumn, $searchQuery, $idColumn) {
        global $conn;
        if ($tableName == "PATIENT") {
            $sql = "SELECT * FROM $tableName WHERE CONCAT($tableName.PatientFirstName, ' ', IFNULL(CONCAT($tableName.PatientMiddleInit, '. '), ''), $tableName.PatientLastName) LIKE '%$searchQuery%' LIMIT 1";
            $result = $conn->query($sql);        
        }
        else if ($tableName == "DOCTOR") {
            $sql = "SELECT * FROM $tableName WHERE CONCAT($tableName.DocFirstName, ' ', IFNULL(CONCAT($tableName.DocMiddleInit, '. '), ''), $tableName.DocLastName) LIKE '%$searchQuery%'  LIMIT 1";
            $result = $conn->query($sql); 
        }

        else {
            $sql = "SELECT * FROM $tableName WHERE $queryColumn LIKE '%$searchQuery%'  LIMIT 1";
            $result = $conn->query($sql);
        }

    if ($result && $row = $result->fetch_assoc()) {
        return $row[$idColumn];
    }

    return null;
    }
    $formattedDatetime = null;
    if (isset($_POST["ConsultationDate"]) && isset($_POST["ConsultationTime"])) {
        $formattedDatetime = $_POST["ConsultationDate"] . ' ' . $_POST["ConsultationTime"] . ':00'; 
    }
    else {
        $formattedDatetime = date("Y-m-d H:i:s");
    }

    $patientID = searchIDFromTable("PATIENT", "PATIENT", $_POST["PatientName"], "PatientID");
    $diagnosisID = searchIDFromTable("DIAGNOSIS", "Diagnosis", $_POST["Diagnosis"], "DiagnosisID");
    $prescriptionID = searchIDFromTable("PRESCRIPTION", "Prescription", $_POST["Prescription"], "PrescriptionID");
    $remarks = $_POST["Remarks"];
    $doctorID = searchIDFromTable("DOCTOR", "DOCTOR", $_POST["DoctorName"], "DoctorID");

    $sql = "INSERT INTO CONSULTATION (PatientID, DiagnosisID, PrescriptionID, Remarks, ConsultDateTime, DoctorID) VALUES($patientID, $diagnosisID, $prescriptionID, '$remarks', '$formattedDatetime', $doctorID);";

    if (mysqli_query($conn, $sql)) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
?>