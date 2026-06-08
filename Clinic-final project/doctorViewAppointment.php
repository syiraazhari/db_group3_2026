<?php
session_start();
include 'database.php';

// Check if doctor is logged in
if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'doctor') {
    header("Location: login.php");
    exit();
}

$doctorId = $_SESSION['user_id'];
$message = "";
$error = "";

// Get filter
$status_filter = isset($_GET['status']) ? $_GET['status'] : 'all';
$date_filter = isset($_GET['date']) ? $_GET['date'] : '';

// Build query
$query = "SELECT a.*, p.patientName, p.patientIc, p.patientPhoneNo, p.patientEmail, 
          q.queueNumber, q.queueStatus
          FROM appointment a 
          JOIN patient p ON a.patientId = p.patientId 
          LEFT JOIN queue q ON a.apptId = q.appointmentId
          WHERE a.doctorId = '$doctorId'";

if($status_filter != 'all') {
    $query .= " AND a.status = '$status_filter'";
}

if($date_filter != '') {
    $query .= " AND a.apptDate = '$date_filter'";
}

$query .= " ORDER BY a.apptDate DESC, a.apptTime ASC";
$appointments = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Appointments - Doctor Panel</title>
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
        
        .filter-section {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .filter-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 15px;
        }
        
        .filter-btn {
            padding: 8px 15px;
            background: #ecf0f1;
            color: #333;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
        }
        
        .filter-btn.active {
            background: #667eea;
            color: white;
        }
        
        .appointments-table {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow-x: auto;
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
        
        .queue-status {
            background: #e8f4f8;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
            display: inline-block;
        }
        
        .btn {
            padding: 6px 12px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 12px;
            display: inline-block;
        }
        
        .btn:hover {
            background: #5a67d8;
        }
        
        .logout-btn {
            background: rgba(255,255,255,0.2);
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            color: white;
        }
        
        .date-input {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🏥 Clinic Management System</h1>
        <div>
            <span>Welcome, Dr. <?php echo $_SESSION['user_name']; ?></span>
            <a href="login.php?action=logout" class="logout-btn">🚪 Logout</a>
        </div>
    </div>
    
    <div class="nav">
        <a href="doctor_dashboard.php">📊 Dashboard</a>
        <a href="doctor_appointments.php" class="active">📅 My Appointments</a>
        <a href="doctor_queue.php">👥 Patient Queue</a>
        <a href="doctor_profile.php">👤 My Profile</a>
    </div>
    
    <div class="container">
        <div class="filter-section">
            <h3>Filter Appointments</h3>
            <div class="filter-buttons">
                <a href="?status=all" class="filter-btn <?php echo ($status_filter == 'all') ? 'active' : ''; ?>">All</a>
                <a href="?status=Scheduled" class="filter-btn <?php echo ($status_filter == 'Scheduled') ? 'active' : ''; ?>">Scheduled</a>
                <a href="?status=Pending" class="filter-btn <?php echo ($status_filter == 'Pending') ? 'active' : ''; ?>">Pending</a>
                <a href="?status=Completed" class="filter-btn <?php echo ($status_filter == 'Completed') ? 'active' : ''; ?>">Completed</a>
                <a href="?status=Cancelled" class="filter-btn <?php echo ($status_filter == 'Cancelled') ? 'active' : ''; ?>">Cancelled</a>
            </div>
            <div style="margin-top: 15px;">
                <form method="GET" style="display: inline;">
                    <input type="date" name="date" class="date-input" value="<?php echo $date_filter; ?>">
                    <button type="submit" class="btn">Filter by Date</button>
                    <a href="doctor_appointments.php" class="btn">Reset</a>
                </form>
            </div>
        </div>
        
        <div class="appointments-table">
            <h3>📋 Appointment List</h3>
            <?php if(mysqli_num_rows($appointments) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Patient Name</th>
                            <th>IC Number</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Queue #</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($appointment = mysqli_fetch_assoc($appointments)): ?>
                            <tr>
                                <td><?php echo date('d/m/Y', strtotime($appointment['apptDate'])); ?></td>
                                <td><?php echo date('h:i A', strtotime($appointment['apptTime'])); ?></td>
                                <td><?php echo $appointment['patientName']; ?></td>
                                <td><?php echo $appointment['patientIc']; ?></td>
                                <td><?php echo $appointment['patientPhoneNo']; ?></td>
                                <td><?php echo $appointment['patientEmail']; ?></td>
                                <td>
                                    <span class="status status-<?php echo strtolower($appointment['status']); ?>">
                                        <?php echo $appointment['status']; ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if($appointment['queueNumber']): ?>
                                        <span class="queue-status">Queue: <?php echo $appointment['queueNumber']; ?></span>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="doctor_update_appointment.php?id=<?php echo $appointment['apptId']; ?>" class="btn">Update</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="text-align: center; color: #999;">No appointments found.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>