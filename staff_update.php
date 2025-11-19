<?php
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);
    $first = $_POST['firstname'];
    $last = $_POST['lastname'];
    $middle = $_POST['middleinit'];
    $specialty = $_POST['specialtyid'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $contact = $_POST['contact']; // New Contact Field
    $dob = !empty($_POST['dob']) ? date('Y-m-d', strtotime($_POST['dob'])) : NULL;

    // Update DocContactNo
    $sql = "UPDATE DOCTOR SET 
            DocFirstName='$first', 
            DocLastName='$last', 
            DocMiddleInit='$middle', 
            SpecialtyID='$specialty',
            DocEmail='$email', 
            DocAddress='$address', 
            DocContactNo='$contact', 
            DocDOB='$dob' 
            WHERE DoctorID=$id";

    if ($conn->query($sql) === TRUE) {
        header("Location: staff.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>