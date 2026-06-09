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

// Get filter from URL
$status_filter = isset($_GET['status']) ? $_GET['status'] : 'all';
$date_filter = isset($_GET['date']) ? $_GET['date'] : '';

// Build query to get appointments
$query = "SELECT a.*, p.patientName, p.patientIc, p.patientPhoneNo, p.patientEmail 
          FROM appointment a 
          JOIN patient p ON a.patientId = p.patientId 
          WHERE a.doctorId = '$doctorId'";

// Apply status filter
if($status_filter != 'all') {
    $query .= " AND a.status = '$status_filter'";
}

// Apply date filter
if($date_filter != '') {
    $query .= " AND a.apptDate = '$date_filter'";
}

// Order by date and time
$query .= " ORDER BY a.apptDate DESC, a.apptTime ASC";
$appointments = mysqli_query($conn, $query);

// Get statistics for summary
$total_query = "SELECT COUNT(*) as total FROM appointment WHERE doctorId = '$doctorId'";
$total_result = mysqli_query($conn, $total_query);
$total_appointments = mysqli_fetch_assoc($total_result)['total'];

$pending_query = "SELECT COUNT(*) as pending FROM appointment WHERE doctorId = '$doctorId' AND status = 'Pending'";
$pending_result = mysqli_query($conn, $pending_query);
$pending_appointments = mysqli_fetch_assoc($pending_result)['pending'];

$completed_query = "SELECT COUNT(*) as completed FROM appointment WHERE doctorId = '$doctorId' AND status = 'Completed'";
$completed_result = mysqli_query($conn, $completed_query);
$completed_appointments = mysqli_fetch_assoc($completed_result)['completed'];

