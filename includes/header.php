<?php
/**
 * Reusable Header Component
 * Include this at the top of every page
 */

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: /auth/login.php');
    exit();
}

// Get user details
$user_id = $_SESSION['user_id'] ?? 0;
$full_name = $_SESSION['full_name'] ?? 'User';
$role_name = $_SESSION['role_name'] ?? 'Employee';
$email = $_SESSION['email'] ?? '';
$employee_id = $_SESSION['employee_id'] ?? 0;

// Set default page title if not set
$page_title = $page_title ?? 'SSMS HRMS';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?> - SSMS HRMS</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/assets/images/favicon.png">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="/assets/css/datatables.min.css">
    
    <!-- Select2 CSS -->
    <link rel="stylesheet" href="/assets/css/select2.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/dashboard.css">
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --bg-light: #f8f9fc;
            --text-dark: #111827;
            --text-gray: #6b7280;
            --border-color: #e5e7eb;
            --success: #22c55e;
            --warning: #f59e0b;
            --error: #ef4444;
            --info: #3b82f6;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: var(--bg-light);
            color: var(--text-dark);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        .dashboard-wrapper {
            display: flex;
            min-height: 100vh;
        }
        
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        
        /* Top Navbar */
        .top-navbar {
            background: white;
            border-bottom: 1px solid var(--border-color);
            padding: 15px 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 4px rgba(0,0,0,0.04);
        }
        
        .navbar-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .page-title {
            font-size: 24px;
            font-weight: 700;
            color: var(--text-dark);
            margin: 0;
        }
        
        .search-bar {
            position: relative;
            width: 400px;
        }
        
        .search-bar input {
            width: 100%;
            padding: 10px 15px 10px 40px;
            border: 2px solid var(--border-color);
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        
        .search-bar input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .search-bar i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-gray);
        }
        
        .navbar-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .notification-icon {
            position: relative;
            cursor: pointer;
            font-size: 20px;
            padding: 8px;
            border-radius: 8px;
            transition: background 0.3s ease;
            color: var(--text-gray);
        }
        
        .notification-icon:hover {
            background: var(--bg-light);
        }
        
        .notification-badge {
            position: absolute;
            top: 2px;
            right: 2px;
            background: var(--error);
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
        
        .user-profile {
            position: relative;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 12px;
            border-radius: 10px;
            transition: background 0.3s ease;
            cursor: pointer;
        }
        
        .user-profile:hover {
            background: var(--bg-light);
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            background: var(--primary-gradient);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            font-weight: bold;
        }
        
        .user-info {
            display: flex;
            flex-direction: column;
        }
        
        .user-name {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-dark);
        }
        
        .user-role {
            font-size: 12px;
            color: var(--text-gray);
        }
        
        /* User Dropdown */
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
            border: 1px solid var(--border-color);
        }
        
        .user-dropdown.active {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        
        .dropdown-header {
            padding: 16px;
            border-bottom: 1px solid var(--border-color);
        }
        
        .dropdown-title {
            font-size: 12px;
            font-weight: 700;
            color: var(--text-gray);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .dropdown-menu-list {
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
            background: var(--bg-light);
            color: var(--text-dark);
        }
        
        .dropdown-item i {
            font-size: 16px;
            width: 20px;
            text-align: center;
        }
        
        .dropdown-item.logout {
            color: var(--error);
            border-top: 1px solid var(--border-color);
            margin-top: 4px;
        }
        
        .dropdown-item.logout:hover {
            background: #fee2e2;
        }
        
        /* Notification Dropdown */
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
            border: 1px solid var(--border-color);
            overflow: hidden;
        }
        
        .notification-dropdown.active {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        
        .notification-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .notification-header h3 {
            margin: 0;
            font-size: 16px;
            font-weight: 700;
            color: var(--text-dark);
        }
        
        .notification-header .badge {
            background: var(--primary-color);
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
            color: var(--info);
        }
        
        .notification-icon-wrapper.success {
            background: #dcfce7;
            color: var(--success);
        }
        
        .notification-icon-wrapper.warning {
            background: #fef3c7;
            color: var(--warning);
        }
        
        .notification-content {
            flex: 1;
        }
        
        .notification-title {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 4px;
        }
        
        .notification-message {
            font-size: 13px;
            color: var(--text-gray);
            line-height: 1.4;
        }
        
        .notification-time {
            font-size: 12px;
            color: #9ca3af;
            margin-top: 4px;
        }
        
        .notification-footer {
            padding: 12px 20px;
            border-top: 1px solid var(--border-color);
            text-align: center;
        }
        
        .notification-footer a {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
        }
        
        @media (max-width: 768px) {
            .search-bar {
                display: none;
            }
            
            .page-title {
                font-size: 18px;
            }
            
            .user-info {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="app-wrapper">
