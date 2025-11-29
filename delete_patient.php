<?php 
include ('database.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "UPDATE Patient SET PatientIsActive = 'FALSE' WHERE PatientID = $id;";
    
    if ($conn->query($sql) === TRUE) {
        echo "success";
    } else {
        echo "error";
    }
}
?>

