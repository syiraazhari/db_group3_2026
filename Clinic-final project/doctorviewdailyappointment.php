<?php
session_start();
require("../config/db.php");

$doctor_id = $_SESSION['doctor_id'];

$stmt = $pdo->prepare("
    SELECT A.*, P.PatientName 
    FROM APPOINTMENT A
    JOIN PATIENT P ON A.PatientID = P.PatientID
    WHERE A.DoctorID = ? AND A.AppointmentDate = CURDATE()
");
$stmt->execute([$doctor_id]);
$data = $stmt->fetchAll();

foreach ($data as $row) {
    echo $row['PatientName'] . " - " . $row['AppointmentTime'] . "<br>";
}
?>