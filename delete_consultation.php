<?php 

include ('database.php');

    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $sql = "DELETE FROM CONSULTATION WHERE ConsultationID = $id";
        if ($conn->query($sql) === TRUE) {
            echo "Record deleted successfully";
        } else {
            echo "Error deleting record: " . $conn->error;
        }
    }
?>