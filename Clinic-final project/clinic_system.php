-- =====================================================
-- DATABASE: clinic_system
-- =====================================================

CREATE DATABASE clinic_system;
USE clinic_system;

-- =====================================================
-- TABLE: doctors
-- =====================================================
CREATE TABLE doctors (
    doctor_id VARCHAR(20) NOT NULL,
    doctor_name VARCHAR(100) NOT NULL,
    doctor_password VARCHAR(50) NOT NULL,
    specialization VARCHAR(100),
    email VARCHAR(100),
    phone VARCHAR(15),
    PRIMARY KEY (doctor_id)
);

-- =====================================================
-- TABLE: pharmacist
-- =====================================================
CREATE TABLE pharmacist (
    pharmacist_id VARCHAR(20) NOT NULL,
    pharmacist_name VARCHAR(100) NOT NULL,
    pharmacist_password VARCHAR(50) NOT NULL,
    email VARCHAR(100),
    phone VARCHAR(15),
    PRIMARY KEY (pharmacist_id)
);

-- =====================================================
-- TABLE: patients
-- =====================================================
CREATE TABLE patients (
    patient_id VARCHAR(20) NOT NULL,
    patient_name VARCHAR(100) NOT NULL,
    patient_password VARCHAR(50) NOT NULL,
    email VARCHAR(100),
    phone VARCHAR(15),
    address VARCHAR(255),
    date_of_birth DATE,
    gender VARCHAR(10),
    PRIMARY KEY (patient_id)
);

-- =====================================================
-- TABLE: doctor_schedule
-- =====================================================
CREATE TABLE doctor_schedule (
    schedule_id INT NOT NULL AUTO_INCREMENT,
    doctor_id VARCHAR(20) NOT NULL,
    schedule_date DATE NOT NULL,
    schedule_time TIME NOT NULL,
    schedule_status VARCHAR(20) DEFAULT 'Available',
    PRIMARY KEY (schedule_id),
    FOREIGN KEY (doctor_id) REFERENCES doctors(doctor_id)
);

-- =====================================================
-- TABLE: appointments
-- =====================================================
CREATE TABLE appointments (
    appointment_id INT NOT NULL AUTO_INCREMENT,
    patient_id VARCHAR(20) NOT NULL,
    doctor_id VARCHAR(20) NOT NULL,
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    status VARCHAR(20) DEFAULT 'Pending',
    consultation_notes TEXT,
    PRIMARY KEY (appointment_id),
    FOREIGN KEY (patient_id) REFERENCES patients(patient_id),
    FOREIGN KEY (doctor_id) REFERENCES doctors(doctor_id)
);

-- =====================================================
-- TABLE: queue
-- =====================================================
CREATE TABLE queue (
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
-- TABLE: prescriptions
-- =====================================================
CREATE TABLE prescriptions (
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
-- SAMPLE DATA
-- =====================================================

-- Doctor sample
INSERT INTO doctors VALUES ('D001', 'Dr. Ahmad Bin Abdullah', '12345', 'Cardiology', 'dr.ahmad@clinic.com', '0123456789');
INSERT INTO doctors VALUES ('D002', 'Dr. Siti Binti Rahman', '12345', 'Pediatrics', 'dr.siti@clinic.com', '0123456790');

-- Pharmacist sample
INSERT INTO pharmacist VALUES ('PH001', 'Nur Fatihah', '12345', 'fatihah@clinic.com', '0123456700');
INSERT INTO pharmacist VALUES ('PH002', 'Alicia Low', '12345', 'alicia@clinic.com', '0123456701');

-- Patient sample
INSERT INTO patients VALUES ('P001', 'John Doe', '12345', 'john@email.com', '0123456702', 'KL', '1990-05-15', 'Male');
INSERT INTO patients VALUES ('P002', 'Jane Smith', '12345', 'jane@email.com', '0123456703', 'PJ', '1985-08-22', 'Female');

-- Appointment sample
INSERT INTO appointments VALUES (1, 'P001', 'D001', '2026-06-10', '10:00:00', 'Completed', 'Patient has high blood pressure');
INSERT INTO appointments VALUES (2, 'P002', 'D002', '2026-06-10', '14:00:00', 'Completed', 'Child has fever');

-- Prescription sample
INSERT INTO prescriptions VALUES (1, 1, 'P001', 'D001', 'PH001', '2026-06-10', 'Paracetamol 500mg', 'High Blood Pressure', 'Dispensed', '2026-06-10');
INSERT INTO prescriptions VALUES (2, 2, 'P002', 'D002', NULL, '2026-06-10', 'Amoxicillin 250mg', 'Fever', 'Pending', NULL);