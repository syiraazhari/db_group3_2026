<?php
session_start();
include 'database.php';

if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'staff') {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User List - Clinic System</title>
    <style>
        body {
            font-family: Arial;
            background: #f0f0f0;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        h2 {
            color: #2c3e50;
            margin-bottom: 30px;
        }
        label {
            font-size: 18px;
            font-weight: bold;
            display: block;
            margin-bottom: 10px;
        }
        select {
            width: 60%;
            padding: 12px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        button {
            background-color: #3498db;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: #2980b9;
        }
        .back-btn {
            background-color: #95a5a6;
            margin-top: 20px;
            display: inline-block;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            color: white;
        }
        .back-btn:hover {
            background-color: #7f8c8d;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Clinic Management System</h2>
    <h3>Select User Type to View List</h3>
    
    <form method="POST" action="">
        <label>Select User Type:</label>
        <select name="user_type" required>
            <option value="" disabled selected>-- Select User Type --</option>
            <option value="patients">Patients</option>
            <option value="staffs">Staffs</option>
        </select>
        <br>
        <button type="submit">View List</button>
    </form>
    
    <br><br>
    <a href="dashboard.php" class="back-btn">← Back to Dashboard</a>
</div>

<?php

if ($_POST) {
    $user_type = $_POST['user_type'];
    
    if ($user_type == "patients") {
        header("Location: patientsList.php");
        exit();
    } 
    else if ($user_type == "staffs") {
        header("Location: staffsList.php");
        exit();
    }
}
?>

</body>
</html>