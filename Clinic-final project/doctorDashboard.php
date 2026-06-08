<?php
session_start();
include 'database.php';

// Check if doctor is logged in
if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'doctor') {
    header("Location: login.php");
    exit();
}

$doctorId = $_SESSION['user_id'];
$doctorName = $_SESSION['user_name'];

// Get statistics
$stats = [];

// Total appointments
$query = "SELECT COUNT(*) as total FROM appointment WHERE doctorId = '$doctorId'";
$result = mysqli_query($conn, $query);
$stats['total'] = mysqli_fetch_assoc($result)['total'];

// Pending appointments
$query = "SELECT COUNT(*) as pending FROM appointment WHERE doctorId = '$doctorId' AND status = 'Pending'";
$result = mysqli_query($conn, $query);
$stats['pending'] = mysqli_fetch_assoc($result)['pending'];

// Completed appointments
$query = "SELECT COUNT(*) as completed FROM appointment WHERE doctorId = '$doctorId' AND status = 'Completed'";
$result = mysqli_query($conn, $query);
$stats['completed'] = mysqli_fetch_assoc($result)['completed'];

// Today's appointments
$today = date('Y-m-d');
$query = "SELECT COUNT(*) as today FROM appointment WHERE doctorId = '$doctorId' AND apptDate = '$today'";
$result = mysqli_query($conn, $query);
$stats['today'] = mysqli_fetch_assoc($result)['today'];

// Get today's appointments
$query = "SELECT a.*, p.patientName, p.patientIc, p.patientPhoneNo 
          FROM appointment a 
          JOIN patient p ON a.patientId = p.patientId 
          WHERE a.doctorId = '$doctorId' AND a.apptDate = '$today'
          ORDER BY a.apptTime ASC";
$todayAppointments = mysqli_query($conn, $query);

// Get queue count
$query = "SELECT COUNT(*) as queueCount FROM queue WHERE doctorId = '$doctorId' AND queueStatus = 'Waiting'";
$result = mysqli_query($conn, $query);
$queueCount = mysqli_fetch_assoc($result)['queueCount'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Dashboard - Clinic System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f2f5;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .nav {
            background: white;
            padding: 12px 30px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .nav a {
            color: #667eea;
            text-decoration: none;
            padding: 10px 20px;
            margin: 0 5px;
            display: inline-block;
            font-weight: 500;
            border-radius: 5px;
            transition: all 0.3s;
        }
        
        .nav a:hover, .nav a.active {
            background: #667eea;
            color: white;
        }
        
        .container {
            padding: 30px;
            max-width: 1400px;
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
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-number {
            font-size: 36px;
            font-weight: bold;
            color: #667eea;
            margin: 10px 0;
        }
        
        .stat-label {
            color: #7f8c8d;
            font-size: 14px;
            font-weight: 500;
        }
        
        .section {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 25px;
        }
        
        .section h3 {
            margin-bottom: 20px;
            color: #333;
            border-left: 4px solid #667eea;
            padding-left: 15px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }
        
        th {
            background: #f8f9fa;
            color: #555;
            font-weight: 600;
        }
        
        tr:hover {
            background: #f8f9fa;
        }
        
        .status {
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
            font-weight: bold;
            display: inline-block;
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
        
        .status-scheduled {
            background: #cce5ff;
            color: #004085;
        }
        
        .btn {
            padding: 6px 12px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 12px;
            border: none;
            cursor: pointer;
            display: inline-block;
        }
        
        .btn:hover {
            background: #5a67d8;
        }
        
        .btn-danger {
            background: #e74c3c;
        }
        
        .btn-danger:hover {
            background: #c0392b;
        }
        
        .logout-btn {
            background: rgba(255,255,255,0.2);
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            color: white;
        }
        
        .logout-btn:hover {
            background: rgba(255,255,255,0.3);
        }
        
        .queue-badge {
            background: #ff6b6b;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🏥 Clinic Management System</h1>
        <div>
            <span>Welcome, Dr. <?php echo $doctorName; ?></span>
            <a href="?action=logout" class="logout-btn">🚪 Logout</a>
        </div>
    </div>
    
    <div class="nav">
        <a href="doctor_dashboard.php" class="active">📊 Dashboard</a>
        <a href="doctor_appointments.php">📅 My Appointments</a>
        <a href="doctor_queue.php">👥 Patient Queue</a>
        <a href="doctor_profile.php">👤 My Profile</a>
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
        
        <!-- Today's Appointments -->
        <div class="section">
            <h3>📅 Today's Appointments (<?php echo date('F j, Y'); ?>)</h3>
            <?php if(mysqli_num_rows($todayAppointments) > 0): ?>
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
                                    <a href="doctor_update_appointment.php?id=<?php echo $appointment['apptId']; ?>" class="btn">Update</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="text-align: center; color: #999;">No appointments scheduled for today.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>