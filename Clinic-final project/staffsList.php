<?php
session_start();
include 'database.php';

if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'staff') {
    header("Location:login.php");
    exit();
}

$doctors_sql = "SELECT doctor_id, doctor_name, specialization, email, phone, 'Doctor' as type FROM doctors";
$doctors_result = mysqli_query($conn, $doctors_sql);

$staff_sql = "SELECT receptionist_id, receptionist_name, '' as specialization, email, phone, 'Receptionist' as type FROM receptionist";
$staff_result = mysqli_query($conn, $staff_sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Staff List - Clinic System</title>
    <style>
        body {
            font-family: Arial;
            background: #f0f0f0;
            margin: 0;
            padding: 20px;
        }
        .header {
            background: #2c3e50;
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .header a {
            color: white;
            text-decoration: none;
            background: #e74c3c;
            padding: 8px 15px;
            border-radius: 5px;
        }
        .container {
            max-width: 1200px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
        }
        h2 {
            text-align: center;
            color: #2c3e50;
        }
        table {
            width: 100%;
            margin: 20px auto;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }
        th {
            background-color: #e67e22;
            color: white;
        }
        /* ODD ROW */
        tr:nth-child(odd) {
            background-color: #F1F1F1;
        }
        /* EVEN ROW */
        tr:nth-child(even) {
            background-color: #D3EEEE;
        }
        .btn-back {
            background-color: #95a5a6;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
        }
        .btn-edit {
            background-color: #f39c12;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        .btn-delete {
            background-color: #e74c3c;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        .doctor-row {
            border-left: 3px solid #27ae60;
        }
        .staff-row {
            border-left: 3px solid #3498db;
        }
        .badge-doctor {
            background-color: #27ae60;
            color: white;
            padding: 3px 8px;
            border-radius: 5px;
            font-size: 12px;
        }
        .badge-staff {
            background-color: #3498db;
            color: white;
            padding: 3px 8px;
            border-radius: 5px;
            font-size: 12px;
        }
    </style>
</head>
<body>

<div class="header">
    <h2>Clinic System - Staff Panel</h2>
    <a href="dashboard.php">Back to Dashboard</a>
</div>

<div class="container">
    <h2>Staff Directory</h2>
    
    <div style="margin-bottom:20px;">
        <a href="dashboard.php" class="btn-back">Back to Dashboard</a>
    </div>
    
    <table>
        <thead>
            <tr>
                <th colspan="7" style="background:#2c3e50;">CLINIC STAFF DIRECTORY</th>
            </tr>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Type</th>
                <th>Specialization</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while($doctor = mysqli_fetch_assoc($doctors_result)) { ?>
            <tr class="doctor-row">
                <td><?php echo $doctor['doctor_id']; ?></td>
                <td><?php echo $doctor['doctor_name']; ?></td>
                <td><span class="badge-doctor">🩺 Doctor</span></td>
                <td><?php echo $doctor['specialization']; ?></td>
                <td><?php echo $doctor['email']; ?></td>
                <td><?php echo $doctor['phone']; ?></td>
                <td>
                    <button class="btn-edit" onclick="location.href='edit_doctor.php?id=<?php echo $doctor['doctor_id']; ?>'">Edit</button>
                    <button class="btn-delete" onclick="if(confirm('Delete this doctor?')) location.href='delete_doctor.php?id=<?php echo $doctor['doctor_id']; ?>'">Delete</button>
                </td>
            </tr>
            <?php } ?>
            
            <?php while($staff = mysqli_fetch_assoc($staff_result)) { ?>
            <tr class="staff-row">
                <td><?php echo $staff['receptionist_id']; ?></td>
                <td><?php echo $staff['receptionist_name']; ?></td>
                <td><span class="badge-staff">📋 Receptionist</span></td>
                <td>-</td>
                <td><?php echo $staff['email']; ?></td>
                <td><?php echo $staff['phone']; ?></td>
                <td>
                    <button class="btn-edit" onclick="location.href='edit_staff.php?id=<?php echo $staff['receptionist_id']; ?>'">Edit</button>
                    <button class="btn-delete" onclick="if(confirm('Delete this staff?')) location.href='delete_staff.php?id=<?php echo $staff['receptionist_id']; ?>'">Delete</button>
                </td>
            </tr>
            <?php } ?>
            
        </tbody>
    </table>
    
    <?php 
    if(mysqli_num_rows($doctors_result) == 0 && mysqli_num_rows($staff_result) == 0) {
        echo "<p style='text-align:center; color:red;'>No staff records found.</p>";
    }
    ?>
</div>

</body>
</html>