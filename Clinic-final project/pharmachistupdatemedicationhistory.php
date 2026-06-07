<?php
require("../config/db.php");

$patient_id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM PRESCRIPTION WHERE PatientID=?");
$stmt->execute([$patient_id]);

foreach ($stmt as $row) {
    echo $row['MedicationDetails'] . " - " . $row['DispensingStatus'] . "<br>";
}
?>