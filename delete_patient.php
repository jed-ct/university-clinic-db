<?php 
include ('database.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // First delete consultations for this patient
    $conn->query("DELETE FROM Consultation WHERE PatientID = $id");

    // Then delete the patient
    $sql = "DELETE FROM Patient WHERE PatientID = $id";
    
    if ($conn->query($sql) === TRUE) {
        echo "success";
    } else {
        echo "error";
    }
}
?>