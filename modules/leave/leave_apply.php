<?php
/**
 * Apply for Leave Page
 */

$page_title = 'Apply for Leave';

require_once '../../config/db.php';
require_once '../../core/session.php';

$database = new Database();
$db = $database->getConnection();

include '../../includes/header.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $leave_type = $_POST['leave_type'];
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];
    $reason = $_POST['reason'];
    
    // Calculate days
    $date1 = new DateTime($from_date);
    $date2 = new DateTime($to_date);
    $total_days = $date2->diff($date1)->days + 1;
    
    $query = "INSERT INTO leave_requests (employee_id, leave_type, from_date, to_date, total_days, reason, status) 
              VALUES (:emp_id, :type, :from, :to, :days, :reason, 'Pending')";
    
    $stmt = $db->prepare($query);
    
    if ($stmt->execute([
        ':emp_id' => $employee_id,
        ':type' => $leave_type,
        ':from' => $from_date,
        ':to' => $to_date,
        ':days' => $total_days,
        ':reason' => $reason
    ])) {
        $success = "Leave application submitted successfully!";
    } else {
        $error = "Failed to submit leave application.";
    }
}
?>

<?php include '../../includes/sidebar.php'; ?>

<div class="main-content">
    <?php include '../../includes/navbar.php'; ?>
    
    <div class="page-content">
        <div class="page-header" style="margin-bottom: 30px;">
            <h1 style="font-size: 28px; font-weight: 700; margin-bottom: 8px;">Apply for Leave</h1>
            <p style="color: var(--text-gray);">Submit a new leave application</p>
        </div>
        
        <?php if (isset($success)): ?>
            <div style="background: #dcfce7; border: 1px solid #22c55e; color: #22c55e; padding: 16px; border-radius: 10px; margin-bottom: 20px;">
                <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                <a href="leave_my_requests.php" style="color: #22c55e; margin-left: 10px;">View My Requests</a>
            </div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div style="background: #fee2e2; border: 1px solid #ef4444; color: #ef4444; padding: 16px; border-radius: 10px; margin-bottom: 20px;">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <div style="max-width: 800px;">
            <div style="background: white; padding: 30px; border-radius: 16px; border: 1px solid var(--border-color);">
                <form method="POST">
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                        <div>
                            <label style="display: block; font-weight: 600; margin-bottom: 8px;">Leave Type *</label>
                            <select name="leave_type" required style="width: 100%; padding: 12px; border: 2px solid var(--border-color); border-radius: 10px; font-size: 14px;">
                                <option value="">Select Leave Type</option>
                                <option value="Casual Leave">Casual Leave</option>
                                <option value="Sick Leave">Sick Leave</option>
                                <option value="Earned Leave">Earned Leave</option>
                                <option value="Maternity Leave">Maternity Leave</option>
                                <option value="Paternity Leave">Paternity Leave</option>
                            </select>
                        </div>
                        
                        <div></div>
                        
                        <div>
                            <label style="display: block; font-weight: 600; margin-bottom: 8px;">From Date *</label>
                            <input type="date" name="from_date" required min="<?php echo date('Y-m-d'); ?>" style="width: 100%; padding: 12px; border: 2px solid var(--border-color); border-radius: 10px; font-size: 14px;">
                        </div>
                        
                        <div>
                            <label style="display: block; font-weight: 600; margin-bottom: 8px;">To Date *</label>
                            <input type="date" name="to_date" required min="<?php echo date('Y-m-d'); ?>" style="width: 100%; padding: 12px; border: 2px solid var(--border-color); border-radius: 10px; font-size: 14px;">
                        </div>
                        
                        <div style="grid-column: 1 / -1;">
                            <label style="display: block; font-weight: 600; margin-bottom: 8px;">Reason *</label>
                            <textarea name="reason" required rows="4" style="width: 100%; padding: 12px; border: 2px solid var(--border-color); border-radius: 10px; font-size: 14px; resize: vertical;" placeholder="Enter reason for leave..."></textarea>
                        </div>
                    </div>
                    
                    <div style="margin-top: 30px; display: flex; gap: 12px;">
                        <button type="submit" style="padding: 12px 30px; background: var(--primary-gradient); color: white; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">
                            <i class="fas fa-paper-plane"></i> Submit Application
                        </button>
                        <a href="leave_my_requests.php" style="padding: 12px 30px; background: white; color: var(--text-dark); border: 2px solid var(--border-color); border-radius: 10px; font-weight: 600; text-decoration: none; display: inline-block;">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
