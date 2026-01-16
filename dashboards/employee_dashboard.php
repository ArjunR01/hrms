<?php
// Include required files
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../core/session.php';

// Initialize session
Session::init();

// Check if user is logged in
if (!Session::isLoggedIn()) {
    header('Location: ../auth/login.php');
    exit();
}

// Check if user has employee role
if (Session::getUserRole() !== 'employee') {
    // Redirect to appropriate dashboard based on role
    $role = Session::getUserRole();
    $dashboard_map = [
        'super_admin' => 'super_admin_dashboard.php',
        'admin' => 'admin_dashboard.php',
        'hr' => 'hr_dashboard.php',
        'manager' => 'manager_dashboard.php'
    ];
    
    if (isset($dashboard_map[$role])) {
        header('Location: ' . $dashboard_map[$role]);
        exit();
    }
    
    // If role not found, logout
    header('Location: ../auth/logout.php');
    exit();
}

// Get user details from session
$user_id = Session::getUserId();
$employee_id = Session::getEmployeeId();
$full_name = Session::get('full_name', 'Employee');
$username = Session::get('username', 'employee@ssspl.com');
$email = Session::get('email', '');
$role = Session::getUserRole();
$role_name = Session::get('role_name', 'Employee');

// Database connection
$database = new Database();
$db = $database->getConnection();

// Get current date info
$current_date = date('l, d F Y');
$current_month = date('F Y');
$current_year = date('Y');
$current_month_num = date('m');

// Initialize stats with default values
$total_present = 0;
$leave_balance = 12;
$leave_taken = 0;
$tasks_completed = 5;
$avg_work_hours = '8.5h';
$attendance_percentage = 0;

