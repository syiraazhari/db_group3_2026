<?php
require_once 'database.php';

$sql = "

$sql = "
CREATE TABLE IF NOT EXISTS users (
    user_id INT NOT NULL AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(50) NOT NULL,
    role VARCHAR(50) NOT NULL,

CREATE TABLE IF NOT EXISTS users (
    PRIMARY KEY (user_id)
);


CREATE TABLE IF NOT EXISTS patients (
    patient_password VARCHAR(50) NOT NULL,
    email VARCHAR(100) NULL,
    phone VARCHAR(15) NULL,
    phone INT NULL,
    address VARCHAR(255) NULL,
    date_of_birth DATE NULL,
    date_of_birth DATETIME NULL,
    gender VARCHAR(10) NULL,
    PRIMARY KEY (patient_id)
);

-- =====================================================
-- TABLE: doctors (UPDATED with password)
-- =====================================================
CREATE TABLE IF NOT EXISTS doctors (
    doctor_id VARCHAR(20) NOT NULL,
    doctor_name VARCHAR(100) NOT NULL,
    doctor_password VARCHAR(50) NOT NULL,
    specialization VARCHAR(100) NOT NULL,
    email VARCHAR(100) NULL,
    phone VARCHAR(15) NULL,
    PRIMARY KEY (doctor_id)
);

-- =====================================================
-- TABLE: pharmacist (NEW TABLE)
-- =====================================================
CREATE TABLE IF NOT EXISTS pharmacist (
    pharmacist_id VARCHAR(20) NOT NULL,
    pharmacist_name VARCHAR(100) NOT NULL,
    pharmacist_password VARCHAR(50) NOT NULL,
    email VARCHAR(100) NULL,
    phone VARCHAR(15) NULL,
    PRIMARY KEY (pharmacist_id)
);

-- =====================================================
-- TABLE: doctor_schedule (NEW TABLE for Manage Available Schedule)
-- =====================================================
CREATE TABLE IF NOT EXISTS doctor_schedule (
    schedule_id INT NOT NULL AUTO_INCREMENT,
    doctor_id VARCHAR(20) NOT NULL,
    schedule_date DATE NOT NULL,
    schedule_time TIME NOT NULL,
    schedule_status VARCHAR(20) DEFAULT 'Available',
    PRIMARY KEY (schedule_id),
    FOREIGN KEY (doctor_id) REFERENCES doctors(doctor_id)
);

-- =====================================================
-- TABLE: appointments (UPDATED with consultation_notes)
-- =====================================================
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

-- =====================================================
-- TABLE: queue (NEW TABLE for Queue List)
-- =====================================================
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

-- =====================================================
-- TABLE: prescriptions (NEW TABLE for Prescription Records)
-- =====================================================
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

-- =====================================================
-- TABLE: treatments (KEPT for reference)
-- =====================================================
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

// Execute SQL
try {
    $conn=exec($sql);
    echo "<h2 style='color:green'>All tables created successfully!</h2>";
    echo "<p>Database: hospital_db</p>";
    echo "<p>Tables created:</p>";
    echo "<ul>";
    echo "<li>users</li>";
    echo "<li>patients</li>";
    echo "<li>doctors (updated with password)</li>";
    echo "<li>pharmacist (NEW)</li>";
    echo "<li>doctor_schedule (NEW)</li>";
    echo "<li>appointments (updated with consultation_notes)</li>";
    echo "<li>queue (NEW)</li>";
    echo "<li>prescriptions (NEW)</li>";
    echo "<li>treatments</li>";
    echo "</ul>";
    
    // Insert sample data
    echo "<h3>Inserting sample data...</h3>";
    
    // Sample Doctors
    $conn=exec("INSERT IGNORE INTO doctors VALUES 
        ('D001', 'Dr. Ahmad Bin Abdullah', '12345', 'Cardiology', 'dr.ahmad@clinic.com', '0123456789'),
        ('D002', 'Dr. Siti Binti Rahman', '12345', 'Pediatrics', 'dr.siti@clinic.com', '0123456790')");
    
    // Sample Pharmacists
    $conn=exec("INSERT IGNORE INTO pharmacist VALUES 
        ('PH001', 'Nur Fatihah', '12345', 'fatihah@clinic.com', '0123456700'),
        ('PH002', 'Alicia Low', '12345', 'alicia@clinic.com', '0123456701')");
    
    // Sample Patients
    $conn=exec("INSERT IGNORE INTO patients VALUES 
        ('P001', 'John Doe', '12345', 'john@email.com', '0123456702', 'Kuala Lumpur', '1990-05-15', 'Male'),
        ('P002', 'Jane Smith', '12345', 'jane@email.com', '0123456703', 'Petaling Jaya', '1985-08-22', 'Female')");
    
    // Sample Appointments
    $conn=exec("INSERT IGNORE INTO appointments VALUES 
        (1, 'P001', 'D001', '2026-06-10', '10:00:00', 'Completed', 'Patient has high blood pressure'),
        (2, 'P002', 'D002', '2026-06-10', '14:00:00', 'Pending', NULL)");
    
    // Sample Prescriptions
    $conn=exec("INSERT IGNORE INTO prescriptions VALUES 
        (1, 1, 'P001', 'D001', 'PH001', '2026-06-10', 'Paracetamol 500mg', 'High Blood Pressure', 'Dispensed', '2026-06-10'),
        (2, 2, 'P002', 'D002', NULL, '2026-06-10', 'Amoxicillin 250mg', 'Fever', 'Pending', NULL)");
    
    echo "<p style='color:green'>✓ Sample data inserted successfully!</p>";
    echo "<p><strong>Login Credentials:</strong></p>";
    echo "<ul>";
    echo "<li><strong>Doctor:</strong> ID: D001 / D002 | Password: 12345</li>";
    echo "<li><strong>Pharmacist:</strong> ID: PH001 / PH002 | Password: 12345</li>";
    echo "<li><strong>Patient:</strong> ID: P001 / P002 | Password: 12345</li>";
    echo "</ul>";
    
} catch(PDOException $e) {
    echo "<h2 style='color:red'>Error creating tables: </h2>" . $e->getMessage();
}
?>