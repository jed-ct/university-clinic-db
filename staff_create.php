<?php
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first = $_POST['firstname'];
    $last = $_POST['lastname'];
    $middle = $_POST['middleinit'];
    $specialty = $_POST['specialtyid'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $contact = $_POST['contact']; // New Contact Field
    $dob = !empty($_POST['dob']) ? date('Y-m-d', strtotime($_POST['dob'])) : NULL;

    // Insert into DocContactNo
    $sql = "INSERT INTO DOCTOR (DocFirstName, DocLastName, DocMiddleInit, SpecialtyID, DocEmail, DocAddress, DocContactNo, DocDOB) 
            VALUES ('$first', '$last', '$middle', '$specialty', '$email', '$address', '$contact', '$dob')";

    if ($conn->query($sql) === TRUE) {
        header("Location: staff.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>