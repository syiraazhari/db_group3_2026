<?php
include 'database.php';
$error = "";
$success = "";
$generated_id = "";

function getNextPatientId($conn) {
    $query = "SELECT patient_id FROM patients ORDER BY patient_id DESC LIMIT 1";
    $result = mysqli_query($conn, $query);
    
    if(mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $last_id = $row['patient_id'];
        $last_num = (int)substr($last_id, 1);
        $new_num = $last_num + 1;
        $new_id = "P" . str_pad($new_num, 3, "0", STR_PAD_LEFT);
        return $new_id;
    } else {
        return "P001";
    }
}
$generated_id = getNextPatientId($conn);

if ($_POST) {
    $patient_id = $_POST['patient_id'];
    $patient_name = $_POST['patient_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $date_of_birth = $_POST['date_of_birth'];
    $gender = $_POST['gender'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $query = "INSERT INTO patients (patient_id, patient_name, email, phone, address, date_of_birth, gender, patient_username, patient_password) 
              VALUES ('$patient_id', '$patient_name', '$email', '$phone', '$address', '$date_of_birth', '$gender', '$username', '$password')";
    
    if(mysqli_query($conn, $query)) {
        $success = "Registration successful! Your Patient ID is: " . $patient_id;
        $generated_id = getNextPatientId($conn);
    } else {
        $error = "Registration failed: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Patient Registration</title>
    <style>
        body { font-family: Arial; background: #CCCCFF; margin: 0; padding: 20px; }
        .register-box { max-width: 500px; margin: 50px auto; background: white; padding: 30px; border-radius: 10px; }
        h2 { text-align: center; color: #2c3e50; }
        label { display: block; margin-top: 15px; font-weight: bold; }
        input, select { width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        input[readonly] { background-color: #e9ecef; cursor: not-allowed; }
        button { width: 100%; padding: 12px; margin-top: 20px; background: #3498db; color: white; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background: #2980b9; }
        .error { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-top: 10px; }
        .success { background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-top: 10px; }
        .info { background: #e7f3ff; padding: 10px; border-radius: 5px; margin-bottom: 20px; text-align: center; }
    </style>
</head>
<body>

<div class="register-box">
    <h2>Patient Registration</h2>
    
    <div class="info">
        <strong>Your Patient ID will be assigned automatically:</strong> <?php echo $generated_id; ?>
    </div>
    
    <?php if($error != ""){
        echo $error;
		}else if($success != ""){
        echo $success;
		} ?>
    
    <form method="POST" action="">
        <label>Patient ID (Auto-generated):</label>
        <input type="text" name="patient_id" value="<?php echo $generated_id; ?>" readonly>
        
        <label>Full Name:</label>
        <input type="text" name="patient_name" required>
        
        <label>Email:</label>
        <input type="email" name="email">
        
        <label>Phone Number:</label>
        <input type="text" name="phone">
        
        <label>Address:</label>
        <textarea name="address" rows="3" style="width:100%; padding:10px;"></textarea>
        
        <label>Date of Birth:</label>
        <input type="date" name="date_of_birth">
        
        <label>Gender:</label>
        <select name="gender">
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
        </select>
        
        <label>Username:</label>
        <input type="text" name="username" required>
        
        <label>Password:</label>
        <input type="password" name="password" required>
        
        <button type="submit">Register</button>
    </form>
    
    <p style="text-align:center; margin-top:20px;">
        Already have an account? <a href="login.php">Login here</a>
    </p>
</div>

</body>
</html>