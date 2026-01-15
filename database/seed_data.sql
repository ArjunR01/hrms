-- =========================================
-- SSMS HRMS - Seed Data with Demo Users
-- =========================================

-- Insert demo departments
INSERT INTO `departments` (`department_name`, `department_code`, `is_active`) VALUES
('Information Technology', 'IT', 1),
('Human Resources', 'HR', 1),
('Finance & Accounts', 'FIN', 1),
('Operations', 'OPS', 1),
('Sales & Marketing', 'SAL', 1);

-- Insert demo designations
INSERT INTO `designations` (`designation_name`, `designation_code`, `department_id`, `is_active`) VALUES
('Software Engineer', 'SE', 1, 1),
('HR Manager', 'HRM', 2, 1),
('Accountant', 'ACC', 3, 1),
('Operations Manager', 'OPM', 4, 1),
('Sales Executive', 'SLX', 5, 1);

-- Insert demo employees
INSERT INTO `employees` (`employee_id`, `full_name`, `email`, `phone`, `department`, `designation`, `date_of_joining`, `date_of_birth`, `gender`, `is_active`) VALUES
('EMP001', 'Super Admin User', 'superadmin@ssspl.com', '9876543210', 'Information Technology', 'System Administrator', '2024-01-01', '1990-01-01', 'Male', 1),
('EMP002', 'Admin User', 'admin@ssspl.com', '9876543211', 'Information Technology', 'IT Manager', '2024-01-01', '1991-02-15', 'Male', 1),
('EMP003', 'HR Manager', 'hr@ssspl.com', '9876543212', 'Human Resources', 'HR Manager', '2024-01-01', '1992-03-20', 'Female', 1),
('EMP004', 'Department Manager', 'manager@ssspl.com', '9876543213', 'Operations', 'Operations Manager', '2024-01-01', '1993-04-25', 'Male', 1),
('EMP005', 'John Doe', 'employee@ssspl.com', '9876543214', 'Information Technology', 'Software Engineer', '2024-01-01', '1995-05-30', 'Male', 1);

-- Insert demo users with password 'demo@123' (hashed)
-- Password hash for 'demo@123' using PHP password_hash()
-- You can generate this by running: echo password_hash('demo@123', PASSWORD_DEFAULT);
INSERT INTO `users` (`employee_id`, `username`, `email`, `password_hash`, `role`, `is_active`) VALUES
(1, 'superadmin', 'superadmin@ssspl.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Super Admin', 1),
(2, 'admin', 'admin@ssspl.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin', 1),
(3, 'hr', 'hr@ssspl.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'HR', 1),
(4, 'manager', 'manager@ssspl.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Manager', 1),
(5, 'employee', 'employee@ssspl.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Employee', 1);

-- Note: All demo accounts use password: demo@123
-- You can login with:
-- Username: superadmin, admin, hr, manager, or employee
-- OR Email: superadmin@ssspl.com, admin@ssspl.com, hr@ssspl.com, manager@ssspl.com, employee@ssspl.com
-- Password: demo@123
