<?php
session_start();
include 'database.php';

// Check if user is logged in
if(!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['user_role'];
$user_name = $_SESSION['user_name'];

// =============================================
// PATIENT DASHBOARD
// =============================================
if($user_role == 'patient') {
    
    // Get patient's appointments - NO QUEUE JOIN (queue table has no link to appointments)
    $appointments_query = "SELECT a.apptId, a.apptDate, a.apptTime, a.status, 
                                  d.doctorName, d.specialisation
                           FROM appointment a
                           JOIN doctor d ON a.doctorId = d.doctorId
                           WHERE a.patientId = '$user_id'
                           ORDER BY a.apptDate DESC";
    $appointments_result = mysqli_query($conn, $appointments_query);
    
    // Get today's queue number - SEPARATE QUERY (queue table has patientId)
    $today = date('Y-m-d');
    $queue_query = "SELECT queueId, availability as queueStatus
                    FROM queue 
                    WHERE patientId = '$user_id'
                    LIMIT 1";
    $queue_result = mysqli_query($conn, $queue_query);
    $queue = mysqli_fetch_assoc($queue_result);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Patient Dashboard</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial; background: #f0f0f0; }
        .header { background: #2c3e50; color: white; padding: 15px 20px; display: flex; justify-content: space-between; }
        .header a { color: white; background: #e74c3c; padding: 8px 15px; text-decoration: none; border-radius: 5px; }
        .container { max-width: 1200px; margin: 20px auto; padding: 0 20px; }
        .welcome { background: linear-gradient(135deg, #3498db, #2c3e50); color: white; padding: 25px; border-radius: 10px; margin-bottom: 20px; }
        .rectangle { background: white; border-radius: 15px; padding: 25px; margin-bottom: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .rectangle h3 { color: #2c3e50; border-bottom: 2px solid #3498db; padding-bottom: 10px; margin-bottom: 15px; }
        .queue-box { background: #e67e22; color: white; padding: 20px; border-radius: 10px; text-align: center; }
        .queue-number { font-size: 48px; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #3498db; color: white; }
        tr:hover { background: #f5f5f5; }
        .status-pending { background: #ffeaa7; color: #d35400; padding: 3px 8px; border-radius: 5px; }
        .status-completed { background: #d4edda; color: #155724; padding: 3px 8px; border-radius: 5px; }
    </style>
</head>
<body>

<div class="header">
    <h2>🏥 Clinic System - Patient</h2>
    <a href="logout.php">Logout</a>
</div>

<div class="container">
    <div class="welcome">
        <h3>Welcome, <?php echo $user_name; ?>!</h3>
        <p>Patient ID: <?php echo $user_id; ?></p>
    </div>
    
    <!-- QUEUE NUMBER RECTANGLE -->
    <div class="rectangle">
        <h3>🚶 Queue Number</h3>
        <?php if($queue && $queue['queueId']): ?>
        <div class="queue-box">
            <div class="queue-number"><?php echo $queue['queueId']; ?></div>
            <div>Status: <?php echo $queue['queueStatus']; ?></div>
        </div>
        <?php else: ?>
        <p style="text-align:center; padding:20px;">No queue number assigned.</p>
        <?php endif; ?>
    </div>
    
    <!-- APPOINTMENT DETAILS RECTANGLE -->
    <div class="rectangle">
        <h3>📅 My Appointments</h3>
        <?php if(mysqli_num_rows($appointments_result) > 0): ?>
        <table>
            <thead>
                <tr><th>Date</th><th>Time</th><th>Doctor</th><th>Specialization</th><th>Status</th></tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($appointments_result)): ?>
                <tr>
                    <td><?php echo date('d/m/Y', strtotime($row['apptDate'])); ?></td>
                    <td><?php echo date('h:i A', strtotime($row['apptTime'])); ?></td>
                    <td><?php echo $row['doctorName']; ?></td>
                    <td><?php echo $row['specialisation']; ?></td>
                    <td>
                        <?php if($row['status'] == 'Pending'): ?>
                            <span class="status-pending">Pending</span>
                        <?php else: ?>
                            <span class="status-completed"><?php echo $row['status']; ?></span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
        <p style="text-align:center; padding:20px;">No appointments found.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>

<?php
}
// =============================================
// DOCTOR DASHBOARD
// =============================================
elseif($user_role == 'doctor') {
    
    // Get doctor's appointments with patient info (no queue join)
    $appointments_query = "SELECT a.apptId, a.apptDate, a.apptTime, a.status,
                                  p.patientId, p.patientName, p.gender, p.patientPhoneNo
                           FROM appointment a
                           JOIN patient p ON a.patientId = p.patientId
                           WHERE a.doctorId = '$user_id'
                           ORDER BY a.apptDate DESC LIMIT 5";
    $appointments_result = mysqli_query($conn, $appointments_query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Doctor Dashboard</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial; background: #f0f0f0; }
        .header { background: #2c3e50; color: white; padding: 15px 20px; display: flex; justify-content: space-between; }
        .header a { color: white; background: #e74c3c; padding: 8px 15px; text-decoration: none; border-radius: 5px; }
        .container { max-width: 1200px; margin: 20px auto; padding: 0 20px; }
        .welcome { background: linear-gradient(135deg, #27ae60, #2c3e50); color: white; padding: 25px; border-radius: 10px; margin-bottom: 20px; }
        .profile-box { background: white; border-radius: 15px; padding: 25px; margin-bottom: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .profile-box h3 { color: #2c3e50; border-bottom: 2px solid #27ae60; padding-bottom: 10px; margin-bottom: 15px; }
        .profile-details { display: flex; gap: 20px; flex-wrap: wrap; }
        .profile-item { background: #f8f9fa; padding: 10px 20px; border-radius: 8px; }
        .profile-label { font-size: 12px; color: #7f8c8d; }
        .profile-value { font-size: 16px; font-weight: bold; }
        .btn { background: #27ae60; color: white; padding: 12px 25px; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; display: inline-block; margin-top: 15px; }
        .btn-appointments { background: #667eea; color: white; padding: 12px 25px; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; display: inline-block; margin-top: 15px; margin-left: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #27ae60; color: white; }
        tr:hover { background: #f5f5f5; }
        .status-pending { background: #ffeaa7; color: #d35400; padding: 3px 8px; border-radius: 5px; }
        .status-completed { background: #d4edda; color: #155724; padding: 3px 8px; border-radius: 5px; }
    </style>
</head>
<body>

<div class="header">
    <h2>🏥 Clinic System - Doctor</h2>
    <a href="logout.php">Logout</a>
</div>

<div class="container">
    <div class="welcome">
        <h3>Welcome, Dr. <?php echo $user_name; ?>!</h3>
        <p>Doctor ID: <?php echo $user_id; ?></p>
    </div>
    
    <!-- DOCTOR PROFILE RECTANGLE -->
    <div class="profile-box">
        <h3>👨‍⚕️ My Profile</h3>
        <?php
        $doc_query = "SELECT * FROM doctor WHERE doctorId = '$user_id'";
        $doc_result = mysqli_query($conn, $doc_query);
        $doctor = mysqli_fetch_assoc($doc_result);
        ?>
        <div class="profile-details">
            <div class="profile-item"><div class="profile-label">Name</div><div class="profile-value"><?php echo $doctor['doctorName']; ?></div></div>
            <div class="profile-item"><div class="profile-label">Specialization</div><div class="profile-value"><?php echo $doctor['specialisation']; ?></div></div>
            <div class="profile-item"><div class="profile-label">Phone</div><div class="profile-value"><?php echo $doctor['doctorPhoneNo']; ?></div></div>
            <div class="profile-item"><div class="profile-label">Email</div><div class="profile-value"><?php echo $doctor['doctorEmail']; ?></div></div>
        </div>
        
        <!-- BUTTON LINK TO doctor_appointments.php -->
        <a href="doctor_appointments.php" class="btn">📋 View My Appointments</a>
        <a href="doctor_queue.php" class="btn-appointments">👥 View Patient Queue</a>
    </div>
    
    <!-- Recent Appointments Summary -->
    <div class="profile-box">
        <h3>📅 Recent Appointments</h3>
        <?php if(mysqli_num_rows($appointments_result) > 0): ?>
        <table>
            <thead>
                <tr><th>Date</th><th>Time</th><th>Patient Name</th><th>Status</th></tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($appointments_result)): ?>
                <tr>
                    <td><?php echo date('d/m/Y', strtotime($row['apptDate'])); ?></td>
                    <td><?php echo date('h:i A', strtotime($row['apptTime'])); ?></td>
                    <td><?php echo $row['patientName']; ?></td>
                    <td>
                        <?php if($row['status'] == 'Pending'): ?>
                            <span class="status-pending">Pending</span>
                        <?php else: ?>
                            <span class="status-completed"><?php echo $row['status']; ?></span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
        <p style="text-align:center; padding:20px;">No appointments found.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>

<?php
}
// =============================================
// RECEPTIONIST DASHBOARD
// =============================================
elseif($user_role == 'receptionist') {
?>

<!DOCTYPE html>
<html>
<head>
    <title>Receptionist Dashboard</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial; background: #f0f0f0; }
        .header { background: #2c3e50; color: white; padding: 15px 20px; display: flex; justify-content: space-between; }
        .header a { color: white; background: #e74c3c; padding: 8px 15px; text-decoration: none; border-radius: 5px; }
        .container { max-width: 1200px; margin: 20px auto; padding: 0 20px; }
        .welcome { background: linear-gradient(135deg, #e67e22, #2c3e50); color: white; padding: 25px; border-radius: 10px; margin-bottom: 20px; }
        .squares { display: flex; gap: 20px; flex-wrap: wrap; }
        .square { background: white; border-radius: 15px; padding: 30px; flex: 1; text-align: center; box-shadow: 0 2px 10px rgba(0,0,0,0.1); transition: transform 0.2s; min-width: 200px; }
        .square:hover { transform: translateY(-5px); }
        .square h3 { color: #2c3e50; margin-bottom: 15px; }
        .square p { color: #7f8c8d; margin-bottom: 20px; }
        .square-btn { background: #e67e22; color: white; padding: 12px 25px; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; display: inline-block; }
        .icon { font-size: 48px; margin-bottom: 15px; }
    </style>
</head>
<body>

<div class="header">
    <h2>🏥 Clinic System - Receptionist</h2>
    <a href="logout.php">Logout</a>
</div>

<div class="container">
    <div class="welcome">
        <h3>Welcome, <?php echo $user_name; ?>!</h3>
        <p>Receptionist ID: <?php echo $user_id; ?></p>
    </div>
    
    <div class="squares">
        <div class="square">
            <div class="icon">👥</div>
            <h3>Patients' Lists</h3>
            <p>View and manage patients</p>
            <a href="editPatient(RECEPTIONIST).php" class="square-btn">View Patients →</a>
        </div>
        
        <div class="square">
            <div class="icon">👥</div>
            <h3>Doctors' Lists</h3>
            <p>View and manage doctors</p>
            <a href="editDoctor(RECEPTIONIST).php" class="square-btn">View Doctors →</a>
        </div>
        
        <div class="square">
            <div class="icon">🚶</div>
            <h3>Queue Management</h3>
            <p>Manage queue numbers and status</p>
            <a href="editQueue(RECEPTIONIST).php" class="square-btn">Manage Queue →</a>
        </div>
        
        <div class="square">
            <div class="icon">📅</div>
            <h3>Appointments</h3>
            <p>View and manage all appointments</p>
            <a href="editAppointments(RECEPTIONIST).php" class="square-btn">View Appointments →</a>
        </div>
    </div>
</div>

</body>
</html>

<?php
}
?>