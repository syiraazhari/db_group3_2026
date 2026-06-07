<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'database.php';

echo "<h2>Installing/Updating Database...</h2>";

// =============================================
// STEP 1: Drop all existing tables
// =============================================
$drop_sql = "
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS prescriptions;
DROP TABLE IF EXISTS queue;
DROP TABLE IF EXISTS doctor_schedule;
DROP TABLE IF EXISTS pharmacist;
DROP TABLE IF EXISTS appointments;
DROP TABLE IF EXISTS treatments;
DROP TABLE IF EXISTS doctors;
DROP TABLE IF EXISTS patients;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS = 1;
";

try {
    $pdo->exec($drop_sql);
    echo "<p style='color:orange'>✓ Old tables dropped successfully!</p>";
} catch(PDOException $e) {
    echo "<p>No existing tables to drop.</p>";
}

// =============================================
// STEP 2: Create new tables
// =============================================
$sql = "

CREATE TABLE IF NOT EXISTS users (
    user_id INT NOT NULL AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(50) NOT NULL,
    role VARCHAR(50) NOT NULL,
    PRIMARY KEY (user_id)
);

CREATE TABLE IF NOT EXISTS patients (
    patient_id VARCHAR(20) NOT NULL,
    patient_name VARCHAR(100) NOT NULL,
    patient_password VARCHAR(50) NOT NULL,
    email VARCHAR(100) NULL,
    phone VARCHAR(15) NULL,
    address VARCHAR(255) NULL,
    date_of_birth DATE NULL,
    gender VARCHAR(10) NULL,
    PRIMARY KEY (patient_id)
);

CREATE TABLE IF NOT EXISTS doctors (
    doctor_id VARCHAR(20) NOT NULL,
    doctor_name VARCHAR(100) NOT NULL,
    doctor_password VARCHAR(50) NOT NULL,
    specialization VARCHAR(100) NOT NULL,
    email VARCHAR(100) NULL,
    phone VARCHAR(15) NULL,
    PRIMARY KEY (doctor_id)
);

CREATE TABLE IF NOT EXISTS pharmacist (
    pharmacist_id VARCHAR(20) NOT NULL,
    pharmacist_name VARCHAR(100) NOT NULL,
    pharmacist_password VARCHAR(50) NOT NULL,
    email VARCHAR(100) NULL,
    phone VARCHAR(15) NULL,
    PRIMARY KEY (pharmacist_id)
);

CREATE TABLE IF NOT EXISTS doctor_schedule (
    schedule_id INT NOT NULL AUTO_INCREMENT,
    doctor_id VARCHAR(20) NOT NULL,
    schedule_date DATE NOT NULL,
    schedule_time TIME NOT NULL,
    schedule_status VARCHAR(20) DEFAULT 'Available',
    PRIMARY KEY (schedule_id),
    FOREIGN KEY (doctor_id) REFERENCES doctors(doctor_id)
);

CREATE TABLE IF NOT EXISTS appointments (
    appointment_id INT NOT NULL AUTO_INCREMENT,
    patient_id VARCHAR(20) NOT NULL,
    doctor_id VARCHAR(20) NOT NULL,
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    status VARCHAR(20) DEFAULT 'Pending',
    consultation_notes TEXT NULL,
    PRIMARY KEY (appointment_id),
    FOREIGN KEY (patient_id) REFERENCES patients(patient_id),
    FOREIGN KEY (doctor_id) REFERENCES doctors(doctor_id)
);

CREATE TABLE IF NOT EXISTS queue (
    queue_id INT NOT NULL AUTO_INCREMENT,
    queue_number VARCHAR(10) NOT NULL,
    patient_id VARCHAR(20) NOT NULL,
    doctor_id VARCHAR(20) NOT NULL,
    appointment_id INT NOT NULL,
    queue_status VARCHAR(20) DEFAULT 'Waiting',
    PRIMARY KEY (queue_id),
    FOREIGN KEY (patient_id) REFERENCES patients(patient_id),
    FOREIGN KEY (doctor_id) REFERENCES doctors(doctor_id),
    FOREIGN KEY (appointment_id) REFERENCES appointments(appointment_id)
);

CREATE TABLE IF NOT EXISTS prescriptions (
    prescription_id INT NOT NULL AUTO_INCREMENT,
    appointment_id INT NOT NULL,
    patient_id VARCHAR(20) NOT NULL,
    doctor_id VARCHAR(20) NOT NULL,
    pharmacist_id VARCHAR(20) NULL,
    prescription_date DATE NOT NULL,
    medication_details VARCHAR(255) NOT NULL,
    diagnosis VARCHAR(255) NOT NULL,
    dispensing_status VARCHAR(20) DEFAULT 'Pending',
    dispensing_date DATE NULL,
    PRIMARY KEY (prescription_id),
    FOREIGN KEY (appointment_id) REFERENCES appointments(appointment_id),
    FOREIGN KEY (patient_id) REFERENCES patients(patient_id),
    FOREIGN KEY (doctor_id) REFERENCES doctors(doctor_id),
    FOREIGN KEY (pharmacist_id) REFERENCES pharmacist(pharmacist_id)
);

CREATE TABLE IF NOT EXISTS treatments (
    treatment_id INT NOT NULL AUTO_INCREMENT,
    appointment_id INT NOT NULL,
    diagnosis VARCHAR(255) NOT NULL,
    prescription VARCHAR(255) NULL,
    treatment_notes TEXT NULL,
    PRIMARY KEY (treatment_id),
    FOREIGN KEY (appointment_id) REFERENCES appointments(appointment_id)
);

";

try {
    $pdo->exec($sql);
    echo "<p style='color:green'>✓ All tables created successfully!</p>";
    
    // Insert sample data
    echo "<h3>Inserting sample data...</h3>";
    
    // Sample Doctors
    $pdo->exec("INSERT IGNORE INTO doctors VALUES 
        ('D001', 'Dr. Ahmad Bin Abdullah', '12345', 'Cardiology', 'dr.ahmad@clinic.com', '0123456789'),
        ('D002', 'Dr. Siti Binti Rahman', '12345', 'Pediatrics', 'dr.siti@clinic.com', '0123456790')");
    
    // Sample Pharmacists
    $pdo->exec("INSERT IGNORE INTO pharmacist VALUES 
        ('PH001', 'Nur Fatihah', '12345', 'fatihah@clinic.com', '0123456700'),
        ('PH002', 'Alicia Low', '12345', 'alicia@clinic.com', '0123456701')");
    
    // Sample Patients
    $pdo->exec("INSERT IGNORE INTO patients VALUES 
        ('P001', 'John Doe', '12345', 'john@email.com', '0123456702', 'Kuala Lumpur', '1990-05-15', 'Male'),
        ('P002', 'Jane Smith', '12345', 'jane@email.com', '0123456703', 'Petaling Jaya', '1985-08-22', 'Female')");
    
    echo "<p style='color:green'>✓ Sample data inserted!</p>";
    
    echo "<hr>";
    echo "<h3>✅ Installation Complete!</h3>";
    echo "<p><strong>Login Credentials:</strong></p>";
    echo "<ul>";
    echo "<li><strong>Doctor:</strong> ID: D001 or D002 | Password: 12345</li>";
    echo "<li><strong>Pharmacist:</strong> ID: PH001 or PH002 | Password: 12345</li>";
    echo "<li><strong>Patient:</strong> ID: P001 or P002 | Password: 12345</li>";
    echo "</ul>";
    echo "<a href='login.php'>Go to Login Page</a>";
    
} catch(PDOException $e) {
    echo "<h2 style='color:red'>Error: </h2>" . $e->getMessage();
}
?>