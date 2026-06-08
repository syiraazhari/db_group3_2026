<?php
session_start();
include 'database.php';

if(!isset($_SESSION['doctor_id'])) {
    header("Location: doctor_auth.php");
    exit();
}

$doctor_id = $_SESSION['doctor_id'];
$doctor_name = $_SESSION['doctor_name'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Doctor Dashboard</title>
    <style>
        body { font-family: Arial; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); margin: 0; padding: 20px; }
        .dashboard { max-width: 800px; margin: 50px auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); }
        h2 { color: #667eea; }
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #667eea; padding-bottom: 10px; }
        .logout { background: #e74c3c; color: white; padding: 8px 15px; text-decoration: none; border-radius: 5px; }
        .logout:hover { background: #c0392b; }
        .welcome { margin: 20px 0; padding: 15px; background: #f0f4ff; border-radius: 5px; }
        .menu { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-top: 30px; }
        .menu-card { background: #f9f9f9; padding: 20px; text-align: center; border-radius: 8px; text-decoration: none; color: #333; transition: transform 0.3s; }
        .menu-card:hover { transform: translateY(-5px); background: #f0f4ff; }
        .menu-card h3 { margin: 0; color: #667eea; }
    </style>
</head>
<body>

<div class="dashboard">
    <div class="header">
        <h2>👨‍⚕️ Doctor Dashboard</h2>
        <a href="doctor_auth.php?action=logout" class="logout">Logout</a>
    </div>
    
    <div class="welcome">
        <strong>Welcome, Dr. <?php echo $doctor_name; ?>!</strong><br>
        You are logged in as a doctor.
    </div>
    
    <div class="menu">
        <a href="view_appointments.php" class="menu-card">
            <h3>📅 Appointments</h3>
            <p>View today's appointments</p>
        </a>
        <a href="view_patients.php" class="menu-card">
            <h3>👥 My Patients</h3>
            <p>View assigned patients</p>
        </a>
        <a href="patient_records.php" class="menu-card">
            <h3>📋 Medical Records</h3>
            <p>Update patient records</p>
        </a>
        <a href="prescriptions.php" class="menu-card">
            <h3>💊 Prescriptions</h3>
            <p>Manage prescriptions</p>
        </a>
    </div>
</div>

</body>
</html>