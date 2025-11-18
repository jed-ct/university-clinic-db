<?php
include("database.php");

if (!isset($_GET['q']) || empty($_GET['q'])) {
    echo "";
    exit;
}

$searchname = htmlspecialchars($_GET['q']);

if (!preg_match("/^[A-Za-z.-]+(?:[ .-][A-Za-z.-]+)*$/", $searchname)) {
    echo "<p class='error-search'>Invalid characters in search.</p>";
    exit;
}

$search_term = "%".$searchname."%";

$sql = "SELECT * FROM `PATIENT` WHERE 
        `PatientFirstName` LIKE ? 
        OR `PatientLastName` LIKE ? 
        OR CONCAT(PatientFirstName, ' ', PatientLastName) LIKE ? 
        OR CONCAT(PatientFirstName, ' ', PatientMiddleInit, ' ', PatientLastName) LIKE ? 
        OR CONCAT(PatientFirstName, ' ', PatientMiddleInit) LIKE ? 
        OR CONCAT(PatientMiddleInit, ' ', PatientLastName) LIKE ? 
        OR CONCAT(PatientLastName,' ', PatientFirstName) LIKE ?
        ORDER BY `PatientID`";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "sssssss", 
    $search_term, $search_term, $search_term, $search_term,
    $search_term, $search_term, $search_term
);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($res) === 0) {
    echo "<p>No patients found.</p>";
    exit;
}


while ($row = mysqli_fetch_assoc($res)) {
    echo "
        <div>
            <a href='get_patient.php?id={$row['PatientID']}'>
                <h4 class='link-to-other'>"
                    .htmlspecialchars($row['PatientFirstName'])." "
                    .htmlspecialchars($row['PatientMiddleInit'])." "
                    .htmlspecialchars($row['PatientLastName']).
                "</h4>
            </a>
            <hr>
        </div>
    ";
}
?>
