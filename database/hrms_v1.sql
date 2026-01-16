-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql108.infinityfree.com
-- Generation Time: Jan 15, 2026 at 11:17 PM
-- Server version: 11.4.9-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_39401290_hrms`
--

-- --------------------------------------------------------

--
-- Table structure for table `assets`
--

CREATE TABLE `assets` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `asset_type` enum('Laptop','PC','Mobile','Sim Card','ID Card','Other') DEFAULT 'Other',
  `asset_code` varchar(100) DEFAULT NULL,
  `asset_value` decimal(12,2) DEFAULT 0.00,
  `accessories` text DEFAULT NULL,
  `issue_date` date DEFAULT NULL,
  `condition_status` varchar(100) DEFAULT NULL,
  `acknowledgement_receipt` enum('Yes','No') DEFAULT 'No',
  `return_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `attendance_date` date NOT NULL,
  `in_time` time DEFAULT NULL,
  `out_time` time DEFAULT NULL,
  `total_working_hours` decimal(5,2) DEFAULT 0.00,
  `late_marks` enum('Yes','No') DEFAULT 'No',
  `early_entry` enum('Yes','No') DEFAULT 'No',
  `early_exit` enum('Yes','No') DEFAULT 'No',
  `overtime_hours` decimal(5,2) DEFAULT 0.00,
  `shortage_hours` decimal(5,2) DEFAULT 0.00,
  `attendance_status` enum('Present','Absent','Half Day','Comp Off','Comp Working') DEFAULT 'Present',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` int(11) NOT NULL,
  `org_id` int(11) NOT NULL,
  `branch_name` varchar(150) NOT NULL,
  `branch_code` varchar(50) NOT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `contact_person` varchar(150) DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `candidates`
--

CREATE TABLE `candidates` (
  `id` int(11) NOT NULL,
  `job_id` int(11) DEFAULT NULL,
  `candidate_name` varchar(150) DEFAULT NULL,
  `resume_file` varchar(255) DEFAULT NULL,
  `source_of_profile` varchar(150) DEFAULT NULL,
  `present_salary` decimal(12,2) DEFAULT 0.00,
  `salary_offered` decimal(12,2) DEFAULT 0.00,
  `expected_joining_date` date DEFAULT NULL,
  `candidate_status` enum('Selected','On Hold','Rejected') DEFAULT 'On Hold',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `org_id` int(11) DEFAULT NULL,
  `department_name` varchar(150) NOT NULL,
  `department_code` varchar(50) NOT NULL,
  `hod_name` varchar(150) DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `org_id`, `department_name`, `department_code`, `hod_name`, `status`, `created_at`) VALUES
(1, NULL, 'Sales', 'SALES', NULL, 'Active', '2026-01-14 06:18:54'),
(2, NULL, 'Marketing', 'MKTG', NULL, 'Active', '2026-01-14 06:18:54'),
(3, NULL, 'Human Resources', 'HR', NULL, 'Active', '2026-01-14 06:18:54'),
(4, NULL, 'Information Technology', 'IT', NULL, 'Active', '2026-01-14 06:18:54'),
(5, NULL, 'Finance & Accounts', 'FIN', NULL, 'Active', '2026-01-14 06:18:54'),
(6, NULL, 'Operations', 'OPS', NULL, 'Active', '2026-01-14 06:18:54'),
(7, NULL, 'Sales & Marketing', 'SAL', NULL, 'Active', '2026-01-15 11:49:06');

-- --------------------------------------------------------

--
-- Table structure for table `designations`
--

CREATE TABLE `designations` (
  `id` int(11) NOT NULL,
  `org_id` int(11) DEFAULT NULL,
  `designation_name` varchar(150) NOT NULL,
  `grade_level` varchar(50) DEFAULT NULL,
  `reporting_hierarchy` text DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `designations`
--

INSERT INTO `designations` (`id`, `org_id`, `designation_name`, `grade_level`, `reporting_hierarchy`, `status`, `created_at`) VALUES
(1, NULL, 'HR Manager', 'L3', NULL, 'Active', '2026-01-14 06:18:54'),
(2, NULL, 'HR Executive', 'E3', NULL, 'Active', '2026-01-14 06:18:54'),
(3, NULL, 'Sales Manager', 'M1', NULL, 'Active', '2026-01-14 06:18:54'),
(4, NULL, 'Sales Executive', 'L1', NULL, 'Active', '2026-01-14 06:18:54'),
(5, NULL, 'Software Engineer', 'L1', NULL, 'Active', '2026-01-14 06:18:54'),
(6, NULL, 'Accountant', 'L2', NULL, 'Active', '2026-01-14 06:18:54'),
(7, NULL, 'Operations Manager', 'L4', NULL, 'Active', '2026-01-15 11:52:49');

-- --------------------------------------------------------

--
-- Table structure for table `disciplinary`
--

CREATE TABLE `disciplinary` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `complaint_description` text DEFAULT NULL,
  `show_cause_notices` text DEFAULT NULL,
  `enquiry_committee_report` text DEFAULT NULL,
  `disciplinary_actions` text DEFAULT NULL,
  `resolution_status` enum('Open','Closed') DEFAULT 'Open',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `org_id` int(11) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `designation_id` int(11) DEFAULT NULL,
  `shift_id` int(11) DEFAULT NULL,
  `employee_code` varchar(50) NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `dob` date NOT NULL,
  `blood_group` varchar(10) DEFAULT NULL,
  `marital_status` varchar(20) DEFAULT NULL,
  `nationality` varchar(100) DEFAULT NULL,
  `present_address` mediumtext DEFAULT NULL,
  `permanent_address` mediumtext DEFAULT NULL,
  `mobile_number` varchar(20) DEFAULT NULL,
  `emergency_contact_name` varchar(150) DEFAULT NULL,
  `emergency_contact_number` varchar(20) DEFAULT NULL,
  `personal_email` varchar(150) DEFAULT NULL,
  `aadhaar_number` varchar(20) DEFAULT NULL,
  `pan_number` varchar(20) DEFAULT NULL,
  `passport_number` varchar(30) DEFAULT NULL,
  `passport_valid_from` date DEFAULT NULL,
  `passport_valid_to` date DEFAULT NULL,
  `driving_license` varchar(30) DEFAULT NULL,
  `dl_valid_from` date DEFAULT NULL,
  `dl_valid_to` date DEFAULT NULL,
  `uan_number` varchar(30) DEFAULT NULL,
  `pf_number` varchar(30) DEFAULT NULL,
  `esic_number` varchar(30) DEFAULT NULL,
  `date_of_joining` date DEFAULT NULL,
  `employment_type` enum('Permanent','Contract','Consultant','Fixed','Project','Govt') DEFAULT 'Permanent',
  `grade` varchar(50) DEFAULT NULL,
  `reporting_manager_id` int(11) DEFAULT NULL,
  `training_period` varchar(50) DEFAULT NULL,
  `probation_period` varchar(50) DEFAULT NULL,
  `confirmation_date` date DEFAULT NULL,
  `commitment_from` date DEFAULT NULL,
  `commitment_to` date DEFAULT NULL,
  `profile_photo` varchar(255) DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `org_id`, `branch_id`, `department_id`, `designation_id`, `shift_id`, `employee_code`, `full_name`, `gender`, `dob`, `blood_group`, `marital_status`, `nationality`, `present_address`, `permanent_address`, `mobile_number`, `emergency_contact_name`, `emergency_contact_number`, `personal_email`, `aadhaar_number`, `pan_number`, `passport_number`, `passport_valid_from`, `passport_valid_to`, `driving_license`, `dl_valid_from`, `dl_valid_to`, `uan_number`, `pf_number`, `esic_number`, `date_of_joining`, `employment_type`, `grade`, `reporting_manager_id`, `training_period`, `probation_period`, `confirmation_date`, `commitment_from`, `commitment_to`, `profile_photo`, `status`, `created_at`) VALUES
(1, NULL, NULL, 1, 1, NULL, 'EMP001', 'Super Admin User', 'Male', '1990-01-01', NULL, NULL, NULL, NULL, NULL, '9876543210', NULL, NULL, 'superadmin@ssspl.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-01-01', 'Permanent', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Active', '2026-01-15 11:55:52'),
(2, NULL, NULL, 1, 1, NULL, 'EMP002', 'Admin User', 'Male', '1991-02-15', NULL, NULL, NULL, NULL, NULL, '9876543211', NULL, NULL, 'admin@ssspl.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-01-01', 'Permanent', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Active', '2026-01-15 11:55:52'),
(3, NULL, NULL, 2, 2, NULL, 'EMP003', 'HR Manager', 'Female', '1992-03-20', NULL, NULL, NULL, NULL, NULL, '9876543212', NULL, NULL, 'hr@ssspl.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-01-01', 'Permanent', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Active', '2026-01-15 11:55:52'),
(4, NULL, NULL, 4, 4, NULL, 'EMP004', 'Department Manager', 'Male', '1993-04-25', NULL, NULL, NULL, NULL, NULL, '9876543213', NULL, NULL, 'manager@ssspl.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-01-01', 'Permanent', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Active', '2026-01-15 11:55:52'),
(5, NULL, NULL, 1, 1, NULL, 'EMP005', 'John Doe', 'Male', '1995-05-30', NULL, NULL, NULL, NULL, NULL, '9876543214', NULL, NULL, 'employee@ssspl.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-01-01', 'Permanent', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Active', '2026-01-15 11:55:52');

-- --------------------------------------------------------

--
-- Table structure for table `employee_bank_details`
--

CREATE TABLE `employee_bank_details` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `bank_account_number` varchar(50) DEFAULT NULL,
  `ifsc_code` varchar(20) DEFAULT NULL,
  `bank_name` varchar(150) DEFAULT NULL,
  `branch_name` varchar(150) DEFAULT NULL,
  `branch_address` text DEFAULT NULL,
  `payment_mode` enum('Bank Transfer','Cash','Cheque','UPI') DEFAULT 'Bank Transfer',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_documents`
