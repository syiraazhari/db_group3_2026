<?php
require_once 'database.php';

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
    email VARCHAR(100) NULL,
    phone INT NULL,
    address VARCHAR(255) NULL,
    date_of_birth DATETIME NULL,
    gender VARCHAR(10) NULL,
    PRIMARY KEY (patient_id)
);

CREATE TABLE IF NOT EXISTS doctors (
    doctor_id VARCHAR(20) NOT NULL,
    doctor_name VARCHAR(100) NOT NULL,
    specialization VARCHAR(100) NOT NULL,
    email VARCHAR(100) NULL,
    phone INT NULL,
    PRIMARY KEY (doctor_id)
);

CREATE TABLE IF NOT EXISTS appointments (
    appointment_id INT NOT NULL AUTO_INCREMENT,
    patient_id VARCHAR(20) NOT NULL,
    doctor_id VARCHAR(20) NOT NULL,
    appointment_date DATETIME NOT NULL,
    appointment_time DATETIME NOT NULL,
    status VARCHAR(20) NULL,
    PRIMARY KEY (appointment_id),
    FOREIGN KEY (patient_id) REFERENCES patients(patient_id),
    FOREIGN KEY (doctor_id) REFERENCES doctors(doctor_id)
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

CREATE TABLE IF NOT EXISTS receptionist (
    receptionist_id INT NOT NULL AUTO_INCREMENT,
    receptionist_username VARCHAR(255) NOT NULL,
    receptionist_password VARCHAR(255) NOT NULL,
    receptionist_phoneNo VARCHAR(255) NOT NULL,
    receptionist_email VARCHAR(255) NOT NULL,
    PRIMARY KEY (receptionist_id),
);
";

try {
    $pdo->exec($sql);
    echo "<h2 style='color:green'>All tables created successfully!</h2>";
    echo "<p>Database: hospital_db</p>";
    echo "<p>Tables created: users, patients, doctors, appointments, treatments, receptionist</p>";
} catch(PDOException $e) {
    echo "<h2 style='color:red'>Error creating tables: </h2>" . $e->getMessage();
}
?>