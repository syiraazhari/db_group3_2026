<?php
// Function to sanitize input
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Function to check if doctor is logged in
function isDoctorLoggedIn() {
    return isset($_SESSION['doctor_id']) && isset($_SESSION['doctor_role']) && $_SESSION['doctor_role'] == 'doctor';
}

// Function to require doctor login
function requireDoctorLogin() {
    if (!isDoctorLoggedIn()) {
        header("Location: login.php");
        exit();
    }
}

// Function to get doctor's appointments
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
    $sql = "SELECT a.*, p.patientName, p.patientIc, p.patientPhoneNo 
            FROM appointment a 
            JOIN patient p ON a.patientId = p.patientId 
            WHERE a.doctorId = $doctorId AND a.apptDate = '$today'
            ORDER BY a.apptTime ASC";
    
    return mysqli_query($conn, $sql);
}
?>