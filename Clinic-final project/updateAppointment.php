<?php
$con = mysqli_connect('localhost','root','','clinic_db');
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $qry = mysqli_query($con, "SELECT * FROM appointment WHERE apptId=$id");
    $row = mysqli_fetch_assoc($qry);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Appointment</title>
</head>
<body>
    <h1>Update Appointment Status</h1>
    <form action="updateAppointment.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $row['apptId']; ?>">
        <p>
            <label>Status: </label>
            <select name="status" required>
                <option value="Pending" <?php if($row['status']=="Pending") echo "selected"; ?>>Pending</option>
                <option value="Confirmed" <?php if($row['status']=="Confirmed") echo "selected"; ?>>Confirmed</option>
                <option value="Completed" <?php if($row['status']=="Completed") echo "selected"; ?>>Completed</option>
                <option value="Cancelled" <?php if($row['status']=="Cancelled") echo "selected"; ?>>Cancelled</option>
            </select>
        </p>
        <p>
            <input type="submit" name="update" value="Save Changes">
        </p>
    </form>
</body>
</html>

<?php
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];

    $sql = "UPDATE appointment 
            SET status='$status' 
            WHERE apptId=$id";
    mysqli_query($con, $sql);

    header("Location: editAppointments(RECEPTIONIST).php"); // redirect back to appointment list
}
mysqli_close($con);
?>
