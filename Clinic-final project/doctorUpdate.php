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

// Get appointment ID
if(!isset($_GET['id'])) {
    header("Location: doctor_appointments.php");
    exit();
}

$appointmentId = $_GET['id'];

// Fetch appointment details
$query = "SELECT a.*, p.patientName, p.patientIc, p.patientPhoneNo, p.patientEmail,
          q.queueNumber, q.queueStatus
          FROM appointment a 
          JOIN patient p ON a.patientId = p.patientId 
          LEFT JOIN queue q ON a.apptId = q.appointmentId
          WHERE a.apptId = '$appointmentId' AND a.doctorId = '$doctorId'";
$result = mysqli_query($conn, $query);

if(mysqli_num_rows($result) == 0) {
    header("Location: doctor_appointments.php");
    exit();
}

$appointment = mysqli_fetch_assoc($result);

// Update appointment status
if(isset($_POST['update_status'])) {
    $new_status = $_POST['status'];
    $consultation_notes = mysqli_real_escape_string($conn, $_POST['consultation_notes']);
    
    $update_query = "UPDATE appointment SET status = '$new_status', consultationNotes = '$consultation_notes' WHERE apptId = '$appointmentId'";
    
    if(mysqli_query($conn, $update_query)) {
        $message = "Appointment status updated successfully!";
        
        // If completed, update queue status
        if($new_status == 'Completed') {
            $update_queue = "UPDATE queue SET queueStatus = 'Completed' WHERE appointmentId = '$appointmentId'";
            mysqli_query($conn, $update_queue);
        }
        
        // Refresh data
        $result = mysqli_query($conn, $query);
        $appointment = mysqli_fetch_assoc($result);
    } else {
        $error = "Error updating appointment: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Appointment - Doctor Panel</title>
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
        
        .nav a:hover {
            background: #667eea;
            color: white;
        }
        
        .container {
            padding: 30px;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .patient-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 25px;
        }
        
        .patient-info h3 {
            margin-bottom: 15px;
            color: #667eea;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
        
        .info-item {
            padding: 5px 0;
        }
        
        .info-label {
            font-weight: bold;
            color: #555;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }
        
        select, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        
        textarea {
            resize: vertical;
            min-height: 120px;
        }
        
        button {
            background: #667eea;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }
        
        button:hover {
            background: #5a67d8;
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
        
        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #667eea;
            text-decoration: none;
        }
        
        .logout-btn {
            background: rgba(255,255,255,0.2);
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            color: white;
        }
        
        .status-badge {
            display: inline-block;
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
        <a href="doctor_appointments.php">📅 My Appointments</a>
        <a href="doctor_queue.php">👥 Patient Queue</a>
        <a href="doctor_profile.php">👤 My Profile</a>
    </div>
    
    <div class="container">
        <div class="card">
            <h2>Update Appointment</h2>
            
            <?php if($message): ?>
                <div class="message success"><?php echo $message; ?></div>
            <?php endif; ?>
            
            <?php if($error): ?>
                <div class="message error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <div class="patient-info">
                <h3>Patient Information</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Name:</span> <?php echo $appointment['patientName']; ?>
                    </div>
                    <div class="info-item">
                        <span class="info-label">IC Number:</span> <?php echo $appointment['patientIc']; ?>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Phone:</span> <?php echo $appointment['patientPhoneNo']; ?>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Email:</span> <?php echo $appointment['patientEmail']; ?>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Date:</span> <?php echo date('d/m/Y', strtotime($appointment['apptDate'])); ?>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Time:</span> <?php echo date('h:i A', strtotime($appointment['apptTime'])); ?>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Queue Number:</span> <?php echo $appointment['queueNumber'] ? $appointment['queueNumber'] : 'Not assigned'; ?>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Current Status:</span>
                        <span class="status-badge status-<?php echo strtolower($appointment['status']); ?>">
                            <?php echo $appointment['status']; ?>
                        </span>
                    </div>
                </div>
            </div>
            
            <form method="POST">
                <div class="form-group">
                    <label>Appointment Status</label>
                    <select name="status" required>
                        <option value="Scheduled" <?php echo ($appointment['status'] == 'Scheduled') ? 'selected' : ''; ?>>Scheduled</option>
                        <option value="Pending" <?php echo ($appointment['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                        <option value="Completed" <?php echo ($appointment['status'] == 'Completed') ? 'selected' : ''; ?>>Completed</option>
                        <option value="Cancelled" <?php echo ($appointment['status'] == 'Cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Consultation Notes</label>
                    <textarea name="consultation_notes" placeholder="Enter diagnosis, prescription, follow-up instructions..."><?php echo isset($appointment['consultationNotes']) ? $appointment['consultationNotes'] : ''; ?></textarea>
                </div>
                
                <button type="submit" name="update_status">Update Appointment</button>
            </form>
            
            <a href="doctor_appointments.php" class="back-link">← Back to Appointments</a>
        </div>
    </div>
</body>
</html>