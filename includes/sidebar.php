<!-- Sidebar Navigation -->
<div class="sidebar" id="sidebar">
    <!-- Sidebar Toggle Button -->
    <div class="sidebar-toggle" id="sidebarToggle">
        <i class="fas fa-chevron-left"></i>
    </div>
    
    <!-- Brand Logo -->
    <div class="sidebar-header">
        <div class="brand-logo">
            <i class="fas fa-briefcase"></i>
        </div>
        <div class="brand-name">
            <h2>SSMS HRMS</h2>
            <p>HR Management</p>
        </div>
    </div>
    
    <!-- User Profile in Sidebar -->
    <div class="sidebar-user-profile">
        <div class="sidebar-user-avatar">
            <?php echo strtoupper(substr($full_name, 0, 1)); ?>
        </div>
        <div class="sidebar-user-info">
            <div class="sidebar-user-name"><?php echo htmlspecialchars($full_name); ?></div>
            <div class="sidebar-user-role"><?php echo htmlspecialchars($role_name); ?></div>
        </div>
    </div>
    
    <!-- Navigation Menu -->
    <nav class="sidebar-menu">
        <div class="menu-section">
            <a href="/dashboards/employee_dashboard.php" class="menu-item <?php echo (basename($_SERVER['PHP_SELF']) == 'employee_dashboard.php') ? 'active' : ''; ?>">
                <span class="menu-icon"><i class="fas fa-tachometer-alt"></i></span>
                <span class="menu-text">Dashboard</span>
                <span class="menu-tooltip">Dashboard</span>
            </a>
            
            <a href="/modules/employees/employee_view.php?id=<?php echo $employee_id; ?>" class="menu-item <?php echo (basename($_SERVER['PHP_SELF']) == 'employee_view.php') ? 'active' : ''; ?>">
                <span class="menu-icon"><i class="fas fa-user"></i></span>
                <span class="menu-text">My Profile</span>
                <span class="menu-tooltip">My Profile</span>
            </a>
            
            <a href="/modules/attendance/attendance_list.php" class="menu-item <?php echo (strpos($_SERVER['PHP_SELF'], 'attendance') !== false) ? 'active' : ''; ?>">
                <span class="menu-icon"><i class="fas fa-clock"></i></span>
                <span class="menu-text">Attendance</span>
                <span class="menu-tooltip">Attendance</span>
            </a>
            
            <a href="/modules/leave/leave_my_requests.php" class="menu-item <?php echo (strpos($_SERVER['PHP_SELF'], 'leave') !== false) ? 'active' : ''; ?>">
                <span class="menu-icon"><i class="fas fa-calendar-alt"></i></span>
                <span class="menu-text">Leave</span>
                <?php
                // Get pending leave count
                if (isset($db)) {
                    $stmt = $db->prepare("SELECT COUNT(*) as count FROM leave_requests WHERE employee_id = :emp_id AND status = 'Pending'");
                    $stmt->execute([':emp_id' => $employee_id]);
                    $pending = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;
                    if ($pending > 0) {
                        echo '<span class="menu-badge">' . $pending . '</span>';
                    }
                }
                ?>
                <span class="menu-tooltip">Leave Requests</span>
            </a>
            
            <a href="/modules/performance/self_appraisal.php" class="menu-item <?php echo (strpos($_SERVER['PHP_SELF'], 'performance') !== false) ? 'active' : ''; ?>">
                <span class="menu-icon"><i class="fas fa-chart-line"></i></span>
                <span class="menu-text">Performance</span>
                <span class="menu-tooltip">Performance</span>
            </a>
            
            <a href="/modules/training/training_list.php" class="menu-item <?php echo (strpos($_SERVER['PHP_SELF'], 'training') !== false) ? 'active' : ''; ?>">
                <span class="menu-icon"><i class="fas fa-graduation-cap"></i></span>
                <span class="menu-text">Training</span>
                <span class="menu-tooltip">Training</span>
            </a>
            
            <a href="/modules/payroll/payslip_download.php" class="menu-item <?php echo (strpos($_SERVER['PHP_SELF'], 'payroll') !== false || strpos($_SERVER['PHP_SELF'], 'payslip') !== false) ? 'active' : ''; ?>">
                <span class="menu-icon"><i class="fas fa-file-invoice-dollar"></i></span>
                <span class="menu-text">Payslips</span>
                <span class="menu-tooltip">Payslips</span>
            </a>
            
            <a href="/modules/expenses/expense_apply.php" class="menu-item <?php echo (strpos($_SERVER['PHP_SELF'], 'expense') !== false) ? 'active' : ''; ?>">
                <span class="menu-icon"><i class="fas fa-receipt"></i></span>
                <span class="menu-text">Expenses</span>
                <span class="menu-tooltip">Expenses</span>
            </a>
            
            <a href="/modules/grievance/grievance_register.php" class="menu-item <?php echo (strpos($_SERVER['PHP_SELF'], 'grievance') !== false) ? 'active' : ''; ?>">
                <span class="menu-icon"><i class="fas fa-comments"></i></span>
                <span class="menu-text">Grievance</span>
                <span class="menu-tooltip">Grievance</span>
            </a>

            <a href="/modules/settings/settings.php" class="menu-item <?php echo (strpos($_SERVER['PHP_SELF'], 'settings') !== false) ? 'active' : ''; ?>">
                <span class="menu-icon"><i class="fas fa-cog"></i></span>
                <span class="menu-text">Settings</span>
                <span class="menu-tooltip">Settings</span>
            </a>
        </div>
        
        <div class="menu-divider"></div>
        
        <div class="menu-section">
            <a href="/auth/logout.php" class="menu-item logout-item">
                <span class="menu-icon"><i class="fas fa-sign-out-alt"></i></span>
                <span class="menu-text">Logout</span>
                <span class="menu-tooltip">Logout</span>
            </a>
        </div>
    </nav>
