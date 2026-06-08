<!DOCTYPE html>
<html>
<head>
    <title>Receptionist Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f5f7fa; margin: 0; }
        header { background-color: #2c3e50; color: white; padding: 15px; text-align: center; }
        nav { background-color: #34495e; padding: 10px; }
        nav a { color: white; margin: 0 15px; text-decoration: none; font-weight: bold; }
        nav a:hover { color: #1abc9c; }
        .container { padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        table, th, td { border: 1px solid #bdc3c7; }
        th, td { padding: 10px; text-align: left; }
    </style>
</head>
<body>
    <header>
        <h1>Receptionist Dashboard</h1>
    </header>
    <nav>
        <a href="viewAppointments.php">View Appointments</a>
        <a href="viewQueue.php">View Queue List</a>
        <a href="viewPatients.php">View Patients</a>
        <a href="viewDoctors.php">View Doctors</a>
    </nav>
    <div class="container">
        <h2>Welcome, Receptionist</h2>
        <p>Select an option above to manage clinic records.</p>
    </div>
</body>
</html>
