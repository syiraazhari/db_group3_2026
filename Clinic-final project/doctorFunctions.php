<?php
// Function to sanitize input data
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Function to check if doctor is logged in
function isDoctorLoggedIn() {
    return isset($_SESSION['doctor_id']) && isset($_SESSION['doctor_role']);
}

// Function to redirect if not logged in
function requireDoctorLogin() {
    if (!isDoctorLoggedIn()) {
        header("Location: login.php");
        exit();
    }
}

// Function to get doctor appointments
function getDoctorAppointments($conn, $doctorId, $status = null) {
    $sql = "SELECT a.*, p.patientName, p.patientIc, p.patientPhoneNo, p.patientEmail 
            FROM appointment a 
            JOIN patient p ON a.patientId = p.patientId 
            WHERE a.doctorId = $doctorId";
    
    if ($status && $status != 'all') {
        $sql .= " AND a.status = '$status'";
    }
    
    $sql .= " ORDER BY a.apptDate DESC, a.apptTime ASC";
    
    return mysqli_query($conn, $sql);
}

// Function to get today's appointments
function getTodayAppointments($conn, $doctorId) {
    $today = date('Y-m-d');
    $sql = "SELECT a.*, p.patientName, p.patientIc, p.patientPhoneNo, p.patientEmail 
            FROM appointment a 
            JOIN patient p ON a.patientId = p.patientId 
            WHERE a.doctorId = $doctorId AND a.apptDate = '$today'
            ORDER BY a.apptTime ASC";
    
    return mysqli_query($conn, $sql);
}

// Function to get queue for doctor
function getDoctorQueue($conn, $doctorId) {
    $sql = "SELECT q.*, p.patientName, p.patientIc, a.apptTime 
            FROM queue q 
            JOIN patient p ON q.patientId = p.patientId 
            JOIN appointment a ON q.patientId = a.patientId AND a.doctorId = q.doctorId
            WHERE q.doctorId = $doctorId 
            ORDER BY q.queueId ASC";
    
    return mysqli_query($conn, $sql);
}
?>