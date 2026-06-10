<?php
session_start();
include 'database.php';

if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'doctor') {
    header("Location: login.php");
    exit();
}

$doctorId = $_SESSION['user_id'];
$doctorName = $_SESSION['user_name'];

$query = "SELECT a.*, p.patientName, p.patientIc, p.patientPhoneNo, p.patientEmail 
          FROM appointment a 
          JOIN patient p ON a.patientId = p.patientId 
          WHERE a.doctorId = '$doctorId'
          ORDER BY a.apptDate DESC, a.apptTime ASC";

$appointments = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Appointments</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial; background: #f0f2f5; }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .nav {
            background: white;
            padding: 10px 25px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .nav a {
            color: #667eea;
            text-decoration: none;
            padding: 8px 15px;
            margin: 0 5px;
            display: inline-block;
            border-radius: 5px;
        }
        
        .nav a:hover, .nav a.active {
            background: #667eea;
            color: white;
        }
        
        .container {
            padding: 20px;
            max-width: 1300px;
            margin: 0 auto;
        }
        
        table {
            width: 100%;
            background: white;
            border-collapse: collapse;
            border-radius: 8px;
            overflow: hidden;
        }
        
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        th {
            background: #667eea;
            color: white;
        }
        
        tr:hover {
            background: #f5f5f5;
        }
        
        .status {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            display: inline-block;
        }
        
        .status-pending { background: #fff3cd; color: #856404; }
        .status-completed { background: #d4edda; color: #155724; }
        .status-cancelled { background: #f8d7da; color: #721c24; }
        
        .empty-state {
            text-align: center;
            padding: 40px;
            background: white;
            border-radius: 8px;
            color: #999;
        }
        
        .logout-btn {
            background: rgba(255,255,255,0.2);
            padding: 6px 12px;
            border-radius: 5px;
            text-decoration: none;
            color: white;
        }
    </style>
</head>
<body>

<div class="header">
    <h2>🏥 Clinic System</h2>
    <div>
        <span>Dr. <?php echo $doctorName; ?></span>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
</div>

<div class="nav">
    <a href="dashboard.php">Dashboard</a>
    <a href="doctor_appointments.php" class="active">My Appointments</a>
    <a href="doctor_queue.php">Patient Queue</a>
</div>

<div class="container">
    
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
                <td><?php echo $appointment['patientName']; ?></td>
                <td><?php echo $appointment['patientIc']; ?></td>
                <td><?php echo $appointment['patientPhoneNo']; ?></td>
                <td><?php echo $appointment['patientEmail']; ?></td>
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
            <p>No appointments found.</p>
        </div>
    <?php endif; ?>
    
</div>

</body>
</html>