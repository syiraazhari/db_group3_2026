<?php
$con = mysqli_connect('localhost','root','','clinic_db');
if (!$con) { die("Connection failed: " . mysqli_connect_error()); }

$qry = mysqli_query($con, "SELECT * FROM patient");
?>
<!DOCTYPE html>
<html>
<head>
<title>Patients List</title>
<style>
    table{ border-collapse: collapse; width: 80%; margin: auto; }
    th, td{ border: 1px solid black; padding: 8px; text-align: center; }
    th{ background-color: #f2f2f2; }
</style>
</head>
<body>
<h1>Patients List</h1>
<form action="pRegister.php" method="POST">
    <input type="submit" value="Add New Patient">
</form>
<br>
<table>
<tr>
    <th>ID</th><th>Name</th><th>IC</th><th>Username</th><th>Password</th>
    <th>Gender</th><th>DOB</th><th>Phone</th><th>Email</th><th>Status</th>
</tr>
<?php while ($row = mysqli_fetch_assoc($qry)) {
    echo "<tr>
            <td>{$row['patientId']}</td>
            <td>{$row['patientName']}</td>
            <td>{$row['patientIc']}</td>
            <td>{$row['patientUsername']}</td>
            <td>{$row['patientPassword']}</td>
            <td>{$row['gender']}</td>
            <td>{$row['DOB']}</td>
            <td>{$row['patientPhoneNo']}</td>
            <td>{$row['patientEmail']}</td>
            <td>{$row['patientStatus']}</td>
          </tr>";
} ?>
</table>
</body>
</html>
<?php mysqli_close($con); ?>