--

CREATE TABLE `employee_documents` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `document_type` varchar(100) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `uploaded_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_kyc`
--

CREATE TABLE `employee_kyc` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `aadhaar_number` varchar(20) DEFAULT NULL,
  `pan_number` varchar(20) DEFAULT NULL,
  `passport_number` varchar(50) DEFAULT NULL,
  `passport_valid_from` date DEFAULT NULL,
  `passport_valid_to` date DEFAULT NULL,
  `driving_license_number` varchar(50) DEFAULT NULL,
  `dl_valid_from` date DEFAULT NULL,
  `dl_valid_to` date DEFAULT NULL,
  `uan_number` varchar(50) DEFAULT NULL,
  `pf_number` varchar(50) DEFAULT NULL,
  `esic_number` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_status_history`
--

CREATE TABLE `employee_status_history` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `previous_status` varchar(50) DEFAULT NULL,
  `new_status` varchar(50) DEFAULT NULL,
  `change_date` date NOT NULL,
  `reason` text DEFAULT NULL,
  `changed_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `grievance`
--

CREATE TABLE `grievance` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `grievance_category` varchar(150) DEFAULT NULL,
  `complaint_description` text DEFAULT NULL,
  `enquiry_committee_report` text DEFAULT NULL,
  `action_taken` text DEFAULT NULL,
  `resolution_status` enum('Open','Closed') DEFAULT 'Open',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `holidays`
