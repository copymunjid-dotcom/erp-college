-- =====================================================
-- STUDENT MANAGEMENT SYSTEM
-- DATABASE.SQL (PART 1)
-- =====================================================

DROP DATABASE IF EXISTS student_management_system;

CREATE DATABASE student_management_system;

USE student_management_system;

-- =====================================================
-- LOOKUP TABLE : DEPARTMENTS
-- =====================================================

CREATE TABLE departments (

    department_id INT AUTO_INCREMENT PRIMARY KEY,

    department_name VARCHAR(100) NOT NULL UNIQUE

);

INSERT INTO departments (department_name) VALUES
('B.Tech CSE'),
('B.Tech IT'),
('BCA'),
('B.Sc Computer Science'),
('B.Com'),
('BBA'),
('MCA'),
('MBA');

-- =====================================================
-- LOOKUP TABLE : YEARS
-- =====================================================

CREATE TABLE years (

    year_id INT AUTO_INCREMENT PRIMARY KEY,

    year_name VARCHAR(30) NOT NULL UNIQUE

);

INSERT INTO years (year_name) VALUES
('1st Year'),
('2nd Year'),
('3rd Year'),
('4th Year');

-- =====================================================
-- LOOKUP TABLE : GENDERS
-- =====================================================

CREATE TABLE genders (

    gender_id INT AUTO_INCREMENT PRIMARY KEY,

    gender_name VARCHAR(20) NOT NULL UNIQUE

);

INSERT INTO genders (gender_name) VALUES
('Male'),
('Female'),
('Others');

-- =====================================================
-- LOOKUP TABLE : BOARDS
-- =====================================================

CREATE TABLE boards (

    board_id INT AUTO_INCREMENT PRIMARY KEY,

    board_name VARCHAR(50) NOT NULL UNIQUE

);

INSERT INTO boards (board_name) VALUES
('State Board'),
('CBSE'),
('ICSE'),
('IB'),
('IGCSE'),
('NIOS');

-- =====================================================
-- LOOKUP TABLE : QUALIFICATIONS
-- =====================================================

CREATE TABLE qualifications (

    qualification_id INT AUTO_INCREMENT PRIMARY KEY,

    qualification_name VARCHAR(50) NOT NULL UNIQUE

);

INSERT INTO qualifications (qualification_name) VALUES
('10th'),
('12th');

-- =====================================================
-- LOOKUP TABLE : RELATIONSHIP TYPES
-- =====================================================

CREATE TABLE relationship_types (

    relationship_type_id INT AUTO_INCREMENT PRIMARY KEY,

    relationship_name VARCHAR(50) NOT NULL UNIQUE

);

INSERT INTO relationship_types (relationship_name) VALUES

('Father'),
('Mother'),
('Guardian'),
('Brother'),
('Sister'),
('Grandfather'),
('Grandmother'),
('Uncle'),
('Aunt'),
('Relative'),
('Friend'),
('Other');

-- =====================================================
-- LOOKUP TABLE : DOCUMENT TYPES
-- =====================================================

CREATE TABLE document_types (

    document_type_id INT AUTO_INCREMENT PRIMARY KEY,

    document_name VARCHAR(100) NOT NULL UNIQUE

);

INSERT INTO document_types (document_name) VALUES

('Photo'),
('10th Marksheet'),
('12th Marksheet'),
('Aadhaar'),
('Transfer Certificate'),
('Community Certificate');

-- =====================================================
-- STUDENTS
-- =====================================================

CREATE TABLE students (

    student_id INT AUTO_INCREMENT PRIMARY KEY,

    student_name VARCHAR(100) NOT NULL,

    register_number VARCHAR(30) NOT NULL UNIQUE,

    email VARCHAR(100) NOT NULL UNIQUE,

    phone VARCHAR(15) NOT NULL,

    gender_id INT NOT NULL,

    date_of_birth DATE NOT NULL,

    department_id INT NOT NULL,

    year_id INT NOT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (gender_id)
        REFERENCES genders(gender_id),

    FOREIGN KEY (department_id)
        REFERENCES departments(department_id),

    FOREIGN KEY (year_id)
        REFERENCES years(year_id)

);
-- =====================================================
-- STUDENT ACCOUNTS
-- =====================================================



-- =====================================================
-- STUDENT ADDRESSES
-- =====================================================

CREATE TABLE student_addresses (

    address_id INT AUTO_INCREMENT PRIMARY KEY,

    student_id INT NOT NULL,

    permanent_address TEXT NOT NULL,

    temporary_address TEXT,

    FOREIGN KEY (student_id)
        REFERENCES students(student_id)
        ON DELETE CASCADE

);

