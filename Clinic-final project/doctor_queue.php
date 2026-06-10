<?php
session_start();
include 'database.php';

if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'doctor') {
    header("Location: login.php");
    exit();
}

$doctorId = $_SESSION['user_id'];
$doctorName = $_SESSION['user_name'];

$query = "SELECT q.*, p.patientName, p.patientIc, p.patientPhoneNo
          FROM queue q 
          JOIN patient p ON q.patientId = p.patientId 
          WHERE q.doctorId = '$doctorId'
          ORDER BY q.queueId ASC";
$result = mysqli_query($conn, $query);

if(isset($_POST['update_queue_status'])) {
    $queue_id = $_POST['queue_id'];
    $queue_status = $_POST['queue_status'];
    
    $update_query = "UPDATE queue SET availability = '$queue_status' WHERE queueId = '$queue_id'";
    mysqli_query($conn, $update_query);
    header("Location: doctor_queue.php");
    exit();
}

if(isset($_GET['call_next'])) {
    $queue_id = $_GET['call_next'];
    $update_query = "UPDATE queue SET availability = 'In Consultation' WHERE queueId = '$queue_id'";
    mysqli_query($conn, $update_query);
    header("Location: doctor_queue.php");
    exit();
}

$count_query = "SELECT COUNT(*) as count FROM queue WHERE doctorId = '$doctorId' AND availability = 'Waiting'";
$count_result = mysqli_query($conn, $count_query);
$waiting = mysqli_fetch_assoc($count_result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Patient Queue</title>
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
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .queue-stats {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .queue-stats h2 {
            font-size: 48px;
            margin: 10px 0;
        }
        
        .queue-container {
            background: white;
            border-radius: 10px;
            padding: 20px;
            overflow-x: auto;
        }
        
        .queue-container h3 {
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
            display: inline-block;
        }
        
        .status-waiting { background: #fff3cd; color: #856404; }
        .status-consultation { background: #cce5ff; color: #004085; }
        .status-completed { background: #d4edda; color: #155724; }
        
        .btn {
            padding: 6px 12px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 12px;
            display: inline-block;
            border: none;
            cursor: pointer;
        }
        
        .btn-primary { background: #667eea; color: white; }
        .btn-success { background: #27ae60; color: white; }
        
        .badge {
            background: #667eea;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
        }
        
        .empty-state {
            text-align: center;
            padding: 40px;
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
    <a href="doctor_appointments.php">My Appointments</a>
    <a href="doctor_queue.php" class="active">Patient Queue</a>
</div>

<div class="container">
    
    <div class="queue-stats">
        <p>Current Queue Status</p>
        <h2><?php echo $waiting['count']; ?></h2>
        <p>Patients Waiting</p>
    </div>
    
    <div class="queue-container">
        <h3>Patient Queue List</h3>
        
        <?php if(isset($result) && mysqli_num_rows($result) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Queue #</th>
                    <th>Patient Name</th>
                    <th>IC Number</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><span class="badge"><?php echo $row['queueId']; ?></span></td>
                    <td><?php echo $row['patientName']; ?></td>
                    <td><?php echo $row['patientIc']; ?></td>
                    <td><?php echo $row['patientPhoneNo']; ?></td>
                    <td>
                        <?php if($row['availability'] == 'Waiting'): ?>
                            <span class="status status-waiting">Waiting</span>
                        <?php elseif($row['availability'] == 'In Consultation'): ?>
                            <span class="status status-consultation">In Consultation</span>
                        <?php else: ?>
                            <span class="status status-completed">Completed</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($row['availability'] == 'Waiting'): ?>
                            <a href="?call_next=<?php echo $row['queueId']; ?>" class="btn btn-success" onclick="return confirm('Call this patient?')">Call Patient</a>
                        <?php elseif($row['availability'] == 'In Consultation'): ?>
                            <form method="POST" style="display: inline-block;">
                                <input type="hidden" name="queue_id" value="<?php echo $row['queueId']; ?>">
                                <input type="hidden" name="queue_status" value="Completed">
                                <button type="submit" name="update_queue_status" class="btn btn-primary" onclick="return confirm('Mark as completed?')">Complete</button>
                            </form>
                        <?php else: ?>
                            <span>✔️ Done</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
            <div class="empty-state">
                <p>No patients in queue.</p>
            </div>
        <?php endif; ?>
    </div>
    
</div>

</body>
</html>