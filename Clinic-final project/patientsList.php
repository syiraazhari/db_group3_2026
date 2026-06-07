<?php
session_start();
include 'database.php';

if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'staff') {
    header("Location: login.php");
    exit();
}

$sql = "SELECT * FROM patients ORDER BY patient_id";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Patient List</title>
    <style>
        body { font-family: Arial; background: #f0f0f0; margin: 0; padding: 20px; }
        .header { background: #2c3e50; color: white; padding: 15px 20px; display: flex; justify-content: space-between; }
        .header a { color: white; text-decoration: none; background: #e74c3c; padding: 8px 15px; border-radius: 5px; }
        .container { max-width: 1200px; margin: auto; background: white; padding: 20px; border-radius: 10px; }
        h2 { text-align: center; color: #2c3e50; }
        table { width: 100%; margin: 20px auto; border-collapse: collapse; }
        th, td { padding: 12px; text-align: center; border: 1px solid #ddd; }
        th { background-color: #e67e22; color: white; }
        tr:nth-child(odd) { background-color: #F1F1F1; }
        tr:nth-child(even) { background-color: #D3EEEE; }
        .btn-back { background-color: #95a5a6; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; }
        .btn-edit { background-color: #f39c12; color: white; padding: 5px 10px; border: none; border-radius: 3px; cursor: pointer; }
        .btn-delete { background-color: #e74c3c; color: white; padding: 5px 10px; border: none; border-radius: 3px; cursor: pointer; }
    </style>
</head>
<body>

<div class="header">
    <h2>Patient List</h2>
    <a href="dashboard.php">← Back to Dashboard</a>
</div>

<div class="container">
    <h2>Patient List</h2>
    
    <div style="margin-bottom:20px;">
        <a href="dashboard.php" class="btn-back">Back to Dashboard</a>
    </div>
    
    <table>
        <thead>
            <tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Gender</th><th>DOB</th><th>Action</th></tr>
        </thead>
        <tbody>
            <?php while($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $row['patient_id']; ?></td>
                <td><?php echo $row['patient_name']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['phone']; ?></td>
                <td><?php echo $row['gender']; ?></td>
                <td><?php echo date('d/m/Y', strtotime($row['date_of_birth'])); ?></td>
                <td>
                    <button class="btn-edit" onclick="location.href='edit_patient.php?id=<?php echo $row['patient_id']; ?>'">Edit</button>
                    <button class="btn-delete" onclick="if(confirm('Delete?')) location.href='delete_patient.php?id=<?php echo $row['patient_id']; ?>'">Delete</button>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

</body>
</html>