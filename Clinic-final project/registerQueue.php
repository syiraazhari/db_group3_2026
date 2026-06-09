<?php
$con = mysqli_connect('localhost','root','','clinic_db');
if (!$con) { die("Connection failed: " . mysqli_connect_error()); }

// Run only when form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form values safely
    $patientId = isset($_POST['patientId']) ? $_POST['patientId'] : null;
    $doctorId = isset($_POST['doctorId']) ? $_POST['doctorId'] : null;
    $receptionistId = isset($_POST['receptionistId']) ? $_POST['receptionistId'] : null;
    $availability = isset($_POST['availability']) ? $_POST['availability'] : null;

    if ($patientId && $doctorId && $receptionistId && $availability) {
        // Insert new queue entry (queueId auto-increment in DB)
        $sql = "INSERT INTO queue (patientId, doctorId, receptionistId, availability)
                VALUES ('$patientId', '$doctorId', '$receptionistId', '$availability')";

        if (mysqli_query($con, $sql)) {
            header("Location: editQueue(RECEPTIONIST).php");
        } else {
            echo "<p style='color:red; text-align:center;'>Error: " . mysqli_error($con) . "</p>";
        }
    } else {
        echo "<p style='color:red; text-align:center;'>All fields are required.</p>";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
<title>Register Queue</title>
<style>
    body { font-family: Arial; background:#f5f5f5; }
    .form-box { width:400px; margin:50px auto; background:white; padding:20px; border-radius:10px; box-shadow:0 2px 10px rgba(0,0,0,0.1); }
    h2 { text-align:center; color:#2c3e50; }
    label { display:block; margin-top:10px; }
    input, select { width:100%; padding:8px; margin-top:5px; }
    button { margin-top:15px; background:#27ae60; color:white; padding:10px; border:none; border-radius:5px; cursor:pointer; width:100%; }
    button:hover { background:#219150; }
</style>
</head>
<body>
<div class="form-box">
    <h2>Queue Registration</h2>
    <form method="POST">
        <label>Patient ID:</label>
        <input type="number" name="patientId" required>

        <label>Doctor ID:</label>
        <input type="number" name="doctorId" required>

        <label>Receptionist ID:</label>
        <input type="number" name="receptionistId" required>

        <label>Availability:</label>
        <select name="availability" required>
            <option value="Waiting">Waiting</option>
            <option value="Proceed">Proceed</option>
            <option value="In Consultation">In Consultation</option>
			<option value="Finish">Finish</option>
        </select>

        <button type="submit">Register Queue</button>
    </form>
</div>
</body>
</html>

<?php mysqli_close($con); ?>
