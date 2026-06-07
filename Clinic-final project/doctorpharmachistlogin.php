<?php
session_start();
require("../config/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    if ($role == "doctor") {
        $stmt = $pdo->prepare("SELECT * FROM DOCTOR WHERE DoctorName = ?");
    } else {
        $stmt = $pdo->prepare("SELECT * FROM PHARMACIST WHERE Username = ?");
    }

    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user) {
        if ($role == "doctor") {
            $_SESSION['doctor_id'] = $user['DoctorID'];
            header("Location: ../doctor/dashboard.php");
        } else {
            $_SESSION['pharmacist_id'] = $user['PharmacistID'];
            header("Location: ../pharmacist/dashboard.php");
        }
    } else {
        echo "Invalid login";
    }
}
?>