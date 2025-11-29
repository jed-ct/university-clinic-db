<?php
include("database.php");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['PatientID'])) {
    $patientID = mysqli_real_escape_string($conn, $_POST['PatientID']);

    $fields = [
        'PatientFirstName' => $_POST['PFirstName'] ?? '',
        'PatientMiddleInit' => $_POST['PMiddleInit'] ?? '',
        'PatientLastName' => $_POST['PLastName'] ?? '',
        'PatientSex' => $_POST['Sex'] ?? '',
        'PatientBirthday' => $_POST['Birthday'] ?? '',
        'PatientContactNo' => $_POST['ContactNo'] ?? ''
    ];

    $updateParts = [];
    foreach ($fields as $column => $value) {        
        $value = mysqli_real_escape_string($conn, $value);
        $updateParts[] = "`$column` = '$value'";
    }
    if (!empty($updateParts)) {
        $sql = "UPDATE `Patient` SET " . implode(", ", $updateParts) . " WHERE `PatientID` = '$patientID'";
        if (mysqli_query($conn, $sql)) {
            echo json_encode(['success' => true, 'message' => 'Patient updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Database error: ' . mysqli_error($conn)]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'No fields to update']);
    }
    
    mysqli_close($conn); 

} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request or missing PatientID.']);
}
?>