<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "Setting up database...\n";

// Create users table
DB::statement('CREATE TABLE IF NOT EXISTS users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM("super_admin", "tenant", "employee") NOT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    tenant_id BIGINT UNSIGNED NULL,
    role_id BIGINT UNSIGNED NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (tenant_id) REFERENCES tenants(id)
)');

echo "Users table created\n";

// Create marques table
DB::statement('CREATE TABLE IF NOT EXISTS marques (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tenant_id BIGINT UNSIGNED NOT NULL,
    marque VARCHAR(100) NOT NULL,
    image VARCHAR(255) NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE
)');

echo "Marques table created\n";

// Create students table
DB::statement('CREATE TABLE IF NOT EXISTS students (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tenant_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NULL,
    student_number VARCHAR(255) UNIQUE NOT NULL,
    name VARCHAR(100) NOT NULL,
    name_ar VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    phone VARCHAR(20) NOT NULL,
    cin VARCHAR(20) UNIQUE NOT NULL,
    birth_date DATE NOT NULL,
    birth_place VARCHAR(100) NOT NULL,
    address TEXT NOT NULL,
    reference VARCHAR(255) NOT NULL,
    cinimage VARCHAR(255) NOT NULL,
    image VARCHAR(255) NOT NULL,
    emergency_contact_name VARCHAR(100) NOT NULL,
    emergency_contact_phone VARCHAR(20) NOT NULL,
    license_category VARCHAR(10) NULL,
    status ENUM("registered", "active", "suspended", "graduated", "dropped") NOT NULL DEFAULT "registered",
    registration_date DATE NOT NULL,
    theory_hours_completed INT NOT NULL DEFAULT 0,
    practical_hours_completed INT NOT NULL DEFAULT 0,
    required_theory_hours INT NOT NULL DEFAULT 20,
    required_practical_hours INT NOT NULL DEFAULT 20,
    total_paid DECIMAL(10,2) NOT NULL DEFAULT 0,
    total_due DECIMAL(10,2) NOT NULL DEFAULT 0,
    progress_skills JSON NULL,
    notes TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
)');

echo "Students table created\n";

// Create instructors table
DB::statement('CREATE TABLE IF NOT EXISTS instructors (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tenant_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    employee_number VARCHAR(255) UNIQUE NOT NULL,
    license_number VARCHAR(255) UNIQUE NOT NULL,
    license_expiry DATE NOT NULL,
    license_categories VARCHAR(255) NULL,
    years_experience INT NOT NULL DEFAULT 0,
    hourly_rate DECIMAL(8,2) NOT NULL DEFAULT 0,
    max_students INT NOT NULL DEFAULT 20,
    current_students INT NOT NULL DEFAULT 0,
    status ENUM("active", "inactive", "suspended") NOT NULL DEFAULT "active",
    availability_schedule JSON NULL,
    specializations JSON NULL,
    notes TEXT NULL,
    is_available TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)');

echo "Instructors table created\n";

// Create vehicules table
DB::statement('CREATE TABLE IF NOT EXISTS vehicules (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tenant_id BIGINT UNSIGNED NOT NULL,
    marque VARCHAR(255) NULL,
    name VARCHAR(255) NOT NULL,
    immatriculation VARCHAR(255) UNIQUE NOT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    is_training_vehicle TINYINT(1) NOT NULL DEFAULT 0,
    training_type ENUM("theory", "practical", "both") NOT NULL DEFAULT "practical",
    required_licenses JSON NULL,
    has_dual_controls TINYINT(1) NOT NULL DEFAULT 0,
    has_automatic_transmission TINYINT(1) NOT NULL DEFAULT 0,
    has_manual_transmission TINYINT(1) NOT NULL DEFAULT 1,
    max_students INT NOT NULL DEFAULT 1,
    hourly_rate DECIMAL(8,2) NOT NULL DEFAULT 0,
    safety_features JSON NULL,
    last_inspection DATE NULL,
    next_inspection DATE NULL,
    requires_maintenance TINYINT(1) NOT NULL DEFAULT 0,
    maintenance_notes TEXT NULL,
    landing_display TINYINT(1) NOT NULL DEFAULT 0,
    landing_order INT NOT NULL DEFAULT 0,
    categorie_vehicule ENUM("A","B","C","D","E") NULL,
    couleur VARCHAR(255) NULL,
    description TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE
)');

echo "Vehicules table created\n";

// Create packages table
DB::statement('CREATE TABLE IF NOT EXISTS packages (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tenant_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(200) NOT NULL,
    description TEXT NULL,
    license_category VARCHAR(10) NULL,
    theory_hours INT NOT NULL DEFAULT 0,
    practical_hours INT NOT NULL DEFAULT 0,
    price DECIMAL(10,2) NOT NULL,
    validity_days INT NOT NULL DEFAULT 365,
    includes_exam TINYINT(1) NOT NULL DEFAULT 0,
    includes_materials TINYINT(1) NOT NULL DEFAULT 0,
    features JSON NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE
)');

echo "Packages table created\n";

