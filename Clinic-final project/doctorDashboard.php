<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Require doctor login
requireDoctorLogin();

$doctorId = $_SESSION['doctor_id'];
$doctorName = $_SESSION['doctor_name'];

// Get statistics - Using COUNT and conditional statements
$stats = [];

// Total appointments - READ operation
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

// Today's appointments - READ with WHERE and DATE
$today = date('Y-m-d');
$sql = "SELECT COUNT(*) as today FROM appointment WHERE doctorId = $doctorId AND apptDate = '$today'";
$result = mysqli_query($conn, $sql);
$stats['today'] = mysqli_fetch_assoc($result)['today'];

// Get today's appointments for display - READ with JOIN
$todayAppointments = getTodayAppointments($conn, $doctorId);

// Get queue count
$sql = "SELECT COUNT(*) as queueCount FROM queue WHERE doctorId = $doctorId";
$result = mysqli_query($conn, $sql);
$queueCount = mysqli_fetch_assoc($result)['queueCount'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Dashboard - Clinic Management System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f4f4;
        }
        
        .header {
            background: #2c3e50;
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .nav {
            background: #34495e;
            padding: 10px 20px;
        }
        
        .nav a {
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            margin: 0 5px;
            display: inline-block;
        }
        
        .nav a:hover {
            background: #2c3e50;
            border-radius: 5px;
        }
        
        .container {
            padding: 30px;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .stat-number {
            font-size: 36px;
            font-weight: bold;
            color: #3498db;
            margin: 10px 0;
        }
        
        .stat-label {
            color: #7f8c8d;
            font-size: 14px;
        }
        
        .section {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .section h3 {
            margin-bottom: 20px;
            color: #2c3e50;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        th {
            background: #f8f9fa;
            color: #2c3e50;
        }
        
        .status {
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
            font-weight: bold;
        }
        
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        
        .status-completed {
            background: #d4edda;
            color: #155724;
        }
        
        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
        }
        
        .btn {
            padding: 5px 10px;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 12px;
        }
        
        .btn:hover {
            background: #2980b9;
        }
        
        .welcome {
            font-size: 18px;
        }
        
        .logout-btn {
            background: #e74c3c;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            color: white;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Clinic Management System</h1>
        <div class="welcome">
            Welcome, Dr. <?php echo $doctorName; ?> | 
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </div>
    
    <div class="nav">
        <a href="dashboard.php">Dashboard</a>
        <a href="view_appointments.php">My Appointments</a>
        <a href="view_queue.php">Patient Queue</a>
        <a href="profile.php">My Profile</a>
    </div>
    
    <div class="container">
        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Total Appointments</div>
                <div class="stat-number"><?php echo $stats['total']; ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Pending Appointments</div>
                <div class="stat-number"><?php echo $stats['pending']; ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Completed Appointments</div>
                <div class="stat-number"><?php echo $stats['completed']; ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Today's Appointments</div>
                <div class="stat-number"><?php echo $stats['today']; ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Patients in Queue</div>
                <div class="stat-number"><?php echo $queueCount; ?></div>
            </div>
        </div>
        
        <!-- Today's Appointments Section -->
        <div class="section">
            <h3>Today's Appointments (<?php echo date('F j, Y'); ?>)</h3>
            <?php if (mysqli_num_rows($todayAppointments) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Patient Name</th>
                            <th>IC Number</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($appointment = mysqli_fetch_assoc($todayAppointments)): ?>
                            <tr>
                                <td><?php echo date('h:i A', strtotime($appointment['apptTime'])); ?></td>
                                <td><?php echo $appointment['patientName']; ?></td>
                                <td><?php echo $appointment['patientIc']; ?></td>
                                <td><?php echo $appointment['patientPhoneNo']; ?></td>
                                <td>
                                    <span class="status status-<?php echo strtolower($appointment['status']); ?>">
                                        <?php echo $appointment['status']; ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="update_appointment.php?id=<?php echo $appointment['apptId']; ?>" class="btn">
                                        Update
                                    </a>
                                </td>
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