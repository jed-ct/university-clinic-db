<?php 
    date_default_timezone_set('Asia/Manila');
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    
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
    $id = intval($_POST["id"]);
    $patientID = searchIDFromTable("PATIENT", "PATIENT", $_POST["PatientName"], "PatientID");
    $diagnosis = $_POST["Diagnosis"];
    $prescription = $_POST["Prescription"];
    $remarks = $_POST["Remarks"];
    $doctorID = searchIDFromTable("DOCTOR", "DOCTOR", $_POST["DoctorName"], "DoctorID");

    $sql = "UPDATE CONSULTATION SET 
            ConsultDateTime = '$formattedDatetime',
            PatientID = '$patientID',
            Diagnosis = '$diagnosis',
            Prescription = '$prescription',
            Remarks = '$remarks',
            DoctorID = '$doctorID'
        WHERE ConsultationID = $id";

    if (mysqli_query($conn, $sql)) {
        echo "Record edited successfully";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
?>