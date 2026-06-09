<?php
$con = mysqli_connect('localhost','root','','clinic_db');
if (!$con) { die("Connection failed: " . mysqli_connect_error()); }

$qry = mysqli_query($con, "SELECT * FROM appointment");
?>
<!DOCTYPE html>
<html>
<head>
<title>Appointments List</title>
<style>
	table{
		border-collapse:collapse;
		width:80%;margin:auto;
	}
	th,td{
		border:1px solid black;
		padding:8px;
		text-align:center;
	}
	th{
		background:#f2f2f2;
	}
</style>
</head>
<body>
	<h1>Appointments List</h1>
	<form action="registerAppointment.php" method="POST">
		<input type="submit" value="Add Appointment"></form><br>
	<table>
		<tr>
			<th>ID</th>
			<th>Patient ID</th>
			<th>Doctor ID</th>
			<th>Receptionist ID</th>
			<th>Date</th><th>Time</th>
			<th>Status</th>
			<th colspan="2">Action</th>
		</tr>
	<?php while ($row = mysqli_fetch_assoc($qry)) {
		echo "<tr>
				<td>{$row['apptId']}</td>
				<td>{$row['patientId']}</td>
				<td>{$row['doctorId']}</td>
				<td>{$row['receptionistId']}</td>
				<td>{$row['apptDate']}</td>
				<td>{$row['apptTime']}</td>
				<td>{$row['status']}</td>
				<td>
					<form action='updateAppointment.php' method='POST'>
					<input type='hidden' name='id' value='{$row['apptId']}'>
					<input type='submit' value='Edit'></form>
				</td>
			  </tr>";
	} ?>
	</table>
</body>
</html>
<?php mysqli_close($con); ?>
