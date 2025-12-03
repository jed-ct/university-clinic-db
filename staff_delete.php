<?php
include 'database.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // --- SOFT DELETE ---
    // Instead of "DELETE FROM", we use "UPDATE" to set IsActive to 0.
    // This hides the doctor from the active list but keeps their data for history.
    $sql = "UPDATE DOCTOR SET IsActive = 0 WHERE DoctorID = $id";

    if ($conn->query($sql) === TRUE) {
        // Success! Redirect back to the main staff page.
        header("Location: staff.php"); 
        exit();
    } else {
        // If there's a database error, show it.
        echo "Error deleting record: " . $conn->error;
    }
}
?>