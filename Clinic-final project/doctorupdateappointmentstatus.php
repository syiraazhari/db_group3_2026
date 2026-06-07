<?php
require("../config/db.php");

$id = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $status = $_POST['status'];

    $stmt = $pdo->prepare("UPDATE APPOINTMENT SET AppointmentStatus=? WHERE AppointmentID=?");
    $stmt->execute([$status, $id]);

    header("Location: appointments.php");
}
?>

<form method="POST">
<select name="status">
<option>Pending</option>
<option>Completed</option>
</select>
<button>Update</button>
</form>