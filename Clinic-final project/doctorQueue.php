<?php
session_start();
include 'database.php';

// Check if doctor is logged in
if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'doctor') {
    header("Location: login.php");
    exit();
}

$doctorId = $_SESSION['user_id'];

// Get queue list
$query = "SELECT q.*, p.patientName, p.patientIc, p.patientPhoneNo, a.apptTime, a.status as appointmentStatus
          FROM queue q 
          JOIN patient p ON q.patientId = p.patientId 
          JOIN appointment a ON q.appointmentId = a.apptId
          WHERE q.doctorId = '$doctorId'
          ORDER BY q.queueId ASC";
$queue_list = mysqli_query($conn, $query);

// Update queue status
if(isset($_POST['update_queue_status'])) {
    $queue_id = $_POST['queue_id'];
    $queue_status = $_POST['queue_status'];
    
    $update_query = "UPDATE queue SET queueStatus = '$queue_status' WHERE queueId = '$queue_id'";
    if(mysqli_query($conn, $update_query)) {
        $message = "Queue status updated!";
        // Refresh the page
        header("Location: doctor_queue.php");
        exit();
    }
}

// Call next patient
if(isset($_GET['call_next'])) {
    $queue_id = $_GET['call_next'];
    $update_query = "UPDATE queue SET queueStatus = 'In Consultation' WHERE queueId = '$queue_id'";
    mysqli_query($conn, $update_query);
    header("Location: doctor_queue.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Queue - Doctor Panel</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f2f5;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .nav {
            background: white;
            padding: 12px 30px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .nav a {
            color: #667eea;
            text-decoration: none;
            padding: 10px 20px;
            margin: 0 5px;
            display: inline-block;
            font-weight: 500;
            border-radius: 5px;
        }
        
        .nav a:hover, .nav a.active {
            background: #667eea;
            color: white;
        }
        
        .container {
            padding: 30px;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .queue-stats {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            text-align: center;
        }
        
        .queue-stats h2 {
            font-size: 48px;
            margin: 10px 0;
        }
        
        .queue-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.