// Create lessons table
DB::statement('CREATE TABLE IF NOT EXISTS lessons (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tenant_id BIGINT UNSIGNED NOT NULL,
    student_id BIGINT UNSIGNED NOT NULL,
    instructor_id BIGINT UNSIGNED NOT NULL,
    vehicule_id BIGINT UNSIGNED NULL,
    lesson_number VARCHAR(255) NOT NULL,
    lesson_type ENUM("theory", "practical", "simulation") NOT NULL DEFAULT "practical",
    title VARCHAR(200) NOT NULL,
    description TEXT NULL,
    scheduled_at DATETIME NOT NULL,
    completed_at DATETIME NULL,
    duration_minutes INT NOT NULL DEFAULT 60,
    status ENUM("scheduled", "in_progress", "completed", "cancelled", "no_show") NOT NULL DEFAULT "scheduled",
    location VARCHAR(200) NULL,
    price DECIMAL(8,2) NOT NULL DEFAULT 0,
    skills_covered JSON NULL,
    student_rating INT NULL,
    instructor_notes TEXT NULL,
    student_feedback TEXT NULL,
    cancellation_reason TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (instructor_id) REFERENCES instructors(id) ON DELETE CASCADE,
    FOREIGN KEY (vehicule_id) REFERENCES vehicules(id) ON DELETE SET NULL
)');

echo "Lessons table created\n";

// Create exams table
DB::statement('CREATE TABLE IF NOT EXISTS exams (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tenant_id BIGINT UNSIGNED NOT NULL,
    student_id BIGINT UNSIGNED NOT NULL,
    exam_type ENUM("theory", "practical", "final") NOT NULL,
    exam_date DATETIME NOT NULL,
    location VARCHAR(255) NOT NULL,
    status ENUM("scheduled", "in_progress", "completed", "cancelled", "failed") NOT NULL DEFAULT "scheduled",
    score DECIMAL(5,2) NULL,
    max_score DECIMAL(5,2) NOT NULL DEFAULT 100,
    passed TINYINT(1) NULL,
    examiner_name VARCHAR(255) NULL,
    examiner_notes TEXT NULL,
    retake_required TINYINT(1) NOT NULL DEFAULT 0,
    retake_date DATETIME NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
)');

echo "Exams table created\n";

// Create payments table
DB::statement('CREATE TABLE IF NOT EXISTS payments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tenant_id BIGINT UNSIGNED NOT NULL,
    student_id BIGINT UNSIGNED NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    payment_method ENUM("cash", "card", "bank_transfer", "check", "online") NOT NULL,
    payment_date DATETIME NOT NULL,
    reference VARCHAR(255) NULL,
    description TEXT NULL,
    status ENUM("pending", "completed", "failed", "refunded") NOT NULL DEFAULT "completed",
    transaction_id VARCHAR(255) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
)');

echo "Payments table created\n";

// Create student_progress table
DB::statement('CREATE TABLE IF NOT EXISTS student_progress (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tenant_id BIGINT UNSIGNED NOT NULL,
    student_id BIGINT UNSIGNED NOT NULL,
    instructor_id BIGINT UNSIGNED NOT NULL,
    skill_category VARCHAR(100) NOT NULL,
    skill_name VARCHAR(200) NOT NULL,
    skill_level ENUM("beginner", "intermediate", "advanced", "expert") NOT NULL,
    hours_practiced DECIMAL(4,2) NOT NULL DEFAULT 0,
    attempts INT NOT NULL DEFAULT 0,
    success_rate DECIMAL(5,2) NOT NULL DEFAULT 0,
    instructor_notes TEXT NULL,
    assessment_criteria JSON NULL,
    is_required TINYINT(1) NOT NULL DEFAULT 1,
    is_completed TINYINT(1) NOT NULL DEFAULT 0,
    last_practiced DATETIME NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (instructor_id) REFERENCES instructors(id) ON DELETE CASCADE
)');

echo "Student progress table created\n";

// Create instructor_availability table
DB::statement('CREATE TABLE IF NOT EXISTS instructor_availability (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tenant_id BIGINT UNSIGNED NOT NULL,
    instructor_id BIGINT UNSIGNED NOT NULL,
    day_of_week ENUM("monday", "tuesday", "wednesday", "thursday", "friday", "saturday", "sunday") NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    is_available TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE,
    FOREIGN KEY (instructor_id) REFERENCES instructors(id) ON DELETE CASCADE
)');

echo "Instructor availability table created\n";

// Create vehicle_assignments table
DB::statement('CREATE TABLE IF NOT EXISTS vehicle_assignments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tenant_id BIGINT UNSIGNED NOT NULL,
    vehicule_id BIGINT UNSIGNED NOT NULL,
    instructor_id BIGINT UNSIGNED NOT NULL,
    assigned_date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    status ENUM("assigned", "in_use", "returned", "maintenance") NOT NULL DEFAULT "assigned",
    notes TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE,
    FOREIGN KEY (vehicule_id) REFERENCES vehicules(id) ON DELETE CASCADE,
    FOREIGN KEY (instructor_id) REFERENCES instructors(id) ON DELETE CASCADE
)');

echo "Vehicle assignments table created\n";

// Create student_packages table
DB::statement('CREATE TABLE IF NOT EXISTS student_packages (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tenant_id BIGINT UNSIGNED NOT NULL,
    student_id BIGINT UNSIGNED NOT NULL,
    package_id BIGINT UNSIGNED NOT NULL,
    purchase_date DATE NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    price_paid DECIMAL(10,2) NOT NULL,
    status ENUM("active", "completed", "expired", "cancelled") NOT NULL DEFAULT "active",
    theory_hours_used INT NOT NULL DEFAULT 0,
    practical_hours_used INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (package_id) REFERENCES packages(id) ON DELETE CASCADE
)');

echo "Student packages table created\n";

echo "Database setup completed!\n";
