<!-- Top Navbar -->
<div class="top-navbar">
    <div class="navbar-left">
        <h1 class="page-title"><?php echo htmlspecialchars($page_title ?? 'Dashboard'); ?></h1>

        <div class="search-bar">
            <i class="fas fa-search"></i>
            <form action="/modules/search/search.php" method="GET">
                <input type="text" name="q" placeholder="Search..." value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>" />
            </form>
        </div>
    </div>

    <div class="navbar-right">
        <!-- Notifications -->
        <div class="notification-icon" id="notificationBtn" title="Notifications">
            <i class="fas fa-bell"></i>
            <span class="notification-badge" id="notificationCount">3</span>

            <div class="notification-dropdown" id="notificationDropdown">
                <div class="notification-header">
                    <h3>Notifications</h3>
                    <span class="badge">3 New</span>
                </div>
                <div class="notification-list">
                    <div class="notification-item unread">
                        <div class="notification-icon-wrapper warning"><i class="fas fa-clock"></i></div>
                        <div class="notification-content">
                            <div class="notification-title">Late Arrival Alert</div>
                            <div class="notification-message">You were marked late today at 10:15 AM</div>
                            <div class="notification-time">2 hours ago</div>
                        </div>
                    </div>
                    <div class="notification-item unread">
                        <div class="notification-icon-wrapper success"><i class="fas fa-check"></i></div>
                        <div class="notification-content">
                            <div class="notification-title">Leave Approved</div>
                            <div class="notification-message">Your leave request has been approved</div>
                            <div class="notification-time">5 hours ago</div>
                        </div>
                    </div>
                    <div class="notification-item unread">
                        <div class="notification-icon-wrapper info"><i class="fas fa-dollar-sign"></i></div>
                        <div class="notification-content">
                            <div class="notification-title">Payroll Processed</div>
                            <div class="notification-message">Your salary has been processed</div>
                            <div class="notification-time">1 day ago</div>
                        </div>
                    </div>
                </div>
                <div class="notification-footer">
                    <a href="/modules/notifications/all_notifications.php">View All Notifications</a>
                </div>
            </div>
        </div>

        <!-- User -->
        <div class="user-profile" id="userProfileBtn">
            <div class="user-avatar"><?php echo strtoupper(substr($full_name, 0, 1)); ?></div>
            <div class="user-info">
                <div class="user-name"><?php echo htmlspecialchars($full_name); ?></div>
                <div class="user-role"><?php echo htmlspecialchars($role_name); ?></div>
            </div>

            <div class="user-dropdown" id="userDropdown">
                <div class="dropdown-header"><div class="dropdown-title">My Account</div></div>
                <div class="dropdown-menu-list">
                    <a href="/modules/employees/employee_view.php?id=<?php echo (int)$employee_id; ?>" class="dropdown-item">
                        <i class="fas fa-user"></i><span>Profile</span>
                    </a>
                    <a href="/modules/settings/settings.php" class="dropdown-item">
                        <i class="fas fa-cog"></i><span>Settings</span>
                    </a>
                    <a href="/auth/logout.php" class="dropdown-item logout">
                        <i class="fas fa-sign-out-alt"></i><span>Logout</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Dropdown behaviour handled in /assets/js/app.js -->
<!-- 
    const userProfileBtn = document.getElementById('userProfileBtn');
    const userDropdown = document.getElementById('userDropdown');
    const notificationBtn = document.getElementById('notificationBtn');
    const notificationDropdown = document.getElementById('notificationDropdown');

    if (userProfileBtn && userDropdown) {
        userProfileBtn.addEventListener('click', function(e){
            e.stopPropagation();
            userDropdown.classList.toggle('active');
            if (notificationDropdown) notificationDropdown.classList.remove('active');
        });
    }

    if (notificationBtn && notificationDropdown) {
        notificationBtn.addEventListener('click', function(e){
            e.stopPropagation();
            notificationDropdown.classList.toggle('active');
            if (userDropdown) userDropdown.classList.remove('active');
        });

        document.querySelectorAll('.notification-item').forEach(item => {
            item.addEventListener('click', function(){
                item.classList.remove('unread');
                const unreadCount = document.querySelectorAll('.notification-item.unread').length;
                const badge = document.getElementById('notificationCount');
                if (badge) {
                    badge.textContent = unreadCount;
                    if (unreadCount === 0) badge.style.display = 'none';
                }
            });
        });
    }

    document.addEventListener('click', function(e){
        if (userDropdown && userProfileBtn && !userProfileBtn.contains(e.target)) userDropdown.classList.remove('active');
        if (notificationDropdown && notificationBtn && !notificationBtn.contains(e.target)) notificationDropdown.classList.remove('active');
    });

    document.addEventListener('keydown', function(e){
        if (e.key === 'Escape') {
            if (userDropdown) userDropdown.classList.remove('active');
            if (notificationDropdown) notificationDropdown.classList.remove('active');
        }
    });
-->