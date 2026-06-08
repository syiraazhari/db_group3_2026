<?php
include 'database.php';
$error = "";
$success = "";
$generated_id = "";

function getNextDoctorId($conn) {
    $query = "SELECT doctorId FROM doctor ORDER BY doctorId DESC LIMIT 1";
    $result = mysqli_query($conn, $query);
    
    if(mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $last_id = $row['doctorId'];
        $new_id = $last_id + 1;
        return $new_id;
    } else {
        return 1;
    }
}

$generated_id = getNextDoctorId($conn);

if ($_POST) {
    $doctor_id = $_POST['doctor_id'];
    $doctor_name = $_POST['doctor_name'];
    $specialization = $_POST['specialization'];
    $doctor_ic = $_POST['doctor_ic'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $gender = $_POST['gender'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $consultation_fee = $_POST['consultation_fee'];
    $doctor_status = "Active";
    
    $query = "INSERT INTO doctor (doctorId, doctorName, specialization, doctorIc, doctorUsername, doctorPassword, gender, doctorPhoneNo, doctorEmail, consultationFee, doctorStatus) 
              VALUES ('$doctor_id', '$doctor_name', '$specialization', '$doctor_ic', '$username', '$password', '$gender', '$phone', '$email', '$consultation_fee', '$doctor_status')";
    
    if(mysqli_query($conn, $query)) {
        $success = "Registration successful! Your Doctor ID is: " . $doctor_id;
        $generated_id = getNextDoctorId($conn);
    } else {
        $error = "Registration failed: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Doctor Registration</title>
    <style>
        body { font-family: Arial; background: #CCCCFF; margin: 0; padding: 20px; }
        .register-box { max-width: 500px; margin: 50px auto; background: white; padding: 30px; border-radius: 10px; }
        h2 { text-align: center; color: #2c3e50; }
        label { display: block; margin-top: 15px; font-weight: bold; }
        input, select { width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        input[readonly] { background-color: #e9ecef; cursor: not-allowed; }
        button { width: 100%; padding: 12px; margin-top: 20px; background: #27ae60; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; }
        button:hover { background: #229954; }
        .error { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-top: 10px; }
        .success { background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-top: 10px; }
        .info { background: #e7f3ff; padding: 10px; border-radius: 5px; margin-bottom: 20px; text-align: center; }
        .back-link { text-align: center; margin-top: 20px; }
        .back-link a { color: #27ae60; text-decoration: none; }
        .back-link a:hover { text-decoration: underline; }
    </style>
</head>
<body>

<div class="register-box">
    <h2>Doctor Registration</h2>
    
    <div class="info">
        <strong>Your Doctor ID will be assigned automatically:</strong> <?php echo $generated_id; ?>
    </div>
    
    <?php if($error != ""): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <?php if($success != ""): ?>
        <div class="success"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <form method="POST" action="">
        <label>Doctor ID (Auto-generated):</label>
        <input type="text" name="doctor_id" value="<?php echo $generated_id; ?>" readonly>
        
        <label>Full Name:</label>
        <input type="text" name="doctor_name" required>
        
        <label>Specialization:</label>
        <select name="specialization" required>
            <option value="">Select Specialization</option>
            <option value="Cardiologist">Cardiologist (Heart Specialist)</option>
            <option value="Dermatologist">Dermatologist (Skin Specialist)</option>
            <option value="Pediatrician">Pediatrician (Child Specialist)</option>
            <option value="Orthopedic">Orthopedic (Bone Specialist)</option>
            <option value="Neurologist">Neurologist (Brain/Nerve Specialist)</option>
            <option value="Psychiatrist">Psychiatrist (Mental Health)</option>
            <option value="Ophthalmologist">Ophthalmologist (Eye Specialist)</option>
            <option value="ENT Specialist">ENT Specialist (Ear, Nose, Throat)</option>
            <option value="General Practitioner">General Practitioner</option>
            <option value="Dentist">Dentist</option>
        </select>
        
        <label>IC/Passport Number:</label>
        <input type="text" name="doctor_ic" required>
        
        <label>Username:</label>
        <input type="text" name="username" required>
        
        <label>Password:</label>
        <input type="password" name="password" required>
        
        <label>Gender:</label>
        <select name="gender" required>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
        </select>
        
        <label>Phone Number:</label>
        <input type="text" name="phone" required>
        
        <label>Email:</label>
        <input type="email" name="email" required>
        
        <label>Consultation Fee (RM):</label>
        <input type="number" name="consultation_fee" step="0.01" required placeholder="e.g., 80.00">
        
        <button type="submit">Register as Doctor</button>
    </form>
    
    <div class="back-link">
        <a href="login.php">← Back to Login</a>
    </div>
</div>

</body>
</html>