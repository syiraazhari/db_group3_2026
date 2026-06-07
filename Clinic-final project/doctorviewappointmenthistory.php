<?php
session_start();
require("../config/db.php");

$doctor_id = $_SESSION['doctor_id'];

$stmt = $pdo->prepare("
    SELECT * FROM APPOINTMENT 
    WHERE DoctorID = ? 
    ORDER BY AppointmentDate DESC
");
$stmt->execute([$doctor_id]);

foreach ($stmt as $row) {
    echo $row['AppointmentDate'] . " - " . $row['AppointmentStatus'] . "<br>";
}
?>