<?php
$con = mysqli_connect('localhost','root','','clinic_db');
if (!$con) { die("Connection failed: " . mysqli_connect_error()); }

$qry = mysqli_query($con, "
    SELECT q.queueId, q.availability,
           p.patientName, d.doctorName, r.receptionistName
    FROM queue q
    JOIN patient p ON q.patientId = p.patientId
    JOIN doctor d ON q.doctorId = d.doctorId
    JOIN receptionist r ON q.receptionistId = r.receptionistId
");
?>
<!DOCTYPE html>
<html>
<head>
<title>Queue List</title>
<style>
    table{border-collapse:collapse;width:80%;margin:auto;}
    th,td{border:1px solid black;padding:8px;text-align:center;}
    th{background:#f2f2f2;}
</style>
</head>
<body>
<h1>Queue List</h1>
<form action="registerQueue.php" method="POST">
    <input type="submit" value="Add Queue Entry">
</form>
<br>
<table>
<tr><th>ID</th><th>Patient</th><th>Doctor</th><th>Receptionist</th><th>Availability</th><th colspan="2">Action</th></tr>
<?php while ($row = mysqli_fetch_assoc($qry)) {
    echo "<tr>
            <td>{$row['queueId']}</td>
            <td>{$row['patientName']}</td>
            <td>{$row['doctorName']}</td>
            <td>{$row['receptionistName']}</td>
            <td>{$row['availability']}</td>
            <td>
				<form action='updateQueue.php' method='POST'>
				<input type='hidden' name='id' value='{$row['queueId']}'>
				<input type='submit' value='Edit'>
				</form>
			</td>
			<td>
				<form action='deleteQueue.php' method='POST'
					  onsubmit='return confirmDelete({$row['queueId']});'>
					<input type='hidden' name='id' value='{$row['queueId']}'>
					<input type='submit' value='Delete'>
				</form>
			</td>
          </tr>";
} ?>
</table>

<script>
	function confirmDelete(id) {
		return confirm("Are you sure you want to delete Queue ID " + id + "?");
	}
</script>


</body>
</html>
<?php mysqli_close($con); ?>


