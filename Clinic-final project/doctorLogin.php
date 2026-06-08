<?php
session_start();
include 'database.php';

$error = "";
$success = "";
$active_form = "login"; // Default to login form

// Function to get next doctor ID
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

// LOGOUT Logic
if(isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_destroy();
    header("Location: doctor_auth.php");
    exit();
}

// Check if already logged in
if(isset($_SESSION['doctor_id'])) {
    header("Location: doctor_dashboard.php");
    exit();
}

// LOGIN Logic
if(isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $query = "SELECT * FROM doctor WHERE doctorUsername = '$username' AND doctorPassword = '$password' AND doctorStatus = 'Active'";
    $result = mysqli_query($conn, $query);
    
    if(mysqli_num_rows($result) > 0) {
        $doctor = mysqli_fetch_assoc($result);
        $_SESSION['doctor_id'] = $doctor['doctorId'];
        $_SESSION['doctor_name'] = $doctor['doctorName'];
        $_SESSION['doctor_username'] = $doctor['doctorUsername'];
        $_SESSION['user_type'] = 'doctor';
        
        header("Location: doctor_dashboard.php");
        exit();
    } else {
        $error = "Invalid username/password or account inactive!";
        $active_form = "login";
    }
}

// REGISTER Logic
if(isset($_POST['register'])) {
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
    
    // Check if username already exists
    $check_query = "SELECT * FROM doctor WHERE doctorUsername = '$username'";
    $check_result = mysqli_query($conn, $check_query);
    
    if(mysqli_num_rows($check_result) > 0) {
        $error = "Username already exists! Please choose another username.";
        $active_form = "register";
    } else {
        $query = "INSERT INTO doctor (doctorId, doctorName, specialization, doctorIc, doctorUsername, doctorPassword, gender, doctorPhoneNo, doctorEmail, consultationFee, doctorStatus) 
                  VALUES ('$doctor_id', '$doctor_name', '$specialization', '$doctor_ic', '$username', '$password', '$gender', '$phone', '$email', '$consultation_fee', '$doctor_status')";
        
        if(mysqli_query($conn, $query)) {
            $success = "Registration successful! Your Doctor ID is: " . $doctor_id . ". Please login.";
            $active_form = "login";
        } else {
            $error = "Registration failed: " . mysqli_error($conn);
            $active_form = "register";
        }
    }
}

$generated_id = getNextDoctorId($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Doctor Portal - Login & Register</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; margin: 0; padding: 20px; }
        
        .container { max-width: 500px; margin: 50px auto; }
        
        /* Toggle Buttons */
        .toggle-buttons { display: flex; margin-bottom: 20px; border-radius: 10px; overflow: hidden; }
        .toggle-btn { flex: 1; padding: 15px; text-align: center; background: rgba(255,255,255,0.2); color: white; cursor: pointer; font-weight: bold; transition: all 0.3s; border: none; font-size: 16px; }
        .toggle-btn.active { background: white; color: #667eea; }
        .toggle-btn:hover:not(.active) { background: rgba(255,255,255,0.3); }
        
        /* Form Boxes */
        .form-box { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); display: none; }
        .form-box.active { display: block; animation: fadeIn 0.5s; }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        h2 { text-align: center; color: #667eea; margin-bottom: 25px; }
        
        label { display: block; margin-top: 15px; font-weight: bold; color: #333; }
        input, select { width: 100%; padding: 12px; margin-top: 5px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; font-size: 14px; }
        input:focus, select:focus { outline: none; border-color: #667eea; }
        input[readonly] { background-color: #e9ecef; cursor: not-allowed; }
        
        button[type="submit"] { width: 100%; padding: 12px; margin-top: 25px; background: #667eea; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; font-weight: bold; transition: background 0.3s; }
        button[type="submit"]:hover { background: #5a67d8; }
        
        .error { background: #f8d7da; color: #721c24; padding: 12px; border-radius: 5px; margin-bottom: 20px; border-left: 4px solid #721c24; }
        .success { background: #d4edda; color: #155724; padding: 12px; border-radius: 5px; margin-bottom: 20px; border-left: 4px solid #155724; }
        .info { background: #e7f3ff; padding: 12px; border-radius: 5px; margin-bottom: 20px; text-align: center; border-left: 4px solid #2196F3; }
        
        .back-link { text-align: center; margin-top: 20px; padding-top: 20px; border-top: 1px solid #eee; }
        .back-link a { color: #667eea; text-decoration: none; }
        .back-link a:hover { text-decoration: underline; }
        
        hr { margin: 20px 0; border: none; border-top: 1px solid #eee; }
    </style>
</head>
<body>

<div class="container">
    <!-- Toggle Buttons -->
    <div class="toggle-buttons">
        <button class="toggle-btn <?php echo ($active_form == 'login') ? 'active' : ''; ?>" onclick="showForm('login')">🔐 Doctor Login</button>
        <button class="toggle-btn <?php echo ($active_form == 'register') ? 'active' : ''; ?>" onclick="showForm('register')">📝 New Doctor Registration</button>
    </div>

    <!-- LOGIN FORM -->
    <div id="login-form" class="form-box <?php echo ($active_form == 'login') ? 'active' : ''; ?>">
        <h2>Doctor Login</h2>
        
        <?php if($error != "" && $active_form == 'login'): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <label>Username:</label>
            <input type="text" name="username" required>
            
            <label>Password:</label>
            <input type="password" name="password" required>
            
            <button type="submit" name="login">Login to Dashboard</button>
        </form>
        
        <div class="back-link">
            <a href="login.php">← Switch to Patient Login</a>
        </div>
    </div>

    <!-- REGISTER FORM -->
    <div id="register-form" class="form-box <?php echo ($active_form == 'register') ? 'active' : ''; ?>">
        <h2>Doctor Registration</h2>
        
        <div class="info">
            📋 Your Doctor ID will be: <strong><?php echo $generated_id; ?></strong>
        </div>
        
        <?php if($error != "" && $active_form == 'register'): ?>
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
            
            <button type="submit" name="register">Register as Doctor</button>
        </form>
        
        <div class="back-link">
            <a href="login.php">← Switch to Patient Login</a>
        </div>
    </div>
</div>

<script>
    function showForm(formType) {
        // Hide both forms
        document.getElementById('login-form').classList.remove('active');
        document.getElementById('register-form').classList.remove('active');
        
        // Update toggle buttons
        const btns = document.querySelectorAll('.toggle-btn');
        btns.forEach(btn => btn.classList.remove('active'));
        
        // Show selected form
        if(formType === 'login') {
            document.getElementById('login-form').classList.add('active');
            document.querySelector('.toggle-btn:first-child').classList.add('active');
        } else {
            document.getElementById('register-form').classList.add('active');
            document.querySelector('.toggle-btn:last-child').classList.add('active');
        }
        
        // Clear URL hash without refreshing
        history.pushState(null, '', '#');
    }
    
    // Check URL hash for form selection
    if(window.location.hash === '#register') {
        showForm('register');
    } else if(window.location.hash === '#login') {
        showForm('login');
    }
</script>

</body>
</html>