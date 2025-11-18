<?php
include("database.php");

if (isset($_GET['id'])) {

    $id = intval($_GET['id']);

    $sql = "SELECT PatientID, PatientFirstName, PatientMiddleInit, PatientLastName,
                   DATE_FORMAT(PatientBirthday, '%Y-%m-%d') AS PatientBirthday,
                   PatientSex, PatientContactNo
            FROM PATIENT
            WHERE PatientID = $id
            LIMIT 1";

    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $data = $result->fetch_assoc();
        echo json_encode($data);
    } else {
        echo json_encode(["error" => "Patient not found."]);
    }
}
?>