// Fetch real attendance data for current month
try {
    // Use employee_id from session if available, otherwise use user_id
    $emp_id = $employee_id ?? $user_id;
    
    $stmt = $db->prepare("SELECT COUNT(*) as present_days FROM attendance 
                         WHERE employee_id = :emp_id 
                         AND MONTH(attendance_date) = :month 
                         AND YEAR(attendance_date) = :year 
                         AND status = 'Present'");
    $stmt->bindParam(':emp_id', $emp_id);
    $stmt->bindParam(':month', $current_month_num);
    $stmt->bindParam(':year', $current_year);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $total_present = $result['present_days'] ?? 0;
    
    // Calculate attendance percentage
    $total_working_days = date('j'); // Days passed in current month
    $attendance_percentage = $total_working_days > 0 ? round(($total_present / $total_working_days) * 100, 1) : 0;
} catch(PDOException $e) {
    // Keep default values if query fails
    error_log("Attendance query error: " . $e->getMessage());
}

// Fetch leave balance
try {
    $emp_id = $employee_id ?? $user_id;
    $stmt = $db->prepare("SELECT * FROM leave_balance WHERE employee_id = :emp_id LIMIT 1");
    $stmt->bindParam(':emp_id', $emp_id);
    $stmt->execute();
    $leave_data = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($leave_data) {
        $leave_balance = $leave_data['available'] ?? 12;
    }
} catch(PDOException $e) {
    // Keep default value
    error_log("Leave balance query error: " . $e->getMessage());
}

// Count approved leaves this year
try {
    $stmt = $db->prepare("SELECT COUNT(*) as leave_count FROM leave_applications 
                         WHERE employee_id = :user_id 
                         AND YEAR(from_date) = :year 
                         AND status = 'approved'");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':year', $current_year);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $leave_taken = $result['leave_count'] ?? 0;
} catch(PDOException $e) {
    // Keep default value
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard - SSSMS HRMS</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
            color: #333;
        }
        
        /* Header */
        .header {
            background: #2d2d2d;
            color: white;
            padding: 0 30px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .header-left {
            display: flex;
            align-items: center;
            gap: 30px;
        }
        
        .logo-section {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .logo-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #d946a6 0%, #b91c8c 100%);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 20px;
        }
        
        .logo-text h1 {
            font-size: 18px;
            font-weight: 600;
        }
        
        .logo-text p {
            font-size: 11px;
            opacity: 0.8;
            margin-top: -2px;
        }
        
        .search-bar {
            display: flex;
            align-items: center;
            background: rgba(255,255,255,0.1);
            padding: 8px 15px;
            border-radius: 8px;
            min-width: 300px;
        }
        
        .search-bar input {
            background: none;
            border: none;
            color: white;
            outline: none;
            width: 100%;
            font-size: 14px;
        }
        
        .search-bar input::placeholder {
            color: rgba(255,255,255,0.6);
        }
        
        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .notification-icon {
            position: relative;
            cursor: pointer;
            font-size: 20px;
        }
        
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #d946a6;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            font-weight: bold;
        }
        
        /* Notification Dropdown Panel */
        .notification-dropdown {
            position: absolute;
            top: calc(100% + 10px);
            right: 80px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            width: 380px;
            max-height: 500px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1000;
            border: 1px solid #e5e7eb;
            overflow: hidden;
        }
        
        .notification-dropdown.active {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        
        .notification-header {
            padding: 16px 20px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .notification-header h3 {
            margin: 0;
            font-size: 16px;
            font-weight: 700;
            color: #111827;
        }
        
        .notification-header .badge {
            background: #667eea;
            color: white;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .notification-list {
            max-height: 400px;
            overflow-y: auto;
        }
        
        .notification-item {
            padding: 16px 20px;
            border-bottom: 1px solid #f3f4f6;
            cursor: pointer;
            transition: background 0.2s ease;
            display: flex;
            gap: 12px;
        }
        
        .notification-item:hover {
            background: #f9fafb;
        }
        
        .notification-item.unread {
            background: #eff6ff;
        }
        
        .notification-item.unread:hover {
            background: #dbeafe;
        }
        
        .notification-icon-wrapper {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }
        
        .notification-icon-wrapper.info {
            background: #dbeafe;
            color: #3b82f6;
        }
        
        .notification-icon-wrapper.success {
            background: #dcfce7;
            color: #22c55e;
        }
        
        .notification-icon-wrapper.warning {
            background: #fef3c7;
            color: #f59e0b;
        }
        
        .notification-content {
            flex: 1;
        }
        
        .notification-title {
            font-size: 14px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 4px;
        }
        
        .notification-message {
            font-size: 13px;
            color: #6b7280;
            line-height: 1.4;
        }
        
        .notification-time {
            font-size: 12px;
            color: #9ca3af;
            margin-top: 4px;
        }
        
        .notification-footer {
            padding: 12px 20px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
        }
        
        .notification-footer a {
            color: #667eea;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
        }
        
        .notification-footer a:hover {
            text-decoration: underline;
        }
        
        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
        }
        
        .user-avatar {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #7c3aed 0%, #5b21b6 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 14px;
        }
        
        .user-info {
            text-align: left;
        }
        
        .user-name {
            font-size: 14px;
            font-weight: 600;
        }
        
        .user-role {
            font-size: 11px;
            opacity: 0.7;
            text-transform: capitalize;
        }
        
        /* User Dropdown Menu */
        .user-dropdown {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            min-width: 220px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1000;
            border: 1px solid #e5e7eb;
        }
        
        .user-dropdown.active {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        
        .dropdown-header {
            padding: 16px;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .dropdown-title {
            font-size: 12px;
            font-weight: 700;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .dropdown-menu {
            padding: 8px;
        }
        
        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            border-radius: 8px;
            text-decoration: none;
            color: #374151;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        
        .dropdown-item:hover {
            background: #f3f4f6;
            color: #111827;
        }
        
        .dropdown-item i {
            font-size: 16px;
            width: 20px;
            text-align: center;
        }
        
        .dropdown-item.logout {
            color: #dc2626;
            border-top: 1px solid #e5e7eb;
            margin-top: 4px;
        }
        
        .dropdown-item.logout:hover {
            background: #fee2e2;
        }
        
        .user-profile {
            position: relative;
        }
        
        .user-profile:hover {
            background: rgba(255,255,255,0.1);
            border-radius: 8px;
            padding: 8px;
        }
        
        /* Main Layout */
        .main-container {
            display: flex;
            min-height: calc(100vh - 60px);
        }
        
        /* Sidebar */
        .sidebar {
            width: 280px;
            background: white;
            padding: 20px 0;
            box-shadow: 2px 0 8px rgba(0,0,0,0.05);
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }
        
        .sidebar.collapsed {
            width: 80px;
        }
        
        .sidebar.collapsed .menu-item span:not(.menu-icon):not(.menu-tooltip) {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }
        
        .sidebar.collapsed .menu-badge {
            display: none;
        }
        
        .sidebar.collapsed .sidebar-user-profile {
            padding: 15px 10px;
        }
        
        .sidebar.collapsed .sidebar-user-info {
            display: none;
        }
        
        /* Sidebar Toggle Button */
        .sidebar-toggle {
            position: absolute;
            top: 20px;
            right: -15px;
            width: 30px;
            height: 30px;
            background: white;
            border: 2px solid #e5e7eb;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            z-index: 100;
        }
        
        .sidebar-toggle:hover {
            background: #667eea;
            border-color: #667eea;
            color: white;
            transform: scale(1.1);
        }
        
        .sidebar-toggle i {
            font-size: 14px;
            transition: transform 0.3s ease;
        }
        
        .sidebar.collapsed .sidebar-toggle i {
            transform: rotate(180deg);
        }
        
        .menu-section {
            margin-bottom: 10px;
        }
        
        .menu-item {
            padding: 12px 30px;
            display: flex;
            align-items: center;
            gap: 12px;
            color: #666;
            text-decoration: none;
            transition: all 0.2s;
            font-size: 14px;
        }
        
        .menu-item:hover {
            background: #f9f9f9;
            color: #d946a6;
        }
        
        .menu-item.active {
            background: linear-gradient(90deg, rgba(217,70,166,0.1) 0%, rgba(255,255,255,0) 100%);
            color: #d946a6;
            border-left: 3px solid #d946a6;
            font-weight: 600;
        }
        
        .menu-icon {
            font-size: 18px;
            width: 20px;
            min-width: 20px;
            text-align: center;
        }
        
        /* Tooltip for collapsed sidebar */
        .menu-tooltip {
            position: absolute;
            left: 70px;
            background: #1f2937;
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 13px;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            pointer-events: none;
            z-index: 1000;
            display: none;
        }
        
        .menu-tooltip::before {
            content: '';
            position: absolute;
            left: -4px;
            top: 50%;
            transform: translateY(-50%);
            border: 5px solid transparent;
            border-right-color: #1f2937;
        }
        
        .sidebar.collapsed .menu-item:hover .menu-tooltip {
            opacity: 1;
            visibility: visible;
            display: block;
            text-align: center;
        }
        
        .menu-badge {
            margin-left: auto;
            background: #d946a6;
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
        }
        
        /* Content Area */
        .content {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
        }
        
        .page-header {
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .page-title-section h1 {
            font-size: 32px;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 5px;
        }
        
        .page-title-section h1 span {
            background: linear-gradient(135deg, #d946a6 0%, #7c3aed 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .page-subtitle {
            color: #666;
            font-size: 14px;
        }
        
        .date-section {
            text-align: right;
        }
        
        .current-date {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }
        
        .calendar-icon-btn {
            background: linear-gradient(135deg, #d946a6 0%, #b91c8c 100%);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .stat-content {
            flex: 1;
        }
        
        .stat-label {
            font-size: 14px;
            color: #666;
            margin-bottom: 8px;
        }
        
        .stat-value {
            font-size: 36px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 8px;
        }
        
        .stat-trend {
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .stat-trend.up {
            color: #10b981;
        }
        
        .stat-trend.down {
            color: #ef4444;
        }
        
        .stat-icon-wrapper {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
        }
        
        .stat-card:nth-child(1) .stat-icon-wrapper {
            background: linear-gradient(135deg, rgba(217,70,166,0.1) 0%, rgba(217,70,166,0.2) 100%);
        }
        
        .stat-card:nth-child(2) .stat-icon-wrapper {
            background: linear-gradient(135deg, rgba(124,58,237,0.1) 0%, rgba(124,58,237,0.2) 100%);
        }
        
        .stat-card:nth-child(3) .stat-icon-wrapper {
            background: linear-gradient(135deg, rgba(236,72,153,0.1) 0%, rgba(236,72,153,0.2) 100%);
        }
        
        .stat-card:nth-child(4) .stat-icon-wrapper {
            background: linear-gradient(135deg, rgba(139,92,246,0.1) 0%, rgba(139,92,246,0.2) 100%);
        }
        
        /* Charts Section */
        .charts-section {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .chart-card {
            background: white;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        
        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .chart-title {
            font-size: 18px;
            font-weight: 600;
            color: #1a1a1a;
        }
        
        .chart-subtitle {
            font-size: 13px;
            color: #666;
            margin-top: 2px;
        }
        
        .view-details-btn {
            background: none;
            border: none;
            color: #d946a6;
            font-size: 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .chart-placeholder {
            height: 300px;
            background: linear-gradient(180deg, rgba(217,70,166,0.05) 0%, rgba(124,58,237,0.05) 100%);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #999;
        }
        
        .donut-chart {
            height: 300px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        
        .donut-placeholder {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background: conic-gradient(
                #d946a6 0deg 120deg,
                #ec4899 120deg 210deg,
                #7c3aed 210deg 300deg,
                #a855f7 300deg 360deg
            );
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .donut-center {
            width: 120px;
            height: 120px;
            background: white;
            border-radius: 50%;
        }
        
        .welcome-message {
            background: linear-gradient(135deg, #d946a6 0%, #7c3aed 100%);
            color: white;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            margin-top: 15px;
        }
        
        .welcome-message h3 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .welcome-message p {
            font-size: 13px;
            opacity: 0.9;
        }
        
        /* User Info Bottom */
        .user-info-bottom {
            position: absolute;
            bottom: 20px;
            left: 0;
            right: 0;
            padding: 0 20px;
        }
        
        .user-card {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .user-card .user-avatar {
            width: 44px;
            height: 44px;
            font-size: 16px;
        }
        
        .user-card .user-info .user-name {
            font-size: 14px;
            color: #1a1a1a;
        }
        
        .user-card .user-info .user-role {
            font-size: 12px;
            color: #666;
        }
        
        /* Responsive */
        @media (max-width: 1400px) {
            .charts-section {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 1024px) {
            .sidebar {
                width: 240px;
            }
            
            .search-bar {
                min-width: 200px;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 768px) {
            .main-container {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
            }
            
            .header-left {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            
            .search-bar {
                display: none;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="header-left">
            <div class="logo-section">
                <div class="logo-icon">S</div>
                <div class="logo-text">
                    <h1>SSSMS</h1>
                    <p>HR Management</p>
                </div>
            </div>
            
            <div class="search-bar">
                <span>🔍</span>
                <input type="text" placeholder="Search employees, reports...">
            </div>
        </div>
        
        <div class="header-right">
            <div class="notification-icon" id="notificationBtn">
                🔔
                <span class="notification-badge" id="notificationCount">3</span>
                
                <!-- Notification Dropdown Panel -->
                <div class="notification-dropdown" id="notificationDropdown">
                    <div class="notification-header">
                        <h3>Notifications</h3>
                        <span class="badge">3 New</span>
                    </div>
                    <div class="notification-list">
                        <div class="notification-item unread">
                            <div class="notification-icon-wrapper warning">
                                ⏰
                            </div>
                            <div class="notification-content">
                                <div class="notification-title">Late Arrival Alert</div>
                                <div class="notification-message">You were marked late today at 10:15 AM</div>
                                <div class="notification-time">2 hours ago</div>
                            </div>
                        </div>
                        <div class="notification-item unread">
                            <div class="notification-icon-wrapper success">
                                ✓
                            </div>
                            <div class="notification-content">
                                <div class="notification-title">Leave Approved</div>
                                <div class="notification-message">Your leave request for Jan 25-27 has been approved</div>
                                <div class="notification-time">5 hours ago</div>
                            </div>
                        </div>
                        <div class="notification-item unread">
                            <div class="notification-icon-wrapper info">
                                💰
                            </div>
                            <div class="notification-content">
                                <div class="notification-title">Payroll Processed</div>
                                <div class="notification-message">Your salary for January has been processed</div>
                                <div class="notification-time">1 day ago</div>
                            </div>
                        </div>
                        <div class="notification-item">
                            <div class="notification-icon-wrapper info">
                                🎓
                            </div>
                            <div class="notification-content">
                                <div class="notification-title">Training Reminder</div>
                                <div class="notification-message">Leadership Training scheduled for tomorrow</div>
                                <div class="notification-time">2 days ago</div>
                            </div>
                        </div>
                        <div class="notification-item">
                            <div class="notification-icon-wrapper success">
                                📊
                            </div>
                            <div class="notification-content">
                                <div class="notification-title">Performance Review</div>
                                <div class="notification-message">Your Q4 performance review is ready</div>
                                <div class="notification-time">3 days ago</div>
                            </div>
                        </div>
                    </div>
                    <div class="notification-footer">
                        <a href="../modules/notifications/all_notifications.php">View All Notifications</a>
                    </div>
                </div>
            </div>
            
            <span>❓</span>
            
            <div class="user-profile" id="userProfileBtn">
                <div class="user-avatar"><?php echo strtoupper(substr($full_name, 0, 1)); ?></div>
                <div class="user-info">
                    <div class="user-name"><?php echo htmlspecialchars($full_name); ?></div>
                    <div class="user-role"><?php echo htmlspecialchars($role_name); ?></div>
                </div>
                
                <!-- Dropdown Menu -->
                <div class="user-dropdown" id="userDropdown">
                    <div class="dropdown-header">
                        <div class="dropdown-title">My Account</div>
                    </div>
                    <div class="dropdown-menu">
                        <a href="../modules/employees/employee_view.php?id=<?php echo $employee_id; ?>" class="dropdown-item">
                            <i class="fas fa-user"></i>
                            <span>Profile</span>
                        </a>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-cog"></i>
                            <span>Settings</span>
                        </a>
                        <a href="../auth/logout.php" class="dropdown-item logout">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Main Container -->
    <div class="main-container">
        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <!-- Sidebar Toggle Button -->
            <div class="sidebar-toggle" id="sidebarToggle">
                <i class="fas fa-chevron-left"></i>
            </div>
            
            <!-- User Profile Section in Sidebar -->
            <div class="sidebar-user-profile" style="padding: 20px; border-bottom: 1px solid #e5e7eb; background: #f9fafb; transition: all 0.3s ease;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 20px; font-weight: bold; flex-shrink: 0;">
                        <?php echo strtoupper(substr($full_name, 0, 1)); ?>
                    </div>
                    <div class="sidebar-user-info" style="flex: 1; transition: all 0.3s ease;">
                        <div style="font-weight: 600; color: #111827; font-size: 14px;"><?php echo htmlspecialchars($full_name); ?></div>
                        <div style="font-size: 12px; color: #6b7280;"><?php echo htmlspecialchars($role_name); ?></div>
                    </div>
                </div>
            </div>
            
            <div class="menu-section">
                <a href="employee_dashboard.php" class="menu-item active">
                    <span class="menu-icon">📊</span>
                    <span>Dashboard</span>
                    <span class="menu-tooltip">Dashboard</span>
                </a>
                
                <a href="../modules/employees/employee_view.php?id=<?php echo $employee_id; ?>" class="menu-item">
                    <span class="menu-icon">👤</span>
                    <span>My Profile</span>
                    <span class="menu-tooltip">My Profile</span>
                </a>
                
                <a href="../modules/attendance/attendance_list.php" class="menu-item">
                    <span class="menu-icon">⏰</span>
                    <span>Attendance</span>
                    <span class="menu-tooltip">Attendance</span>
                </a>
                
                <a href="../modules/leave/leave_my_requests.php" class="menu-item">
                    <span class="menu-icon">📅</span>
                    <span>Leave</span>
                    <span class="menu-badge">3</span>
                    <span class="menu-tooltip">Leave Requests</span>
                </a>
                
                <a href="../modules/performance/self_appraisal.php" class="menu-item">
                    <span class="menu-icon">🎯</span>
                    <span>Performance</span>
                    <span class="menu-tooltip">Performance</span>
                </a>
                
                <a href="../modules/training/training_list.php" class="menu-item">
                    <span class="menu-icon">🎓</span>
                    <span>Training</span>
                    <span class="menu-tooltip">Training</span>
                </a>
                
                <a href="../modules/payroll/payslip_download.php" class="menu-item">
                    <span class="menu-icon">💼</span>
                    <span>Payslips</span>
                    <span class="menu-tooltip">Payslips</span>
                </a>
                
                <a href="../modules/expenses/expense_apply.php" class="menu-item">
                    <span class="menu-icon">✈️</span>
                    <span>Expenses</span>
                    <span class="menu-tooltip">Expenses</span>
                </a>
                
                <a href="../modules/grievance/grievance_register.php" class="menu-item">
                    <span class="menu-icon">💬</span>
                    <span>Grievance</span>
                    <span class="menu-tooltip">Grievance</span>
                </a>
                
                <div style="margin: 20px 0; height: 1px; background: #e5e7eb;"></div>
                
                <a href="../auth/logout.php" class="menu-item" style="color: #dc2626;">
                    <span class="menu-icon">🚪</span>
                    <span><strong>Logout</strong></span>
                    <span class="menu-tooltip">Logout</span>
                </a>
            </div>
            
            <div style="padding: 20px; margin-top: 20px;">
                <div class="user-card">
                    <div class="user-avatar"><?php echo strtoupper(substr($full_name, 0, 1)); ?></div>
                    <div class="user-info">
                        <div class="user-name"><?php echo htmlspecialchars(explode(' ', $full_name)[0]); ?></div>
                        <div class="user-role"><?php echo htmlspecialchars($role); ?></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Content Area -->
        <div class="content">
            <div class="page-header">
                <div class="page-title-section">
                    <h1>Welcome back, <span><?php echo htmlspecialchars(explode(' ', $full_name)[0]); ?></span>!</h1>
                    <p class="page-subtitle">Here's what's happening in your organization today.</p>
                </div>
                
                <div class="date-section">
                    <div class="current-date"><?php echo $current_date; ?></div>
                    <button class="calendar-icon-btn">
                        📅 Calendar
                    </button>
                </div>
            </div>
            
            <!-- Stats Grid -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-content">
                        <div class="stat-label">Present Today</div>
                        <div class="stat-value"><?php echo $total_present; ?></div>
                        <div class="stat-trend up">
                            ↑ <?php echo $attendance_percentage; ?>%
                        </div>
                    </div>
                    <div class="stat-icon-wrapper">
                        👥
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-content">
                        <div class="stat-label">On Leave</div>
                        <div class="stat-value"><?php echo $leave_taken; ?></div>
                        <div class="stat-trend">
                            5.6%
                        </div>
                    </div>
                    <div class="stat-icon-wrapper">
                        🏖️
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-content">
                        <div class="stat-label">Leave Balance</div>
                        <div class="stat-value"><?php echo $leave_balance; ?></div>
                        <div class="stat-trend down">
                            ↓ 1.2%
                        </div>
                    </div>
                    <div class="stat-icon-wrapper">
                        ⏰
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-content">
                        <div class="stat-label">Avg Work Hours</div>
                        <div class="stat-value"><?php echo $avg_work_hours; ?></div>
                        <div class="stat-trend up">
                            ↑ 2.1%
                        </div>
                    </div>
                    <div class="stat-icon-wrapper">
                        📊
                    </div>
                </div>
            </div>
            
            <!-- Charts Section -->
            <div class="charts-section">
                <div class="chart-card">
                    <div class="chart-header">
                        <div>
                            <div class="chart-title">Attendance Trends</div>
                            <div class="chart-subtitle">Monthly attendance overview</div>
                        </div>
                        <button class="view-details-btn">
                            View Details →
                        </button>
                    </div>
                    <div class="chart-placeholder">
                        📈 Attendance chart will be displayed here
                    </div>
                </div>
                
                <div class="chart-card">
                    <div class="chart-header">
                        <div>
                            <div class="chart-title">Departments</div>
                            <div class="chart-subtitle">Employee distribution</div>
                        </div>
                    </div>
                    <div class="donut-chart">
                        <div class="donut-placeholder">
                            <div class="donut-center"></div>
                        </div>
                    </div>
                    <div class="welcome-message">
                        <h3>Welcome back!</h3>
                        <p>You have successfully logged in.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <script>
        // User Profile Dropdown Toggle
        const userProfileBtn = document.getElementById('userProfileBtn');
        const userDropdown = document.getElementById('userDropdown');
        
        if (userProfileBtn && userDropdown) {
            userProfileBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                userDropdown.classList.toggle('active');
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!userProfileBtn.contains(e.target)) {
                    userDropdown.classList.remove('active');
                }
            });
            
            // Close dropdown on ESC key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    userDropdown.classList.remove('active');
                }
            });
        }
        
        // Sidebar Toggle Functionality
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        
        if (sidebar && sidebarToggle) {
            // Load sidebar state from localStorage
            const sidebarState = localStorage.getItem('sidebarState');
            if (sidebarState === 'collapsed') {
                sidebar.classList.add('collapsed');
            }
            
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('collapsed');
                
                // Save state to localStorage
                if (sidebar.classList.contains('collapsed')) {
                    localStorage.setItem('sidebarState', 'collapsed');
                } else {
                    localStorage.setItem('sidebarState', 'expanded');
                }
            });
        }
        
        // Notification Dropdown Toggle
        const notificationBtn = document.getElementById('notificationBtn');
        const notificationDropdown = document.getElementById('notificationDropdown');
        
        if (notificationBtn && notificationDropdown) {
            notificationBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                notificationDropdown.classList.toggle('active');
                // Close user dropdown if open
                if (userDropdown) {
                    userDropdown.classList.remove('active');
                }
            });
            
            // Close notification dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!notificationBtn.contains(e.target) && !notificationDropdown.contains(e.target)) {
                    notificationDropdown.classList.remove('active');
                }
            });
            
            // Mark notification as read when clicked
            const notificationItems = document.querySelectorAll('.notification-item');
            notificationItems.forEach(item => {
                item.addEventListener('click', function() {
                    this.classList.remove('unread');
                    // Update badge count
                    const unreadCount = document.querySelectorAll('.notification-item.unread').length;
                    const badge = document.getElementById('notificationCount');
                    if (badge) {
                        badge.textContent = unreadCount;
                        if (unreadCount === 0) {
                            badge.style.display = 'none';
                        }
                    }
                });
            });
        }
        
        // Dashboard loaded
        console.log('Dashboard with notifications, dropdown and collapsible sidebar loaded successfully');
    </script>
</body>
</html>