# 🚗 Driving School SaaS - Data Model Documentation

## Overview
This document outlines the comprehensive data model for the Driving School SaaS platform, designed to manage students, instructors, vehicles, scheduling, payments, exams, and regulatory compliance.

## Core Entities & Relationships

### 1. Multi-Tenant Architecture
- **tenants**: Main tenant/organization table
- **agences**: Branch/location management
- **users**: System users with role-based access

### 2. User Management
- **users**: Base user table with roles (super_admin, tenant, employee)
- **instructors**: Driving instructors with qualifications and availability
- **students**: Student profiles with progress tracking

### 3. Vehicle Management
- **vehicules**: Training vehicles with driving school specific features
- **marques**: Vehicle brands/manufacturers
- **vehicle_assignments**: Vehicle-to-instructor assignments for lessons

### 4. Learning Management
- **lessons**: Individual driving lessons (theory/practical)
- **theory_classes**: Group theory classes
- **student_theory_enrollments**: Student enrollment in theory classes
- **student_progress**: Detailed skill progress tracking

### 5. Assessment & Certification
- **exams**: Theory and practical exams
- **student_progress**: Skill-based progress tracking

### 6. Financial Management
- **payments**: Payment tracking and invoicing
- **packages**: Learning packages (theory + practical hours)
- **student_packages**: Student package purchases

### 7. Scheduling & Availability
- **instructor_availability**: Instructor weekly schedules
- **vehicle_assignments**: Vehicle usage tracking
- **lessons**: Lesson scheduling

### 8. Communication & Notifications
- **notifications**: System notifications (email, SMS, push, in-app)

### 9. Reporting & Analytics
- **reports**: Generated reports
- **analytics**: Daily metrics and KPIs

## Detailed Table Structures

### Instructors Table
```sql
instructors:
- id (PK)
- tenant_id (FK → tenants)
- agence_id (FK → agences)
- user_id (FK → users)
- employee_number (unique)
- license_number (unique)
- license_expiry
- license_categories (enum: A,B,C,D,E)
- years_experience
- hourly_rate
- max_students
- current_students
- status (enum: active, inactive, suspended)
- availability_schedule (JSON)
- specializations (JSON)
- notes
- is_available
```

### Students Table
```sql
students:
- id (PK)
- tenant_id (FK → tenants)
- agence_id (FK → agences)
- user_id (FK → users, nullable)
- student_number (unique)
- first_name, last_name
- email (unique)
- phone
- cin (unique)
- birth_date, birth_place
- address
- emergency_contact_name, emergency_contact_phone
- license_category (enum: A,B,C,D,E)
- status (enum: registered, active, suspended, graduated, dropped)
- registration_date
- license_expiry
- theory_hours_completed, practical_hours_completed
- required_theory_hours, required_practical_hours
- total_paid, total_due
- progress_skills (JSON)
- notes
```

### Lessons Table
```sql
lessons:
- id (PK)
- tenant_id (FK → tenants)
- agence_id (FK → agences)
- student_id (FK → students)
- instructor_id (FK → instructors)
- vehicle_id (FK → vehicules, nullable)
- lesson_number (unique)
- lesson_type (enum: theory, practical, simulation)
- title, description
- scheduled_at, completed_at
- duration_minutes
- status (enum: scheduled, in_progress, completed, cancelled, no_show)
- location
- price
- skills_covered (JSON)
- student_rating
- instructor_notes, student_feedback
- cancellation_reason
```

### Exams Table
```sql
exams:
- id (PK)
- tenant_id (FK → tenants)
- agence_id (FK → agences)
- student_id (FK → students)
- instructor_id (FK → instructors, nullable)
- examiner_id (FK → users, nullable)
- exam_number (unique)
- exam_type (enum: theory, practical, simulation)
- license_category (enum: A,B,C,D,E)
- scheduled_at, completed_at
- duration_minutes
- status (enum: scheduled, in_progress, passed, failed, cancelled, no_show)
- location
- exam_fee
- score, max_score
- exam_results (JSON)
- examiner_notes, feedback
- retake_date
- cancellation_reason
```

