<?php
require("../config/db.php");

$id = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $notes = $_POST['notes'];
    $diagnosis = $_POST['diagnosis'];
    $medication = $_POST['medication'];

    // Update consultation notes
    $stmt1 = $pdo->prepare("UPDATE APPOINTMENT SET ConsultationNotes=? WHERE AppointmentID=?");
    $stmt1->execute([$notes, $id]);

    // Insert prescription
    $stmt2 = $pdo->prepare("
        INSERT INTO PRESCRIPTION 
        (AppointmentID, Diagnosis, MedicationDetails, DispensingStatus)
        VALUES (?, ?, ?, 'Pending')
    ");
    $stmt2->execute([$id, $diagnosis, $medication]);

    header("Location: appointments.php");
}
?>

<form method="POST">
<input name="notes" placeholder="Notes"><br>
<input name="diagnosis" placeholder="Diagnosis"><br>
<input name="medication" placeholder="Medication"><br>
<button>Submit</button>
</form>