

<?php 
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    include('../database.php');
    $name = $_GET['name'];
    $sql = "SELECT CONCAT(
        DOCTOR.DocFirstName, ' ',
        IFNULL(CONCAT(DOCTOR.DocMiddleInit, '. '), ''),
        DOCTOR.DocLastName
    ) AS DoctorFullName
    FROM DOCTOR
WHERE CONCAT(
        DOCTOR.DocFirstName, ' ',
        IFNULL(CONCAT(DOCTOR.DocMiddleInit, '. '), ''),
        DOCTOR.DocLastName
    ) LIKE '%$name%'
    	ORDER BY CONCAT(
        DOCTOR.DocFirstName, ' ',
        IFNULL(CONCAT(DOCTOR.DocMiddleInit, '. '), ''),
        DOCTOR.DocLastName) ASC LIMIT 3;";
    $result = $conn->query($sql);

    if (!$result) {
        die("SQL Error: " . $conn->error . "\nQuery: " . $sql);
    }

    $doctors = [];
    while ($row = $result->fetch_assoc()) {
        $doctors[] = $row['DoctorFullName'];
    }
    header('Content-Type: application/json');
    echo json_encode($doctors);

?>