### Payments Table
```sql
payments:
- id (PK)
- tenant_id (FK → tenants)
- agence_id (FK → agences)
- student_id (FK → students)
- payment_number (unique)
- payment_type (enum: lesson, exam, registration, package, refund)
- lesson_id (FK → lessons, nullable)
- exam_id (FK → exams, nullable)
- amount, amount_paid, balance
- payment_method (enum: cash, card, bank_transfer, check, online)
- status (enum: pending, partial, paid, overdue, cancelled)
- due_date, paid_date
- transaction_id
- notes
- payment_details (JSON)
```

### Student Progress Table
```sql
student_progress:
- id (PK)
- tenant_id (FK → tenants)
- student_id (FK → students)
- lesson_id (FK → lessons, nullable)
- instructor_id (FK → instructors)
- skill_category (varchar)
- skill_name (varchar)
- skill_level (enum: beginner, intermediate, advanced, mastered)
- hours_practiced
- attempts
- success_rate
- instructor_notes
- assessment_criteria (JSON)
- is_required, is_completed
- last_practiced
```

## Key Relationships

### One-to-Many Relationships
- Tenant → Agencies
- Tenant → Instructors
- Tenant → Students
- Agency → Instructors
- Agency → Students
- Agency → Vehicles
- Instructor → Lessons
- Instructor → Student Progress
- Student → Lessons
- Student → Payments
- Student → Student Progress
- Vehicle → Vehicle Assignments
- Lesson → Payments

### Many-to-Many Relationships
- Students ↔ Theory Classes (via student_theory_enrollments)
- Students ↔ Packages (via student_packages)
- Instructors ↔ Vehicles (via vehicle_assignments)

## Business Rules & Constraints

### Instructor Management
- Each instructor belongs to one tenant and optionally one agency
- Instructors have specific license categories (A, B, C, D, E)
- Maximum student capacity per instructor
- Availability schedule stored as JSON

### Student Management
- Students can be associated with a user account (optional)
- Progress tracking by skill categories
- Required vs completed hours tracking
- Payment status and balance tracking

### Vehicle Management
- Training vehicles have specific features (dual controls, transmission type)
- Vehicle assignments track usage and maintenance
- Safety features and inspection tracking

### Scheduling
- Lessons can be theory, practical, or simulation
- Instructor availability managed separately
- Vehicle assignments for practical lessons

### Financial
- Multiple payment types (lesson, exam, registration, package)
- Package-based learning with hour tracking
- Payment status and balance management

## Indexes for Performance

### Primary Indexes
- All primary keys (id)
- Foreign key columns
- Unique constraints (student_number, license_number, etc.)

### Composite Indexes
- (tenant_id, date, metric_name) on analytics table
- (student_id, skill_category) on student_progress
- (instructor_id, scheduled_at) on lessons
- (vehicle_id, assigned_at) on vehicle_assignments

## Data Validation Rules

### Students
- Email must be unique per tenant
- CIN must be unique per tenant
- Required hours must be positive
- Registration date cannot be in the future

### Instructors
- License number must be unique per tenant
- License expiry must be in the future
- Max students must be positive
- Hourly rate must be non-negative

### Lessons
- Scheduled time cannot be in the past
- Duration must be positive
- Price must be non-negative
- Vehicle required for practical lessons

### Payments
- Amount must be positive
- Amount paid cannot exceed amount
- Balance = amount - amount_paid
- Due date required for pending payments

## Security Considerations

### Multi-Tenancy
- All tables include tenant_id for data isolation
- Foreign key constraints ensure data integrity
- Cascade deletes maintain referential integrity

### Access Control
- Role-based permissions via users table
- Tenant-level data isolation
- Agency-level data scoping where applicable

### Data Privacy
- Personal information properly secured
- Audit trails via timestamps
- Soft deletes where appropriate

## Scalability Considerations

### Partitioning
- Analytics table can be partitioned by date
- Notifications table can be partitioned by tenant_id

### Caching
- Frequently accessed data (instructor availability, student progress)
- Report data caching
- Session management

### Performance
- Proper indexing strategy
- JSON columns for flexible data storage
- Efficient query patterns

This data model provides a comprehensive foundation for the Driving School SaaS platform, supporting all core features while maintaining scalability, security, and data integrity.
