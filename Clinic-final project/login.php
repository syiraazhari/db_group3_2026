<?php
session_start();
include 'database.php';

$error = "";

if ($_POST) {
    $user_id = $_POST['user_id'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    if ($role == "patient") {
        $query = "SELECT * FROM patients WHERE patient_id = '$user_id' AND patient_password = '$password'";
        $result = mysqli_query($conn, $query);
        
        if(mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            $_SESSION['user_id'] = $row['patient_id'];
            $_SESSION['user_name'] = $row['patient_name'];
            $_SESSION['user_role'] = "patient";
            header("Location: pDashboard.php");
            exit();
        } else {
            $error = "Invalid Patient ID or Password!";
        }
    }
    if ($role == "doctor") {
        $query = "SELECT * FROM doctors WHERE doctor_id = '$user_id' AND doctor_password = '$password'";
        $result = mysqli_query($conn, $query);
        
        if(mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            $_SESSION['user_id'] = $row['doctor_id'];
            $_SESSION['user_name'] = $row['doctor_name'];
            $_SESSION['user_role'] = "doctor";
            header("Location: drDashboard.php");
            exit();
        } else {
            $error = "Invalid Doctor ID or Password!";
        }
    }
    if ($role == "staff") {
        $query = "SELECT * FROM receptionist WHERE receptionist_id = '$user_id' AND receptionist_password = '$password'";
        $result = mysqli_query($conn, $query);
        
        if(mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            $_SESSION['user_id'] = $row['receptionist_id'];
            $_SESSION['user_name'] = $row['receptionist_username'];
            $_SESSION['user_role'] = "staff";
            header("Location: staffDashboard.php");
            exit();
        } else {
            $error = "Invalid Staff ID or Password!";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Clinic System</title>
    <style>
        body {
            font-family: Arial;
            background-color:#CCCCFF;
            margin: 0;
            padding: 20px;
            min-height: 100vh;
        }
        .login-container {
            max-width: 400px;
            margin: 100px auto;
            background: white;
            padding: 35px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        .subtitle {
            text-align: center;
            color: #7f8c8d;
            font-size: 14px;
            margin-bottom: 30px;
        }
        label {
            display: block;
            margin-top: 20px;
            font-weight: bold;
            color: #333;
        }
        input, select {
            width: 100%;
            padding: 12px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 14px;
        }
        input:focus, select:focus {
            outline: none;
            border-color: #667eea;
        }
        button {
            width: 100%;
            padding: 14px;
            margin-top: 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
        }
        button:hover {
            opacity: 0.9;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 8px;
            margin-top: 20px;
            border-left: 4px solid #dc3545;
        }
        .register-link {
            text-align: center;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        .register-link a {
            color: #667eea;
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Clinic System</h2>
    <div class="subtitle">Login with your ID and Role</div>
    
    <?php 
	if($error != ""){
        echo $error;
	}
	?>
    
    <form method="POST" action="">
        <label>Select Your Role:</label>
        <select name="role" required>
            <option value="" disabled selected>-- Select Role --</option>
            <option value="patient">Patient</option>
            <option value="doctor">Doctor</option>
            <option value="staff">Staff / Receptionist</option>
        </select>
        
        <label>Your ID Number:</label>
        <input type="text" name="user_id" placeholder="Example: P001, D001, R001" required>
        
        <label>Password:</label>
        <input type="password" name="password" placeholder="Enter your password" required>
        
        <button type="submit">Login</button>
        
    </form>
    
    <div class="register-link">
        New patient? <a href="pRegister.php">Register here</a>
    </div>
</div>

</body>
</html>