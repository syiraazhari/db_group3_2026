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
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #27ae60; color: white; }
        tr:hover { background: #f5f5f5; }
        .status-pending { background: #ffeaa7; color: #d35400; padding: 3px 8px; border-radius: 5px; }
    </style>
</head>
<body>

<div class="header">
    <h2>🏥 Clinic System - Doctor</h2>
    <a href="logout.php">Logout</a>
</div>

<div class="container">
    <div class="welcome">
        <h3>Welcome, Dr. ali!</h3>
        <p>Doctor ID: 1</p>
    </div>
    
    <!-- DOCTOR PROFILE RECTANGLE -->
    <div class="profile-box">
        <h3>👨‍⚕️ My Profile</h3>
                <div class="profile-details">
            <div class="profile-item"><div class="profile-label">Name</div><div class="profile-value">ali</div></div>
            <div class="profile-item"><div class="profile-label">Specialization</div><div class="profile-value">therapy</div></div>
            <div class="profile-item"><div class="profile-label">Phone</div><div class="profile-value">13336</div></div>
            <div class="profile-item"><div class="profile-label">Email</div><div class="profile-value">ali@gmail.com</div></div>
        </div>
        <a href="doctorViewAppointment.php" class="btn">📋 View My Appointments</a>
    </div>
</div>

</body>
</html>