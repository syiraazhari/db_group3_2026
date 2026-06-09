<?php
$con = mysqli_connect('localhost','root','','clinic_db');
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['id'])) {
    $id = intval($_POST['id']); // sanitize input
    $sql = "DELETE FROM queue WHERE queueId=$id"; 
    mysqli_query($con, $sql);
}

// Redirect back to list
header("Location: editQueue(RECEPTIONIST).php");
mysqli_close($con);
?>




