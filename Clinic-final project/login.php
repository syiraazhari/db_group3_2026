<?php
session_start();
include 'database.php';

$error = "";
$active_form = "login";

if(isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_destroy();
    header("Location: login.php");
    exit();
}

if(isset($_SESSION['user_id']) && isset($_SESSION['user_role'])) {
    if($_SESSION['user_role'] == 'patient') {
        header("Location: dashboard.php");
    } elseif($_SESSION['user_role'] == 'doctor') {
        header("Location: dashboard.php");
    } elseif($_SESSION['user_role'] == 'receptionist') {
        header("Location: dashboard.php");
    }
    exit();
}

if(isset($_POST['login'])) {
    $user_id = $_POST['user_id'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    
    if($role == 'patient') {
        $query = "SELECT * FROM patient WHERE patientId = '$user_id' AND patientPassword = '$password'";
        $result = mysqli_query($conn, $query);
        
        if(mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            $_SESSION['user_id'] = $user['patientId'];
            $_SESSION['user_name'] = $user['patientName'];
            $_SESSION['user_role'] = 'patient';
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid Patient ID or Password!";
        }
    }
    elseif($role == 'doctor') {
        $query = "SELECT * FROM doctor WHERE doctorId = '$user_id' AND doctorPassword = '$password'";
        $result = mysqli_query($conn, $query);
        
        if(mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            $_SESSION['user_id'] = $user['doctorId'];
            $_SESSION['user_name'] = $user['doctorName'];
            $_SESSION['user_role'] = 'doctor';
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid Doctor ID or Password!";
        }
    }
    elseif($role == 'receptionist') {
        $query = "SELECT * FROM receptionist WHERE receptionistId = '$user_id' AND receptionistPassword = '$password'";
        $result = mysqli_query($conn, $query);
        
        if(mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            $_SESSION['user_id'] = $user['receptionistId'];
            $_SESSION['user_name'] = $user['receptionistName'];
            $_SESSION['user_role'] = 'receptionist';
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid Receptionist ID or Password!";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Clinic System - Login</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; margin: 0; padding: 20px; }
        
        .container { max-width: 450px; margin: 80px auto; }
        
        /* Form Box */
        .form-box { background: white; padding: 35px; border-radius: 10px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); }
        
        h2 { text-align: center; color: #667eea; margin-bottom: 25px; }
        
        label { display: block; margin-top: 15px; font-weight: bold; color: #333; }
        input, select { width: 100%; padding: 12px; margin-top: 5px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; font-size: 14px; }
        input:focus, select:focus { outline: none; border-color: #667eea; }
        
        button[type="submit"] { width: 100%; padding: 12px; margin-top: 25px; background: #667eea; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; font-weight: bold; transition: background 0.3s; }
        button[type="submit"]:hover { background: #5a67d8; }
        
        .error { background: #f8d7da; color: #721c24; padding: 12px; border-radius: 5px; margin-bottom: 20px; border-left: 4px solid #721c24; }
        
        .demo-info { background: #e7f3ff; padding: 12px; border-radius: 5px; margin-top: 20px; text-align: center; font-size: 13px; border-left: 4px solid #2196F3; }
        .demo-info p { margin: 5px 0; }
    </style>
</head>
<body>

<div class="container">
    <div class="form-box">
        <h2>🔐 Clinic System Login</h2>
        
        <?php if($error != ""): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <label>👤 Select Role:</label>
            <select name="role" required>
                <option value="patient">👨‍⚕️ Patient</option>
                <option value="doctor">🩺 Doctor</option>
                <option value="receptionist">📋 Receptionist</option>
            </select>
            
            <label>🆓 Your ID Number:</label>
            <input type="text" name="user_id" placeholder="Example: 1, 2, 3" required>
            
            <label>🔒 Password:</label>
            <input type="password" name="password" placeholder="Enter your password" required>
            
            <button type="submit" name="login">Login to Dashboard</button>
        </form>
        
        <div class="demo-info">
            <p>🔗 <a href="pRegister.php">Register as New Patient</a></p>
        </div>
    </div>
</div>

</body>
</html>