</div>

















<!-- Sidebar styles are in /assets/css/dashboard.css -->
<!-- 
    .sidebar {
        width: 280px;
        background: white;
        border-right: 1px solid var(--border-color);
        display: flex;
        flex-direction: column;
        transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        height: 100vh;
        overflow-y: auto;
        overflow-x: hidden;
    }
    
    .sidebar.collapsed {
        width: 80px;
    }
    
    .sidebar-toggle {
        position: absolute;
        top: 20px;
        right: -15px;
        width: 30px;
        height: 30px;
        background: white;
        border: 2px solid var(--border-color);
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
        background: var(--primary-color);
        border-color: var(--primary-color);
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
    
    /* Brand Logo */
    .sidebar-header {
        padding: 25px 20px;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        gap: 15px;
        transition: all 0.3s ease;
    }
    
    .brand-logo {
        width: 45px;
        height: 45px;
        background: var(--primary-gradient);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 22px;
        flex-shrink: 0;
    }
    
    .brand-name {
        transition: all 0.3s ease;
    }
    
    .brand-name h2 {
        font-size: 18px;
        font-weight: 700;
        color: var(--text-dark);
        margin: 0;
    }
    
    .brand-name p {
        font-size: 12px;
        color: var(--text-gray);
        margin: 0;
    }
    
    .sidebar.collapsed .brand-name {
        opacity: 0;
        width: 0;
        overflow: hidden;
    }
    
    /* Sidebar User Profile */
    .sidebar-user-profile {
        padding: 20px;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        gap: 12px;
        transition: all 0.3s ease;
    }
    
    .sidebar-user-avatar {
        width: 45px;
        height: 45px;
        background: var(--primary-gradient);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        font-weight: bold;
        flex-shrink: 0;
    }
    
    .sidebar-user-info {
        transition: all 0.3s ease;
    }
    
    .sidebar-user-name {
        font-size: 14px;
        font-weight: 600;
        color: var(--text-dark);
    }
    
    .sidebar-user-role {
        font-size: 12px;
        color: var(--text-gray);
    }
    
    .sidebar.collapsed .sidebar-user-info {
        opacity: 0;
        width: 0;
        overflow: hidden;
    }
    
    .sidebar.collapsed .sidebar-user-profile {
        justify-content: center;
    }
    
    /* Sidebar Menu */
    .sidebar-menu {
        flex: 1;
        padding: 15px 0;
    }
    
    .menu-section {
        padding: 0 10px;
    }
    
    .menu-item {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 12px 15px;
        color: var(--text-gray);
        text-decoration: none;
        border-radius: 10px;
        margin-bottom: 5px;
        transition: all 0.3s ease;
        position: relative;
        white-space: nowrap;
    }
    
    .menu-item:hover {
        background: var(--bg-light);
        color: var(--text-dark);
        padding-left: 20px;
    }
    
    .menu-item.active {
        background: var(--primary-gradient);
        color: white;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }
    
    .menu-icon {
        font-size: 18px;
        width: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    
    .menu-text {
        font-size: 14px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .sidebar.collapsed .menu-text {
        opacity: 0;
        width: 0;
        overflow: hidden;
    }
    
    .menu-badge {
        background: var(--error);
        color: white;
        font-size: 10px;
        font-weight: 600;
        padding: 3px 7px;
        border-radius: 10px;
        margin-left: auto;
    }
    
    .sidebar.collapsed .menu-badge {
        display: none;
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
    }
    
    .menu-divider {
        height: 1px;
        background: var(--border-color);
        margin: 15px 20px;
    }
    
    .logout-item {
        color: var(--error) !important;
    }
    
    .logout-item:hover {
        background: #fee2e2 !important;
        color: var(--error) !important;
    }
    
    @media (max-width: 768px) {
        .sidebar {
            position: fixed;
            left: -280px;
            z-index: 1000;
        }
        
        .sidebar.mobile-open {
            left: 0;
        }
    }
--> 

<!-- Sidebar behaviour handled in /assets/js/app.js -->
<!-- 
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        
        if (sidebar && sidebarToggle) {
            // Load saved state
            const savedState = localStorage.getItem('sidebarState');
            if (savedState === 'collapsed') {
                sidebar.classList.add('collapsed');
            }
            
            // Toggle on click
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('collapsed');
                localStorage.setItem('sidebarState', 
                    sidebar.classList.contains('collapsed') ? 'collapsed' : 'expanded'
                );
            });
        }
    });
-->