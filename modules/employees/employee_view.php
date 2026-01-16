<?php
/**
 * View Employee Profile
 */

$page_title = 'My Profile';

require_once '../../config/db.php';
require_once '../../core/session.php';

$database = new Database();
$db = $database->getConnection();

include '../../includes/header.php';

// Get employee details
$emp_id = $_GET['id'] ?? $employee_id;

$query = "SELECT e.*, u.email, u.username FROM employees e 
          LEFT JOIN users u ON e.id = u.employee_id 
          WHERE e.id = :id";
$stmt = $db->prepare($query);
$stmt->execute([':id' => $emp_id]);
$employee = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$employee) {
    header('Location: ../../dashboards/employee_dashboard.php');
    exit();
}
?>

<?php include '../../includes/sidebar.php'; ?>

<div class="main-content">
    <?php include '../../includes/navbar.php'; ?>
    
    <div class="page-content">
        <div class="page-header" style="margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 style="font-size: 28px; font-weight: 700; margin-bottom: 8px;">Employee Profile</h1>
                <p style="color: var(--text-gray);">View and manage your profile information</p>
            </div>
            <a href="employee_edit.php?id=<?php echo $emp_id; ?>" class="btn-primary" style="padding: 12px 24px; background: var(--primary-gradient); color: white; text-decoration: none; border-radius: 10px; font-weight: 600;">
                <i class="fas fa-edit"></i> Edit Profile
            </a>
        </div>
        
        <div style="display: grid; grid-template-columns: 300px 1fr; gap: 30px;">
            <!-- Left Sidebar - Profile Card -->
            <div>
                <div style="background: white; padding: 30px; border-radius: 16px; border: 1px solid var(--border-color); text-align: center;">
                    <div style="width: 120px; height: 120px; background: var(--primary-gradient); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 48px; color: white; font-weight: bold;">
                        <?php echo strtoupper(substr($employee['full_name'], 0, 1)); ?>
                    </div>
                    
                    <h2 style="font-size: 22px; font-weight: 700; margin-bottom: 4px;"><?php echo htmlspecialchars($employee['full_name']); ?></h2>
                    <p style="color: var(--text-gray); margin-bottom: 4px;"><?php echo htmlspecialchars($employee['designation']); ?></p>
                    <p style="color: var(--text-gray); font-size: 14px;"><?php echo htmlspecialchars($employee['department']); ?></p>
                    
                    <div style="background: var(--bg-light); padding: 16px; border-radius: 10px; margin: 20px 0; text-align: left;">
                        <div style="margin-bottom: 12px;">
                            <p style="font-size: 12px; color: var(--text-gray); margin-bottom: 4px;">Employee ID</p>
                            <p style="font-weight: 600; margin: 0;"><?php echo htmlspecialchars($employee['employee_id']); ?></p>
                        </div>
                        <div>
                            <p style="font-size: 12px; color: var(--text-gray); margin-bottom: 4px;">Joining Date</p>
                            <p style="font-weight: 600; margin: 0;"><?php echo date('d M Y', strtotime($employee['date_of_joining'])); ?></p>
                        </div>
                    </div>
                    
                    <div style="padding: 16px; background: <?php echo $employee['is_active'] ? '#dcfce7' : '#fee2e2'; ?>; border-radius: 10px;">
                        <p style="margin: 0; font-weight: 600; color: <?php echo $employee['is_active'] ? '#22c55e' : '#ef4444'; ?>;">
                            <?php echo $employee['is_active'] ? '✓ Active' : '✗ Inactive'; ?>
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Right Content - Details -->
            <div>
                <!-- Personal Information -->
                <div style="background: white; padding: 30px; border-radius: 16px; border: 1px solid var(--border-color); margin-bottom: 20px;">
                    <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 20px; padding-bottom: 12px; border-bottom: 2px solid var(--border-color);">
                        <i class="fas fa-user" style="color: var(--primary-color);"></i> Personal Information
                    </h3>
                    
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                        <div>
                            <p style="font-size: 12px; color: var(--text-gray); margin-bottom: 4px;">Full Name</p>
                            <p style="font-weight: 600; margin: 0;"><?php echo htmlspecialchars($employee['full_name']); ?></p>
                        </div>
                        <div>
                            <p style="font-size: 12px; color: var(--text-gray); margin-bottom: 4px;">Date of Birth</p>
                            <p style="font-weight: 600; margin: 0;"><?php echo $employee['date_of_birth'] ? date('d M Y', strtotime($employee['date_of_birth'])) : '-'; ?></p>
                        </div>
                        <div>
                            <p style="font-size: 12px; color: var(--text-gray); margin-bottom: 4px;">Gender</p>
                            <p style="font-weight: 600; margin: 0;"><?php echo htmlspecialchars($employee['gender'] ?? '-'); ?></p>
                        </div>
                        <div>
                            <p style="font-size: 12px; color: var(--text-gray); margin-bottom: 4px;">Email</p>
                            <p style="font-weight: 600; margin: 0;"><?php echo htmlspecialchars($employee['email'] ?? '-'); ?></p>
                        </div>
                        <div>
                            <p style="font-size: 12px; color: var(--text-gray); margin-bottom: 4px;">Phone</p>
                            <p style="font-weight: 600; margin: 0;"><?php echo htmlspecialchars($employee['phone'] ?? '-'); ?></p>
                        </div>
                        <div style="grid-column: 1 / -1;">
                            <p style="font-size: 12px; color: var(--text-gray); margin-bottom: 4px;">Address</p>
                            <p style="font-weight: 600; margin: 0;"><?php echo htmlspecialchars($employee['address'] ?? '-'); ?></p>
                        </div>
                    </div>
                </div>
                
                <!-- Employment Details -->
                <div style="background: white; padding: 30px; border-radius: 16px; border: 1px solid var(--border-color);">
                    <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 20px; padding-bottom: 12px; border-bottom: 2px solid var(--border-color);">
                        <i class="fas fa-briefcase" style="color: var(--primary-color);"></i> Employment Details
                    </h3>
                    
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                        <div>
                            <p style="font-size: 12px; color: var(--text-gray); margin-bottom: 4px;">Employee ID</p>
                            <p style="font-weight: 600; margin: 0;"><?php echo htmlspecialchars($employee['employee_id']); ?></p>
                        </div>
                        <div>
                            <p style="font-size: 12px; color: var(--text-gray); margin-bottom: 4px;">Joining Date</p>
                            <p style="font-weight: 600; margin: 0;"><?php echo date('d M Y', strtotime($employee['date_of_joining'])); ?></p>
                        </div>
                        <div>
                            <p style="font-size: 12px; color: var(--text-gray); margin-bottom: 4px;">Department</p>
                            <p style="font-weight: 600; margin: 0;"><?php echo htmlspecialchars($employee['department']); ?></p>
                        </div>
                        <div>
                            <p style="font-size: 12px; color: var(--text-gray); margin-bottom: 4px;">Designation</p>
                            <p style="font-weight: 600; margin: 0;"><?php echo htmlspecialchars($employee['designation']); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
