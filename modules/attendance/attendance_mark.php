<?php
/**
 * Mark Attendance Page
 */

$page_title = 'Mark Attendance';

require_once '../../config/db.php';
require_once '../../core/session.php';

$database = new Database();
$db = $database->getConnection();

include '../../includes/header.php';

// Check if already marked today
$today = date('Y-m-d');
$query = "SELECT * FROM attendance WHERE employee_id = :emp_id AND attendance_date = :date";
$stmt = $db->prepare($query);
$stmt->execute([':emp_id' => $employee_id, ':date' => $today]);
$today_attendance = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$today_attendance) {
    $check_in = date('H:i:s');
    $status = 'Present';
    
    $query = "INSERT INTO attendance (employee_id, attendance_date, check_in, status) VALUES (:emp_id, :date, :check_in, :status)";
    $stmt = $db->prepare($query);
    
    if ($stmt->execute([':emp_id' => $employee_id, ':date' => $today, ':check_in' => $check_in, ':status' => $status])) {
        $success = "Attendance marked successfully!";
        // Refresh data
        $stmt = $db->prepare("SELECT * FROM attendance WHERE employee_id = :emp_id AND attendance_date = :date");
        $stmt->execute([':emp_id' => $employee_id, ':date' => $today]);
        $today_attendance = $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

// Handle check-out
if (isset($_POST['check_out']) && $today_attendance && !$today_attendance['check_out']) {
    $check_out = date('H:i:s');
    $query = "UPDATE attendance SET check_out = :check_out WHERE id = :id";
    $stmt = $db->prepare($query);
    
    if ($stmt->execute([':check_out' => $check_out, ':id' => $today_attendance['id']])) {
        $success = "Check-out marked successfully!";
        $today_attendance['check_out'] = $check_out;
    }
}
?>

<?php include '../../includes/sidebar.php'; ?>

<div class="main-content">
    <?php include '../../includes/navbar.php'; ?>
    
    <div class="page-content">
        <div class="page-header" style="margin-bottom: 30px;">
            <h1 style="font-size: 28px; font-weight: 700; margin-bottom: 8px;">Mark Attendance</h1>
            <p style="color: var(--text-gray);">Check in and out for today</p>
        </div>
        
        <?php if (isset($success)): ?>
            <div style="background: #dcfce7; border: 1px solid #22c55e; color: #22c55e; padding: 16px; border-radius: 10px; margin-bottom: 20px;">
                <i class="fas fa-check-circle"></i> <?php echo $success; ?>
            </div>
        <?php endif; ?>
        
        <div style="max-width: 600px; margin: 0 auto;">
            <div style="background: white; padding: 40px; border-radius: 16px; border: 1px solid var(--border-color); text-align: center;">
                <div style="width: 100px; height: 100px; background: var(--primary-gradient); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 48px; color: white;">
                    <i class="fas fa-clock"></i>
                </div>
                
                <h2 style="font-size: 24px; margin-bottom: 8px;">Today: <?php echo date('l, F j, Y'); ?></h2>
                <p style="font-size: 48px; font-weight: 700; color: var(--primary-color); margin: 20px 0;"><?php echo date('h:i A'); ?></p>
                
                <?php if (!$today_attendance): ?>
                    <form method="POST" style="margin-top: 30px;">
                        <button type="submit" class="btn-primary" style="padding: 16px 40px; background: var(--primary-gradient); color: white; border: none; border-radius: 10px; font-size: 18px; font-weight: 600; cursor: pointer; width: 100%;">
                            <i class="fas fa-sign-in-alt"></i> Check In Now
                        </button>
                    </form>
                <?php elseif (!$today_attendance['check_out']): ?>
                    <div style="background: #dcfce7; padding: 20px; border-radius: 10px; margin: 20px 0;">
                        <p style="color: #22c55e; font-weight: 600; margin: 0;">
                            <i class="fas fa-check-circle"></i> Checked in at <?php echo date('h:i A', strtotime($today_attendance['check_in'])); ?>
                        </p>
                    </div>
                    <form method="POST">
                        <button type="submit" name="check_out" class="btn-secondary" style="padding: 16px 40px; background: var(--error); color: white; border: none; border-radius: 10px; font-size: 18px; font-weight: 600; cursor: pointer; width: 100%;">
                            <i class="fas fa-sign-out-alt"></i> Check Out Now
                        </button>
                    </form>
                <?php else: ?>
                    <div style="background: #dcfce7; padding: 20px; border-radius: 10px; margin: 20px 0;">
                        <p style="color: #22c55e; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-check-circle"></i> Attendance Completed for Today
                        </p>
                        <p style="margin: 0; font-size: 14px; color: var(--text-gray);">
                            Check in: <?php echo date('h:i A', strtotime($today_attendance['check_in'])); ?> | 
                            Check out: <?php echo date('h:i A', strtotime($today_attendance['check_out'])); ?>
                        </p>
                    </div>
                <?php endif; ?>
                
                <a href="attendance_list.php" style="display: inline-block; margin-top: 20px; color: var(--primary-color); text-decoration: none; font-weight: 600;">
                    <i class="fas fa-list"></i> View Attendance History
                </a>
            </div>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
