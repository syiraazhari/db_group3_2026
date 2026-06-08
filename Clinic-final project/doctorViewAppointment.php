<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

requireDoctorLogin();

$doctorId = $_SESSION['doctor_id'];

// Get filter parameter - GET method
$status = isset($_GET['status']) ? $_GET['status'] : 'all';

// Loop through different status options
$statusOptions = ['all', 'Pending', 'Completed', 'Cancelled'];
$appointments = getDoctorAppointments($conn, $doctorId, $status);
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
        
        .nav a:hover, .nav a.active {
            background: #2c3e50;
            border-radius: 5px;
        }
        
        .container {
            padding: 30px;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .filter-bar {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .filter-bar a {
            padding: 8px 15px;
            margin: 0 5px;
            text-decoration: none;
            background: #ecf0f1;
            color: #2c3e50;
            border-radius: 5px;
            display: inline-block;
        }
        
        .filter-bar a.active {
            background: #3498db;
            color: white;
        }
        
        .appointments-table {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
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
            border: none;
            cursor: pointer;
        }
        
        .btn:hover {
            background: #2980b9;
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
        <div>
            Welcome, Dr. <?php echo $_SESSION['doctor_name']; ?> | 
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </div>
    
    <div class="nav">
        <a href="dashboard.php">Dashboard</a>
        <a href="view_appointments.php" class="active">My Appointments</a>
        <a href="view_queue.php">Patient Queue</a>
        <a href="profile.php">My Profile</a>
    </div>
    
    <div class="container">
        <div class="filter-bar">
            <strong>Filter by Status:</strong>
            <?php foreach($statusOptions as $option): ?>
                <a href="?status=<?php echo $option; ?>" class="<?php echo ($status == $option) ? 'active' : ''; ?>">
                    <?php echo ucfirst($option); ?>
                </a>
            <?php endforeach; ?>
        </div>
        
        <div class="appointments-table">
            <h3>My Appointments</h3>
            
            <?php if (mysqli_num_rows($appointments) > 0): ?>
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
                                    <a href="update_appointment.php?id=<?php echo $appointment['apptId']; ?>" class="btn">
                                        Update Status
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No appointments found.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>