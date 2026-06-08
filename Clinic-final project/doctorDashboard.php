<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Require doctor login
if (!isset($_SESSION['doctor_id'])) {
    header("Location: login.php");
    exit();
}

$doctorId = $_SESSION['doctor_id'];
$doctorName = $_SESSION['doctor_name'];

// Get statistics
$stats = [];

// Total appointments
$sql = "SELECT COUNT(*) as total FROM appointment WHERE doctorId = $doctorId";
$result = mysqli_query($conn, $sql);
$stats['total'] = mysqli_fetch_assoc($result)['total'];

// Pending appointments
$sql = "SELECT COUNT(*) as pending FROM appointment WHERE doctorId = $doctorId AND status = 'Pending'";
$result = mysqli_query($conn, $sql);
$stats['pending'] = mysqli_fetch_assoc($result)['pending'];

// Completed appointments
$sql = "SELECT COUNT(*) as completed FROM appointment WHERE doctorId = $doctorId AND status = 'Completed'";
$result = mysqli_query($conn, $sql);
$stats['completed'] = mysqli_fetch_assoc($result)['completed'];

// Today's appointments
$today = date('Y-m-d');
$sql = "SELECT COUNT(*) as today FROM appointment WHERE doctorId = $doctorId AND apptDate = '$today'";
$result = mysqli_query($conn, $sql);
$stats['today'] = mysqli_fetch_assoc($result)['today'];

// Get today's appointments
$todayAppointments = getTodayAppointments($conn, $doctorId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .header {
            background: #2c3e50;
            color: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            text-align: center;
        }
        .stat-number {
            font-size: 32px;
            font-weight: bold;
            color: #3498db;
        }
        .appointments {
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .logout-btn {
            background: #e74c3c;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
        }
        .nav {
            margin-bottom: 20px;
        }
        .nav a {
            background: #34495e;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            margin-right: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Doctor Dashboard</h1>
            <p>Welcome, Dr. <?php echo $doctorName; ?> | <a href="logout.php" class="logout-btn">Logout</a></p>
        </div>
        
        <div class="nav">
            <a href="dashboard.php">Dashboard</a>
            <a href="view_appointments.php">My Appointments</a>
            <a href="view_queue.php">Patient Queue</a>
        </div>
        
        <div class="stats">
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['total']; ?></div>
                <div>Total Appointments</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['pending']; ?></div>
                <div>Pending Appointments</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['completed']; ?></div>
                <div>Completed Appointments</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['today']; ?></div>
                <div>Today's Appointments</div>
            </div>
        </div>
        
        <div class="appointments">
            <h2>Today's Appointments (<?php echo date('F j, Y'); ?>)</h2>
            <?php if (mysqli_num_rows($todayAppointments) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Patient Name</th>
                            <th>IC Number</th>
                            <th>Phone</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($appointment = mysqli_fetch_assoc($todayAppointments)): ?>
                        <tr>
                            <td><?php echo date('h:i A', strtotime($appointment['apptTime'])); ?></td>
                            <td><?php echo $appointment['patientName']; ?></td>
                            <td><?php echo $appointment['patientIc']; ?></td>
                            <td><?php echo $appointment['patientPhoneNo']; ?></td>
                            <td><?php echo $appointment['status']; ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No appointments scheduled for today.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>