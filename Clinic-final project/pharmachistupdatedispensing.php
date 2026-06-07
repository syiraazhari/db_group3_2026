<?php
require("../config/db.php");

$id = $_GET['id'];

$stmt = $pdo->prepare("
    UPDATE PRESCRIPTION 
    SET DispensingStatus='Dispensed', DispensingDate=CURDATE()
    WHERE PrescriptionID=?
");
$stmt->execute([$id]);

header("Location: prescriptions.php");
?>