<?php
include("database.php");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['PatientID'])) {
    $patientID = $_POST['PatientID'];

    $fetchSql = "SELECT PatientFirstName, PatientLastName, PatientSex, PatientBirthday, PatientContactNo FROM `Patient` WHERE `PatientID` = ?";
    $fetch_stmt = mysqli_prepare($conn, $fetchSql);

    mysqli_stmt_bind_param($fetch_stmt, "i", $patientID);
    mysqli_stmt_execute($fetch_stmt);

    $result = mysqli_stmt_get_result($fetch_stmt);
    
    if (!$row = mysqli_fetch_assoc($result)) {
        echo json_encode(['success' => false, 'message' => 'Error: Patient not found.']);
        mysqli_stmt_close($fetch_stmt);
        mysqli_close($conn);
        exit;
    }

    mysqli_stmt_close($fetch_stmt);

    $checkFirstName = $_POST['PFirstName'] ?? $row['PatientFirstName'];
    $checkLastName = $_POST['PLastName'] ?? $row['PatientLastName'];
    $checkSex = $_POST['Sex'] ?? $row['PatientSex'];
    $checkBirthday = $_POST['Birthday'] ?? $row['PatientBirthday'];
    $checkContactNo = $_POST['ContactNo'] ?? $row['PatientContactNo'];
    $dupe_check = "SELECT PatientID FROM PATIENT 
                   WHERE PatientFirstName = ? 
                   AND PatientLastName = ?
                   AND PatientSex = ?
                   AND PatientBirthday = ? 
                   AND PatientContactNo = ?
                   AND PatientID != ? 
                   LIMIT 1";
                         
    $check_stmt = mysqli_prepare($conn, $dupe_check);
    mysqli_stmt_bind_param($check_stmt, "sssssi", 
        $checkFirstName, 
        $checkLastName, 
        $checkSex, 
        $checkBirthday, 
        $checkContactNo, 
        $patientID
    );
    mysqli_stmt_execute($check_stmt);
    mysqli_stmt_store_result($check_stmt);

    if (mysqli_stmt_num_rows($check_stmt) > 0) {
        echo json_encode(['success' => false, 'message' => 'Error: A patient with this Name, Sex, Birthday, and Contact Number already exists.']);
        mysqli_stmt_close($check_stmt);
        mysqli_close($conn); 
        exit; 
    }
    mysqli_stmt_close($check_stmt);
    $updateParts = [];
    $updateTypes = '';
    $updateinput = [];
    
   $fieldMap = [
        'PFirstName' => 'PatientFirstName',
        'PMiddleInit' => 'PatientMiddleInit',
        'PLastName' => 'PatientLastName',
        'Sex' => 'PatientSex',
        'Birthday' => 'PatientBirthday',
        'ContactNo' => 'PatientContactNo'
    ];

    foreach ($fieldMap as $postKey => $dbColumn) {
        if (isset($_POST[$postKey]) && $_POST[$postKey] !== '') {
            $updateParts[] = "`$dbColumn` = ?";
            $updateinput[] = $_POST[$postKey];
            $updateTypes .= 's'; 
        }
    }
    
    if (!empty($updateParts)) {
        $updateinput[] = $patientID;
        $updateTypes .= 'i'; 
        
        $sql = "UPDATE `Patient` SET " . implode(", ", $updateParts) . " WHERE `PatientID` = ?";
        
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, $updateTypes, ...$updateinput); 
        
        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(['success' => true, 'message' => 'Patient updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Database error: ' . mysqli_error($conn)]);
        }
        mysqli_stmt_close($stmt);

    } else {
        echo json_encode(['success' => true, 'message' => 'No fields were modified.']);
    }
    
    mysqli_close($conn); 

} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request or missing PatientID.']);
}
?>