--

CREATE TABLE `holidays` (
  `id` int(11) NOT NULL,
  `org_id` int(11) DEFAULT NULL,
  `holiday_name` varchar(150) NOT NULL,
  `holiday_date` date NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leave_applications`
--

CREATE TABLE `leave_applications` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `leave_application_date` date NOT NULL,
  `leave_type_id` int(11) NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `number_of_days` decimal(5,2) DEFAULT 0.00,
  `reason` text DEFAULT NULL,
  `manager_approval` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `hr_approval` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `leave_status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leave_balance`
--

CREATE TABLE `leave_balance` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `leave_type_id` int(11) NOT NULL,
  `balance` decimal(6,2) DEFAULT 0.00,
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leave_requests`
--

CREATE TABLE `leave_requests` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `leave_type` varchar(50) NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `total_days` decimal(5,2) NOT NULL,
  `reason` text NOT NULL,
  `status` enum('Pending','Approved','Rejected','Cancelled') DEFAULT 'Pending',
  `approved_by` int(11) DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leave_types`
--

CREATE TABLE `leave_types` (
  `id` int(11) NOT NULL,
  `leave_name` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `leave_types`
--

INSERT INTO `leave_types` (`id`, `leave_name`) VALUES
(1, 'Casual Leave (CL)'),
(2, 'Sick Leave (SL)'),
(3, 'ESI Leave'),
(4, 'Earned Leave (EL)'),
(5, 'Compensatory Off'),
(6, 'Maternity Leave'),
(7, 'Paternity Leave'),
(8, 'Accident Leave'),
(9, 'On Duty / Tour'),
(10, 'Educational Leave'),
(11, 'On Training');

-- --------------------------------------------------------

--
-- Table structure for table `manpower_requisition`
--

CREATE TABLE `manpower_requisition` (
  `id` int(11) NOT NULL,
  `org_id` int(11) DEFAULT NULL,
  `job_title` varchar(150) DEFAULT NULL,
  `job_number` varchar(50) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `job_description` text DEFAULT NULL,
  `interview_panel` text DEFAULT NULL,
  `interview_status` varchar(100) DEFAULT NULL,
  `offer_letter_issued` enum('Yes','No') DEFAULT 'No',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `onboarding`
--

CREATE TABLE `onboarding` (
  `id` int(11) NOT NULL,
  `candidate_id` int(11) DEFAULT NULL,
  `joining_checklist` text DEFAULT NULL,
  `documents_required` text DEFAULT NULL,
  `employee_code_created` enum('Yes','No') DEFAULT 'No',
  `joining_date` date DEFAULT NULL,
  `hr_induction` enum('Yes','No') DEFAULT 'No',
  `departmental_induction_from` date DEFAULT NULL,
  `departmental_induction_to` date DEFAULT NULL,
  `internal_test_reports` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `organizations`
--

CREATE TABLE `organizations` (
  `id` int(11) NOT NULL,
  `company_name` varchar(200) NOT NULL,
  `company_code` varchar(50) NOT NULL,
  `cin_gst_number` varchar(100) DEFAULT NULL,
  `registered_address` text DEFAULT NULL,
  `corporate_address` text DEFAULT NULL,
  `contact_email` varchar(150) DEFAULT NULL,
  `contact_phone` varchar(20) DEFAULT NULL,
  `establishment_year` year(4) DEFAULT NULL,
  `financial_year` varchar(20) DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payroll`
--

CREATE TABLE `payroll` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `salary_month` varchar(20) NOT NULL,
  `pay_days` int(11) DEFAULT 0,
  `gross_salary` decimal(12,2) DEFAULT 0.00,
  `total_deductions` decimal(12,2) DEFAULT 0.00,
  `net_pay` decimal(12,2) DEFAULT 0.00,
  `pf_employee` decimal(12,2) DEFAULT 0.00,
  `pf_employer` decimal(12,2) DEFAULT 0.00,
  `esic_employee` decimal(12,2) DEFAULT 0.00,
  `esic_employer` decimal(12,2) DEFAULT 0.00,
  `professional_tax` decimal(12,2) DEFAULT 0.00,
  `income_tax` decimal(12,2) DEFAULT 0.00,
  `tds` decimal(12,2) DEFAULT 0.00,
  `labour_welfare_fund` decimal(12,2) DEFAULT 0.00,
  `salary_advance` decimal(12,2) DEFAULT 0.00,
  `festival_advance` decimal(12,2) DEFAULT 0.00,
  `medical_advance` decimal(12,2) DEFAULT 0.00,
  `tour_advance` decimal(12,2) DEFAULT 0.00,
  `processed_by` int(11) DEFAULT NULL,
  `processed_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `performance`
--

CREATE TABLE `performance` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `goal_kra` text DEFAULT NULL,
  `kpi_definition` text DEFAULT NULL,
  `self_appraisal` text DEFAULT NULL,
  `manager_appraisal` text DEFAULT NULL,
  `rating` varchar(20) DEFAULT NULL,
  `reviewer_comments` text DEFAULT NULL,
  `final_rating` varchar(20) DEFAULT NULL,
  `increment_recommendation` text DEFAULT NULL,
  `promotion_recommendation` text DEFAULT NULL,
  `incentive_reward` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(11) NOT NULL,
  `permission_key` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `role_name` varchar(50) NOT NULL,
  `role_slug` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role_name`, `role_slug`, `description`, `status`, `created_at`) VALUES
