<?php
$con = mysqli_connect('localhost','root','','clinic_db');
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $qry = mysqli_query($con, "SELECT * FROM queue WHERE queueId=$id");
    $row = mysqli_fetch_assoc($qry);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Queue</title>
</head>
<body>
    <h1>Update Queue Availability</h1>
    <form action="updateQueue.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $row['queueId']; ?>">
        <p>
            <label>Availability: </label>
            <select name="availability" required>
                <option value="Waiting" <?php if($row['availability']=="Waiting") echo "selected"; ?>>Waiting</option>
                <option value="Proceed" <?php if($row['availability']=="Proceed") echo "selected"; ?>>Proceed</option>
                <option value="In Consultation" <?php if($row['availability']=="In Consultation") echo "selected"; ?>>In Consultation</option>
				<option value="Finish" <?php if($row['availability']=="Finish") echo "selected"; ?>>Finish</option>
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
    $availability = $_POST['availability'];

    $sql = "UPDATE queue 
            SET availability='$availability' 
            WHERE queueId=$id";
    mysqli_query($con, $sql);

    header("Location: editQueue(RECEPTIONIST).php");
}
mysqli_close($con);
?>