-- =====================================================
-- FAMILY DETAILS
-- =====================================================

CREATE TABLE family_details (

    family_id INT AUTO_INCREMENT PRIMARY KEY,

    student_id INT NOT NULL,

    relationship_type_id INT NOT NULL,

    name VARCHAR(100) NOT NULL,

    occupation VARCHAR(100),

    phone VARCHAR(15) NOT NULL,

    FOREIGN KEY (student_id)
        REFERENCES students(student_id)
        ON DELETE CASCADE,

    FOREIGN KEY (relationship_type_id)
        REFERENCES relationship_types(relationship_type_id)

);

-- =====================================================
-- SIBLINGS
-- =====================================================

CREATE TABLE siblings (

    sibling_id INT AUTO_INCREMENT PRIMARY KEY,

    student_id INT NOT NULL,

    sibling_name VARCHAR(100) NOT NULL,

    relationship_type_id INT NOT NULL,

    FOREIGN KEY (student_id)
        REFERENCES students(student_id)
        ON DELETE CASCADE,

    FOREIGN KEY (relationship_type_id)
        REFERENCES relationship_types(relationship_type_id)

);

-- =====================================================
-- EMERGENCY CONTACTS
-- =====================================================

CREATE TABLE emergency_contacts (

    emergency_id INT AUTO_INCREMENT PRIMARY KEY,

    student_id INT NOT NULL,

    emergency_name VARCHAR(100) NOT NULL,

    relationship_type_id INT NOT NULL,

    emergency_phone VARCHAR(15) NOT NULL,

    FOREIGN KEY (student_id)
        REFERENCES students(student_id)
        ON DELETE CASCADE,

    FOREIGN KEY (relationship_type_id)
        REFERENCES relationship_types(relationship_type_id)

);

-- =====================================================
-- ACADEMIC DETAILS
-- =====================================================

CREATE TABLE academic_details (

    academic_id INT AUTO_INCREMENT PRIMARY KEY,

    student_id INT NOT NULL,

    qualification_id INT NOT NULL,

    school_name VARCHAR(150) NOT NULL,

    percentage DECIMAL(5,2) NOT NULL,

    board_id INT NOT NULL,

    FOREIGN KEY (student_id)
        REFERENCES students(student_id)
        ON DELETE CASCADE,

    FOREIGN KEY (qualification_id)
        REFERENCES qualifications(qualification_id),

    FOREIGN KEY (board_id)
        REFERENCES boards(board_id)

);

-- =====================================================
-- STUDENT DOCUMENTS
-- =====================================================

CREATE TABLE student_documents (

    document_id INT AUTO_INCREMENT PRIMARY KEY,

    student_id INT NOT NULL,

    document_type_id INT NOT NULL,

    file_path VARCHAR(255) NOT NULL,

    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (student_id)
        REFERENCES students(student_id)
        ON DELETE CASCADE,

    FOREIGN KEY (document_type_id)
        REFERENCES document_types(document_type_id)

);
CREATE TABLE users (

    user_id INT AUTO_INCREMENT PRIMARY KEY,

    student_id INT NOT NULL,

    register_number VARCHAR(30) NOT NULL UNIQUE,

    email VARCHAR(100) NOT NULL UNIQUE,

    password VARCHAR(255) NOT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (student_id)
        REFERENCES students(student_id)
        ON DELETE CASCADE

);
ALTER TABLE students
ADD program_level_id INT NOT NULL;
CREATE TABLE program_levels (
    program_level_id INT AUTO_INCREMENT PRIMARY KEY,
    program_level_name VARCHAR(50) NOT NULL UNIQUE
);
INSERT INTO program_levels (program_level_name)
VALUES
('Undergraduate'),
('Postgraduate'),
('Diploma');
ALTER TABLE students
ADD CONSTRAINT fk_program_level
FOREIGN KEY (program_level_id)
REFERENCES program_levels(program_level_id);
CREATE TABLE roles (
    role_id INT AUTO_INCREMENT PRIMARY KEY,
    role_name VARCHAR(50) NOT NULL UNIQUE
);
INSERT INTO roles (role_name)
VALUES
('Student'),
('Staff'),
('Admin');
ALTER TABLE users
ADD role_id INT NOT NULL;
ALTER TABLE users
ADD CONSTRAINT fk_users_role
FOREIGN KEY (role_id)
REFERENCES roles(role_id);
UPDATE users
SET role_id = 3
WHERE register_number = 'admin' AND password = 'admin123';