(1, 'Super Admin', 'super_admin', 'Full system access', 'active', '2026-01-15 10:48:54'),
(2, 'Admin', 'admin', 'Administrative access', 'active', '2026-01-15 10:48:54'),
(3, 'HR', 'hr', 'Human Resources access', 'active', '2026-01-15 10:48:54'),
(4, 'Manager', 'manager', 'Department Manager access', 'active', '2026-01-15 10:48:54'),
(5, 'Employee', 'employee', 'Employee self-service access', 'active', '2026-01-15 10:48:54'),
(6, 'Accounts', 'accounts', 'Accounts and finance access', 'active', '2026-01-15 10:48:54');

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--

CREATE TABLE `role_permissions` (
  `id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `salary_structure`
--

CREATE TABLE `salary_structure` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `ctc` decimal(12,2) DEFAULT 0.00,
  `gross` decimal(12,2) DEFAULT 0.00,
  `basic_salary` decimal(12,2) DEFAULT 0.00,
  `hra` decimal(12,2) DEFAULT 0.00,
  `special_allowance` decimal(12,2) DEFAULT 0.00,
  `conveyance_allowance` decimal(12,2) DEFAULT 0.00,
  `medical_allowance` decimal(12,2) DEFAULT 0.00,
  `da` decimal(12,2) DEFAULT 0.00,
  `vda` decimal(12,2) DEFAULT 0.00,
  `other_allowances` decimal(12,2) DEFAULT 0.00,
  `eligibility_bonus` enum('Yes','No') DEFAULT 'No',
  `eligibility_incentive` enum('Yes','No') DEFAULT 'No',
  `eligibility_gratuity` enum('Yes','No') DEFAULT 'No',
  `eligibility_pf` enum('Yes','No') DEFAULT 'Yes',
  `eligibility_esi` enum('Yes','No') DEFAULT 'No',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `separation`
--

CREATE TABLE `separation` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `resignation_date` date DEFAULT NULL,
  `last_working_day` date DEFAULT NULL,
  `exit_interview` text DEFAULT NULL,
  `no_dues` enum('Yes','No') DEFAULT 'No',
  `full_final_settlement` enum('Yes','No') DEFAULT 'No',
  `settlement_status` enum('Pending','Completed') DEFAULT 'Pending',
  `experience_relieving_letter` enum('Yes','No') DEFAULT 'No',
  `rejoin_eligibility` enum('Yes','No') DEFAULT 'No',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shifts`
--

CREATE TABLE `shifts` (
  `id` int(11) NOT NULL,
  `org_id` int(11) DEFAULT NULL,
  `shift_name` varchar(100) NOT NULL,
  `shift_start` time DEFAULT NULL,
  `shift_end` time DEFAULT NULL,
  `grace_minutes` int(11) DEFAULT 0,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `training`
--

CREATE TABLE `training` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `training_program_name` varchar(200) DEFAULT NULL,
  `training_type` enum('Internal','External') DEFAULT 'Internal',
  `trainer_name` varchar(150) DEFAULT NULL,
  `training_from` date DEFAULT NULL,
  `training_to` date DEFAULT NULL,
  `exam_result` varchar(100) DEFAULT NULL,
  `certificate` enum('Yes','No') DEFAULT 'No',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `travel_expenses`
--

CREATE TABLE `travel_expenses` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `travel_purpose` text DEFAULT NULL,
  `travel_type` enum('Local','TR','Outstation') DEFAULT 'Local',
  `advance_amount` decimal(12,2) DEFAULT 0.00,
  `expense_category` text DEFAULT NULL,
  `bills_upload` varchar(255) DEFAULT NULL,
  `approval_status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `reimbursement_amount` decimal(12,2) DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `org_id` int(11) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `role_id` int(11) NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `org_id`, `branch_id`, `employee_id`, `role_id`, `full_name`, `username`, `email`, `phone`, `password_hash`, `status`, `last_login`, `created_at`) VALUES
(1, 1, 1, 1, 1, 'Super Admin User', 'superadmin', 'superadmin@ssspl.com', NULL, '$2y$10$WRTXEFkUBCw0QaDmZOBQDerWM90wGO.Af5GrDKaVbQaz8k03flKkm', 'Active', NULL, '2026-01-13 04:48:25'),
(2, 1, 1, 2, 2, 'Admin User', 'admin', 'admin@ssspl.com', NULL, '$2y$10$WRTXEFkUBCw0QaDmZOBQDerWM90wGO.Af5GrDKaVbQaz8k03flKkm', 'Active', NULL, '2026-01-15 10:49:39'),
(3, 1, 1, 3, 3, 'HR Manager', 'hr', 'hr@ssspl.com', NULL, '$2y$10$WRTXEFkUBCw0QaDmZOBQDerWM90wGO.Af5GrDKaVbQaz8k03flKkm', 'Active', NULL, '2026-01-15 10:49:39'),
(4, 1, 1, 4, 4, 'Department Manager', 'manager', 'manager@ssspl.com', NULL, '$2y$10$WRTXEFkUBCw0QaDmZOBQDerWM90wGO.Af5GrDKaVbQaz8k03flKkm', 'Active', '2026-01-15 06:16:31', '2026-01-15 10:49:39'),
(5, 1, 1, 5, 5, 'John Doe', 'employee', 'employee@ssspl.com', NULL, '$2y$10$WRTXEFkUBCw0QaDmZOBQDerWM90wGO.Af5GrDKaVbQaz8k03flKkm', 'Active', '2026-01-15 09:50:43', '2026-01-15 10:49:39'),
(6, NULL, NULL, NULL, 6, 'Accounts Officer', 'accounts', 'accounts@ssspl.com', NULL, '$2y$10$WRTXEFkUBCw0QaDmZOBQDerWM90wGO.Af5GrDKaVbQaz8k03flKkm', 'Active', NULL, '2026-01-15 10:49:39');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assets`
--
ALTER TABLE `assets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employee_id` (`employee_id`,`attendance_date`);

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `branch_code` (`branch_code`),
  ADD KEY `org_id` (`org_id`);

--
-- Indexes for table `candidates`
--
ALTER TABLE `candidates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_id` (`job_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `department_code` (`department_code`),
  ADD KEY `org_id` (`org_id`);

--
-- Indexes for table `designations`
--
ALTER TABLE `designations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_designation_name` (`designation_name`),
  ADD KEY `org_id` (`org_id`);

--
-- Indexes for table `disciplinary`
--
ALTER TABLE `disciplinary`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employee_code` (`employee_code`),
  ADD UNIQUE KEY `aadhaar_number` (`aadhaar_number`),
  ADD UNIQUE KEY `pan_number` (`pan_number`),
  ADD KEY `org_id` (`org_id`),
  ADD KEY `branch_id` (`branch_id`),
  ADD KEY `shift_id` (`shift_id`),
  ADD KEY `reporting_manager_id` (`reporting_manager_id`),
  ADD KEY `idx_employee_status` (`status`),
  ADD KEY `idx_employee_department` (`department_id`),
  ADD KEY `idx_employee_designation` (`designation_id`),
  ADD KEY `idx_employee_doj` (`date_of_joining`);

--
-- Indexes for table `employee_bank_details`
--
ALTER TABLE `employee_bank_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `employee_documents`
--
ALTER TABLE `employee_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `employee_kyc`
--
ALTER TABLE `employee_kyc`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `aadhaar_number` (`aadhaar_number`),
  ADD UNIQUE KEY `pan_number` (`pan_number`),
  ADD UNIQUE KEY `passport_number` (`passport_number`),
  ADD UNIQUE KEY `driving_license_number` (`driving_license_number`),
  ADD UNIQUE KEY `uan_number` (`uan_number`),
  ADD UNIQUE KEY `pf_number` (`pf_number`),
  ADD UNIQUE KEY `esic_number` (`esic_number`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `employee_status_history`
--
ALTER TABLE `employee_status_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `grievance`
--
ALTER TABLE `grievance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `holidays`
--
ALTER TABLE `holidays`
  ADD PRIMARY KEY (`id`),
  ADD KEY `org_id` (`org_id`);

--
-- Indexes for table `leave_applications`
--
ALTER TABLE `leave_applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `leave_type_id` (`leave_type_id`);

--
-- Indexes for table `leave_balance`
--
ALTER TABLE `leave_balance`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employee_id` (`employee_id`,`leave_type_id`),
  ADD KEY `leave_type_id` (`leave_type_id`);

--
-- Indexes for table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_leave_employee_id` (`employee_id`);

--
-- Indexes for table `leave_types`
--
ALTER TABLE `leave_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `leave_name` (`leave_name`);

--
-- Indexes for table `manpower_requisition`
--
ALTER TABLE `manpower_requisition`
  ADD PRIMARY KEY (`id`),
  ADD KEY `org_id` (`org_id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `onboarding`
--
ALTER TABLE `onboarding`
  ADD PRIMARY KEY (`id`),
  ADD KEY `candidate_id` (`candidate_id`);

--
-- Indexes for table `organizations`
--
ALTER TABLE `organizations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `company_code` (`company_code`);

--
-- Indexes for table `payroll`
--
ALTER TABLE `payroll`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `processed_by` (`processed_by`);

--
-- Indexes for table `performance`
--
ALTER TABLE `performance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permission_key` (`permission_key`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `role_slug` (`role_slug`);

--
-- Indexes for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `role_id` (`role_id`,`permission_id`),
  ADD KEY `permission_id` (`permission_id`);

--
-- Indexes for table `salary_structure`
--
ALTER TABLE `salary_structure`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `separation`
--
ALTER TABLE `separation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `shifts`
--
ALTER TABLE `shifts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `org_id` (`org_id`);

--
-- Indexes for table `training`
--
ALTER TABLE `training`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `travel_expenses`
--
ALTER TABLE `travel_expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `org_id` (`org_id`),
  ADD KEY `branch_id` (`branch_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assets`
--
ALTER TABLE `assets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `candidates`
--
ALTER TABLE `candidates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `designations`
--
ALTER TABLE `designations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `disciplinary`
--
ALTER TABLE `disciplinary`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `employee_bank_details`
--
ALTER TABLE `employee_bank_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_documents`
--
ALTER TABLE `employee_documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_kyc`
--
ALTER TABLE `employee_kyc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_status_history`
--
ALTER TABLE `employee_status_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `grievance`
--
ALTER TABLE `grievance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `holidays`
--
ALTER TABLE `holidays`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leave_applications`
--
ALTER TABLE `leave_applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leave_balance`
--
ALTER TABLE `leave_balance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leave_requests`
--
ALTER TABLE `leave_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leave_types`
--
ALTER TABLE `leave_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `manpower_requisition`
--
ALTER TABLE `manpower_requisition`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `onboarding`
--
ALTER TABLE `onboarding`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `organizations`
--
ALTER TABLE `organizations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payroll`
--
ALTER TABLE `payroll`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `performance`
--
ALTER TABLE `performance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `role_permissions`
--
ALTER TABLE `role_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `salary_structure`
--
ALTER TABLE `salary_structure`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `separation`
--
ALTER TABLE `separation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shifts`
--
ALTER TABLE `shifts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `training`
--
ALTER TABLE `training`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `travel_expenses`
--
ALTER TABLE `travel_expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD CONSTRAINT `fk_leave_employee` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
