<?php
// required session values already come from header.php
?>

<div class="sidebar" id="sidebar">
    <!-- Toggle -->
    <div class="sidebar-toggle" id="sidebarToggle">
        <i class="fas fa-chevron-left"></i>
    </div>

    <!-- User Profile -->
    <div class="sidebar-user-profile">
        <div class="sidebar-user-avatar">
            <?php echo strtoupper(substr($full_name, 0, 1)); ?>
        </div>
        <div class="sidebar-user-info">
            <div class="sidebar-user-name"><?php echo htmlspecialchars($full_name); ?></div>
            <div class="sidebar-user-role"><?php echo htmlspecialchars($role_name); ?></div>
        </div>
    </div>

    <!-- Menu -->
    <nav class="sidebar-menu">
        <div class="menu-section">

            <a href="/dashboards/employee_dashboard.php"
               class="menu-item <?= basename($_SERVER['PHP_SELF'])=='employee_dashboard.php'?'active':'' ?>">
                <span class="menu-icon">📊</span>
                <span class="menu-text">Dashboard</span>
                <span class="menu-tooltip">Dashboard</span>
            </a>

            <a href="/modules/employees/employee_view.php?id=<?= $employee_id ?>"
               class="menu-item <?= basename($_SERVER['PHP_SELF'])=='employee_view.php'?'active':'' ?>">
                <span class="menu-icon">👤</span>
                <span class="menu-text">My Profile</span>
                <span class="menu-tooltip">Profile</span>
            </a>

            <a href="/modules/attendance/attendance_list.php"
               class="menu-item <?= strpos($_SERVER['PHP_SELF'],'attendance')!==false?'active':'' ?>">
                <span class="menu-icon">⏰</span>
                <span class="menu-text">Attendance</span>
                <span class="menu-tooltip">Attendance</span>
            </a>

            <a href="/modules/leave/leave_my_requests.php"
               class="menu-item <?= strpos($_SERVER['PHP_SELF'],'leave')!==false?'active':'' ?>">
                <span class="menu-icon">📅</span>
                <span class="menu-text">Leave</span>
                <span class="menu-tooltip">Leave</span>
            </a>

            <a href="/auth/logout.php" class="menu-item logout-item">
                <span class="menu-icon">🚪</span>
                <span class="menu-text">Logout</span>
            </a>

        </div>
    </nav>
</div>
