<?php
$con = mysqli_connect('localhost','root','','clinic_db');
if (!$con) { die("Connection failed: " . mysqli_connect_error()); }

$qry = mysqli_query($con, "SELECT * FROM doctor");
?>
<!DOCTYPE html>
<html>
<head>
<title>Doctors List</title>
<style>table{border-collapse:collapse;width:80%;margin:auto;}th,td{border:1px solid black;padding:8px;text-align:center;}th{background:#f2f2f2;}</style>
</head>
<body>
<h1>Doctors List</h1>
<table>
<tr><th>ID</th><th>Name</th><th>Specialisation</th><th>Phone</th><th>Email</th><th>Username</th><th>Password</th></tr>
<?php while ($row = mysqli_fetch_assoc($qry)) {
    echo "<tr>
            <td>{$row['doctorId']}</td>
            <td>{$row['doctorName']}</td>
            <td>{$row['specialisation']}</td>
            <td>{$row['doctorPhoneNo']}</td>
            <td>{$row['doctorEmail']}</td>
            <td>{$row['doctorUsername']}</td>
            <td>{$row['doctorPassword']}</td>
          </tr>";
} ?>
</table>
</body>
</html>
<?php mysqli_close($con); ?>