$today = date('Y-m-d');
$today_query = "SELECT COUNT(*) as today FROM appointment WHERE doctorId = '$doctorId' AND apptDate = '$today'";
$today_result = mysqli_query($conn, $today_query);
$today_appointments = mysqli_fetch_assoc($today_result)['today'];
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
        
        /* Stats Summary */
        .stats-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }
        
        .stat-box {
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .stat-box .number {
            font-size: 32px;
            font-weight: bold;
            color: #667eea;
        }
        
        .stat-box .label {
            color: #7f8c8d;
            margin-top: 5px;
            font-size: 14px;
        }
        
        /* Filter Section */
        .filter-section {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .filter-title {
            font-weight: bold;
            margin-bottom: 15px;
            color: #333;
        }
        
        .filter-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 15px;
        }
        
        .filter-btn {
            padding: 8px 20px;
            background: #ecf0f1;
            color: #333;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
            transition: all 0.3s;
        }
        
        .filter-btn:hover {
            background: #667eea;
            color: white;
        }
        
        .filter-btn.active {
            background: #667eea;
            color: white;
        }
        
        .date-filter {
            display: flex;
            gap: 10px;
            align-items: center;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #e0e0e0;
        }
        
        .date-input {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        
        .btn {
            padding: 8px 15px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }
        
        .btn:hover {
            background: #5a67d8;
        }
        
        .btn-secondary {
            background: #95a5a6;
        }
        
        .btn-secondary:hover {
            background: #7f8c8d;
        }
        
        /* Appointments Table */
        .appointments-table {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow-x: auto;
        }
        
        .appointments-table h3 {
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
            padding: 5px 12px;
            border-radius: 20px;
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
        
        .status-confirmed {
            background: #d1ecf1;
            color: #0c5460;
        }
        
        .action-buttons {
            display: flex;
            gap: 8px;
        }
        
        .btn-small {
            padding: 5px 12px;
            font-size: 12px;
        }
        
        .empty-state {
            text-align: center;
            padding: 40px;
            color: #999;
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
    </style>
</head>
<body>
    <div class="header">
        <h1>🏥 Clinic Management System</h1>
        <div>
            <span>Welcome, Dr. <?php echo $doctorName; ?></span>
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
        <!-- Quick Stats -->
        <div class="stats-summary">
            <div class="stat-box">
                <div class="number"><?php echo $total_appointments; ?></div>
                <div class="label">Total Appointments</div>
            </div>
            <div class="stat-box">
                <div class="number"><?php echo $pending_appointments; ?></div>
                <div class="label">Pending</div>
            </div>
            <div class="stat-box">
                <div class="number"><?php echo $completed_appointments; ?></div>
                <div class="label">Completed</div>
            </div>
            <div class="stat-box">
                <div class="number"><?php echo $today_appointments; ?></div>
                <div class="label">Today's Appointments</div>
            </div>
        </div>
        
        <!-- Filter Section -->
        <div class="filter-section">
            <div class="filter-title">🔍 Filter Appointments</div>
            <div class="filter-buttons">
                <a href="?status=all" class="filter-btn <?php echo ($status_filter == 'all') ? 'active' : ''; ?>">All</a>
                <a href="?status=Scheduled" class="filter-btn <?php echo ($status_filter == 'Scheduled') ? 'active' : ''; ?>">Scheduled</a>
                <a href="?status=Pending" class="filter-btn <?php echo ($status_filter == 'Pending') ? 'active' : ''; ?>">Pending</a>
                <a href="?status=Confirmed" class="filter-btn <?php echo ($status_filter == 'Confirmed') ? 'active' : ''; ?>">Confirmed</a>
                <a href="?status=Completed" class="filter-btn <?php echo ($status_filter == 'Completed') ? 'active' : ''; ?>">Completed</a>
                <a href="?status=Cancelled" class="filter-btn <?php echo ($status_filter == 'Cancelled') ? 'active' : ''; ?>">Cancelled</a>
            </div>
            
            <div class="date-filter">
                <form method="GET" style="display: flex; gap: 10px; align-items: center;">
                    <?php if($status_filter != 'all'): ?>
                        <input type="hidden" name="status" value="<?php echo $status_filter; ?>">
                    <?php endif; ?>
                    <input type="date" name="date" class="date-input" value="<?php echo $date_filter; ?>">
                    <button type="submit" class="btn">Filter by Date</button>
                    <?php if($date_filter != ''): ?>
                        <a href="?status=<?php echo $status_filter; ?>" class="btn btn-secondary">Clear Date</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>
        
        <!-- Appointments List -->
        <div class="appointments-table">
            <h3>📋 Appointment List</h3>
            
            <?php if(isset($appointments) && mysqli_num_rows($appointments) > 0): ?>
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
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody><?php
session_start();
include 'database.php';

// Check if doctor is logged in
if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'doctor') {
    header("Location: login.php");
    exit();
}

$doctorId = $_SESSION['user_id'];
$doctorName = $_SESSION['user_name'];
$message = "";
$error = "";

// Get filter from GET method
$status_filter = isset($_GET['status']) ? $_GET['status'] : 'all';
$date_filter = isset($_GET['date']) ? $_GET['date'] : '';

// Build the query with JOIN to get patient information
$query = "SELECT a.*, p.patientName, p.patientIc, p.patientPhoneNo, p.patientEmail 
          FROM appointment a 
          JOIN patient p ON a.patientId = p.patientId 
          WHERE a.doctorId = '$doctorId'";

// Apply status filter using conditional statement
if($status_filter != 'all') {
    $query .= " AND a.status = '$status_filter'";
}

// Apply date filter
if($date_filter != '') {
    $query .= " AND a.apptDate = '$date_filter'";
}

// Order by date and time
$query .= " ORDER BY a.apptDate DESC, a.apptTime ASC";

$appointments = mysqli_query($conn, $query);

// Check if query was successful
if(!$appointments) {
    $error = "Error fetching appointments: " . mysqli_error($conn);
}

// Get statistics for the header
$stats = [];

// Total appointments
$result = mysqli_query($conn, "SELECT COUNT(*) as total FROM appointment WHERE doctorId = '$doctorId'");
$stats['total'] = mysqli_fetch_assoc($result)['total'];

// Pending appointments
$result = mysqli_query($conn, "SELECT COUNT(*) as pending FROM appointment WHERE doctorId = '$doctorId' AND status = 'Pending'");
$stats['pending'] = mysqli_fetch_assoc($result)['pending'];

// Completed appointments
$result = mysqli_query($conn, "SELECT COUNT(*) as completed FROM appointment WHERE doctorId = '$doctorId' AND status = 'Completed'");
$stats['completed'] = mysqli_fetch_assoc($result)['completed'];

// Today's appointments
$today = date('Y-m-d');
$result = mysqli_query($conn, "SELECT COUNT(*) as today FROM appointment WHERE doctorId = '$doctorId' AND apptDate = '$today'");
$stats['today'] = mysqli_fetch_assoc($result)['today'];
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
        
        /* Stats Summary */
        .stats-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }
        
        .stat-box {
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .stat-box .number {
            font-size: 32px;
            font-weight: bold;
            color: #667eea;
        }
        
        .stat-box .label {
            color: #7f8c8d;
            margin-top: 5px;
        }
        
        /* Filter Section */
        .filter-section {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .filter-title {
            font-weight: bold;
            margin-bottom: 15px;
            color: #333;
        }
        
        .filter-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 15px;
        }
        
        .filter-btn {
            padding: 8px 20px;
            background: #ecf0f1;
            color: #333;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
            transition: all 0.3s;
        }
        
        .filter-btn:hover {
            background: #667eea;
            color: white;
        }
        
        .filter-btn.active {
            background: #667eea;
            color: white;
        }
        
        .date-filter {
            display: flex;
            gap: 10px;
            align-items: center;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #e0e0e0;
        }
        
        .date-input {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        
        .btn {
            padding: 8px 15px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }
        
        .btn:hover {
            background: #5a67d8;
        }
        
        .btn-secondary {
            background: #95a5a6;
        }
        
        .btn-secondary:hover {
            background: #7f8c8d;
        }
        
        /* Appointments Table */
        .appointments-table {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow-x: auto;
        }
        
        .appointments-table h3 {
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
            padding: 5px 12px;
            border-radius: 20px;
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
        
        .status-confirmed {
            background: #d1ecf1;
            color: #0c5460;
        }
        
        .empty-state {
            text-align: center;
            padding: 40px;
            color: #999;
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
        
        .message {
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .message.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .message.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🏥 Clinic Management System</h1>
        <div>
            <span>Welcome, Dr. <?php echo $doctorName; ?></span>
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
        <!-- Quick Stats -->
        <div class="stats-summary">
            <div class="stat-box">
                <div class="number"><?php echo $stats['total']; ?></div>
                <div class="label">Total Appointments</div>
            </div>
            <div class="stat-box">
                <div class="number"><?php echo $stats['pending']; ?></div>
                <div class="label">Pending</div>
            </div>
            <div class="stat-box">
                <div class="number"><?php echo $stats['completed']; ?></div>
                <div class="label">Completed</div>
            </div>
            <div class="stat-box">
                <div class="number"><?php echo $stats['today']; ?></div>
                <div class="label">Today's Appointments</div>
            </div>
        </div>
        
        <!-- Filter Section -->
        <div class="filter-section">
            <div class="filter-title">🔍 Filter Appointments</div>
            <div class="filter-buttons">
                <a href="?status=all" class="filter-btn <?php echo ($status_filter == 'all') ? 'active' : ''; ?>">All</a>
                <a href="?status=Scheduled" class="filter-btn <?php echo ($status_filter == 'Scheduled') ? 'active' : ''; ?>">Scheduled</a>
                <a href="?status=Pending" class="filter-btn <?php echo ($status_filter == 'Pending') ? 'active' : ''; ?>">Pending</a>
                <a href="?status=Confirmed" class="filter-btn <?php echo ($status_filter == 'Confirmed') ? 'active' : ''; ?>">Confirmed</a>
                <a href="?status=Completed" class="filter-btn <?php echo ($status_filter == 'Completed') ? 'active' : ''; ?>">Completed</a>
                <a href="?status=Cancelled" class="filter-btn <?php echo ($status_filter == 'Cancelled') ? 'active' : ''; ?>">Cancelled</a>
            </div>
            
            <div class="date-filter">
                <form method="GET" style="display: flex; gap: 10px; align-items: center;">
                    <?php if($status_filter != 'all'): ?>
                        <input type="hidden" name="status" value="<?php echo $status_filter; ?>">
                    <?php endif; ?>
                    <input type="date" name="date" class="date-input" value="<?php echo $date_filter; ?>" placeholder="Select date">
                    <button type="submit" class="btn">Filter by Date</button>
                    <?php if($date_filter != ''): ?>
                        <a href="?status=<?php echo $status_filter; ?>" class="btn btn-secondary">Clear Date</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>
        
        <!-- Appointments List (VIEW ONLY - NO UPDATE BUTTON) -->
        <div class="appointments-table">
            <h3>📋 Appointment List</h3>
            
            <?php if($error): ?>
                <div class="message error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if(isset($appointments) && mysqli_num_rows($appointments) > 0): ?>
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
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($appointment = mysqli_fetch_assoc($appointments)): ?>
                            <tr>
                                <td><?php echo date('d/m/Y', strtotime($appointment['apptDate'])); ?></td>
                                <td><?php echo date('h:i A', strtotime($appointment['apptTime'])); ?></td>
                                <td><?php echo htmlspecialchars($appointment['patientName']); ?></td>
                                <td><?php echo htmlspecialchars($appointment['patientIc']); ?></td>
                                <td><?php echo htmlspecialchars($appointment['patientPhoneNo']); ?></td>
                                <td><?php echo htmlspecialchars($appointment['patientEmail']); ?></td>
                                <td>
                                    <span class="status status-<?php echo strtolower($appointment['status']); ?>">
                                        <?php echo $appointment['status']; ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state">
                    <p>📭 No appointments found.</p>
                    <p style="font-size: 14px; margin-top: 10px;">Try changing the filter or check back later.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
                        <?php while($appointment = mysqli_fetch_assoc($appointments)): ?>
                            <tr>
                                <td><?php echo date('d/m/Y', strtotime($appointment['apptDate'])); ?></td>
                                <td><?php echo date('h:i A', strtotime($appointment['apptTime'])); ?></td>
                                <td><?php echo htmlspecialchars($appointment['patientName']); ?></td>
                                <td><?php echo htmlspecialchars($appointment['patientIc']); ?></td>
                                <td><?php echo htmlspecialchars($appointment['patientPhoneNo']); ?></td>
                                <td><?php echo htmlspecialchars($appointment['patientEmail']); ?></td>
                                <td>
                                    <span class="status status-<?php echo strtolower($appointment['status']); ?>">
                                        <?php echo $appointment['status']; ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="doctor_update_appointment.php?id=<?php echo $appointment['apptId']; ?>" class="btn btn-small">Update</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state">
                    <p>📭 No appointments found.</p>
                    <p style="font-size: 14px; margin-top: 10px;">Try changing the filter or check back later.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>