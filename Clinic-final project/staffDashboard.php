<?php
// receptionist.php
session_start();
include('database.php'); // <-- your database connection file

// Check if receptionist is logged in
if(!isset($_SESSION['receptionistID'])){
    header("Location: login.php");
    exit();
}

// Handle new appointment form submission
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['addAppointment'])){
    $patientID = $_POST['patientID'];
    $doctorID = $_POST['doctorID'];
    $date = $_POST['appointmentDate'];
    $time = $_POST['appointmentTime'];

    $sql = "INSERT INTO appointment (PatientID, DoctorID, AppointmentDate, AppointmentTime, AppointmentStatus) 
            VALUES ('$patientID', '$doctorID', '$date', '$time', 'Pending')";
    if(mysqli_query($conn, $sql)){
        echo "<p style='color:green;'>Appointment added successfully!</p>";
    } else {
        echo "<p style='color:red;'>Error: " . mysqli_error($conn) . "</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Receptionist Dashboard</title>
    <style>
        body { font-family: Arial; background:#F5F7FA; margin:0; }
        header { background:#2C3E50; color:white; padding:15px; text-align:center; }
        nav { background:#34495E; padding:10px; }
        nav a { color:white; margin:0 15px; text-decoration:none; font-weight:bold; }
        nav a:hover { color:#1ABC9C; }
        .container { padding:20px; }
        table { width:100%; border-collapse:collapse; margin-top:15px; }
        table, th, td { border:1px solid #BDC3C7; }
        th, td { padding:10px; text-align:left; }
        .btn { background:#1ABC9C; color:white; padding:6px 12px; border:none; cursor:pointer; }
        .btn:hover { background:#16A085; }
    </style>
</head>
<body>
    <header>
        <h1>Receptionist Dashboard</h1>
    </header>
    <nav>
        <a href="receptionist.php">Appointments</a>
        <a href="queue.php">Queue Management</a>
        <a href="patient.php">Patient Records</a>
        <a href="logout.php">Logout</a>
    </nav>
    <div class="container">
        <h2>Add Appointment</h2>
        <form method="POST">
            <label>Patient ID:</label>
            <input type="text" name="patientID" required><br><br>
            <label>Doctor ID:</label>
            <input type="text" name="doctorID" required><br><br>
            <label>Date:</label>
            <input type="date" name="appointmentDate" required><br><br>
            <label>Time:</label>
            <input type="time" name="appointmentTime" required><br><br>
            <button type="submit" name="addAppointment" class="btn">Add Appointment</button>
        </form>

        <h2>Queue Status</h2>
        <table>
            <tr>
                <th>Queue Number</th>
                <th>Patient ID</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php
            $queueResult = mysqli_query($conn, "SELECT * FROM queue");
            while($row = mysqli_fetch_assoc($queueResult)){
                echo "<tr>
                        <td>{$row['QueueNumber']}</td>
                        <td>{$row['PatientID']}</td>
                        <td>{$row['QueueStatus']}</td>
                        <td><a href='updateQueue.php?id={$row['QueueID']}' class='btn'>Update</a></td>
                      </tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>
