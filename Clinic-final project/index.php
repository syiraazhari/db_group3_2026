<?php
session_start();
include 'database.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Clinic System - Home</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; 
		background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
		min-height: 100vh;
		margin: 0; 
		padding: 20px;
		background-image: url('zclinic.jpeg');
        background-size: cover;
        background-position: center;

		}
        
        .container { max-width: 1200px; margin: 0 auto; }
        
        /* Header */
        .header { text-align: center; color: white; padding: 40px 20px; }
        .header h1 { font-size: 48px; margin-bottom: 10px; }
        .header p { font-size: 18px; opacity: 0.9; }
        
        /* Navigation Buttons */
        .nav-buttons { display: flex; justify-content: center; gap: 20px; margin: 30px 0; flex-wrap: wrap; }
        .nav-btn { padding: 12px 30px; border: none; border-radius: 5px; font-size: 16px; font-weight: bold; cursor: pointer; text-decoration: none; display: inline-block; transition: transform 0.2s; }
        .nav-btn:hover { transform: translateY(-3px); }
        .btn-login { background: white; color: #667eea; }
        .btn-register { background: #e67e22; color: white; }
        .btn-dashboard { background: #27ae60; color: white; }
        
        /* Feature Cards */
        .features { display: flex; gap: 30px; margin: 50px 0; flex-wrap: wrap; justify-content: center; }
        .feature-card { background: rgba(255,255,255,0.95); border-radius: 15px; padding: 30px; width: 250px; text-align: center; box-shadow: 0 10px 25px rgba(0,0,0,0.1); transition: transform 0.3s; }
        .feature-card:hover { transform: translateY(-10px); }
        .feature-icon { font-size: 50px; margin-bottom: 15px; }
        .feature-card h3 { color: #333; margin-bottom: 10px; }
        .feature-card p { color: #666; font-size: 14px; line-height: 1.5; }

        .welcome-box { background: rgba(255,255,255,0.15); border-radius: 10px; padding: 25px; text-align: center; margin: 30px 0; backdrop-filter: blur(5px); }
        .welcome-box h2 { color: white; margin-bottom: 15px; }
        .welcome-box p { color: white; font-size: 16px; }
        
        .demo-info { background: rgba(0,0,0,0.2); border-radius: 10px; padding: 15px; margin-top: 30px; text-align: center; font-size: 14px; color: white; }
        .demo-info p { margin: 5px 0; }
    </style>
</head>
<body>

<div class="container">
    <!-- Header -->
    <div class="header">
        <h1>🏥 Clinic Appointment System</h1>
        <p>Manage your health with us - Easy booking, quality care</p>
    </div>
    
    <!-- Navigation Buttons -->
    <div class="nav-buttons">
        <?php if(isset($_SESSION['user_id']) && isset($_SESSION['user_role'])): ?>
            <a href="dashboard.php" class="nav-btn btn-dashboard">📊 Go to Dashboard</a>
            <a href="?action=logout" class="nav-btn btn-login">🚪 Logout</a>
        <?php else: ?>
            <a href="login.php" class="nav-btn btn-login">🔐 Login</a>
            <a href="register.php" class="nav-btn btn-register">📝 Register as Patient</a>
        <?php endif; ?>
    </div>
    
    <?php if(isset($_SESSION['user_id']) && isset($_SESSION['user_role'])): ?>
        <!-- Welcome Message for Logged In Users -->
        <div class="welcome-box">
            <h2>Welcome back, <?php echo $_SESSION['user_name']; ?>!</h2>
            <p>Role: <?php echo ucfirst($_SESSION['user_role']); ?> | ID: <?php echo $_SESSION['user_id']; ?></p>
            <p style="margin-top: 15px;">Click the Dashboard button above to continue.</p>
        </div>
    <?php else: ?>
        <!-- Welcome Message for Guests -->
        <div class="welcome-box">
            <h2>Welcome to Our Clinic</h2>
            <p>Login to manage your appointments or register as a new patient.</p>
        </div>
    <?php endif; ?>
    
    <!-- Feature Cards -->
    <div class="features">
        <div class="feature-card">
            <div class="feature-icon">👨‍⚕️</div>
            <h3>Easy Appointment</h3>
            <p>Book appointments with your preferred doctor easily and quickly.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">🩺</div>
            <h3>Quality Doctors</h3>
            <p>Experienced and specialized doctors ready to help you.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">📋</div>
            <h3>Digital Records</h3>
            <p>Access your medical history and prescriptions anytime.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">🚶</div>
            <h3>Queue Management</h3>
            <p>Check your queue number and appointment status online.</p>
        </div>
    </div>
    
    <!-- Demo Info -->
    <div class="demo-info">
        THANK YOU FOR TRUSTING US!
    </div>

</div>

<?php
// Logout logic
if(isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_destroy();
    header("Location: index.php");
    exit();
}
?>

</body>
</html>