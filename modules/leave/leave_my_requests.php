<?php
/**
 * My Leave Requests Page
 */

$page_title = 'My Leave Requests';

require_once '../../config/db.php';
require_once '../../core/session.php';

$database = new Database();
$db = $database->getConnection();

include '../../includes/header.php';

// Fetch leave requests
$query = "SELECT * FROM leave_requests WHERE employee_id = :emp_id ORDER BY created_at DESC";
$stmt = $db->prepare($query);
$stmt->execute([':emp_id' => $employee_id]);
$leave_requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get leave balance
$balance_query = "SELECT 
    (SELECT COALESCE(SUM(total_days), 0) FROM leave_requests WHERE employee_id = :emp_id AND status = 'Approved') as used_leaves,
    24 as total_leaves";
$stmt = $db->prepare($balance_query);
$stmt->execute([':emp_id' => $employee_id]);
$balance = $stmt->fetch(PDO::FETCH_ASSOC);
$available = $balance['total_leaves'] - $balance['used_leaves'];
?>

<?php include '../../includes/sidebar.php'; ?>

<div class="main-content">
    <?php include '../../includes/navbar.php'; ?>
    
    <div class="page-content">
        <div class="page-header" style="margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 style="font-size: 28px; font-weight: 700; margin-bottom: 8px;">My Leave Requests</h1>
                <p style="color: var(--text-gray);">View and manage your leave applications</p>
            </div>
            <a href="leave_apply.php" class="btn-primary" style="padding: 12px 24px; background: var(--primary-gradient); color: white; text-decoration: none; border-radius: 10px; font-weight: 600;">
                <i class="fas fa-plus"></i> Apply for Leave
            </a>
        </div>
        
        <!-- Leave Balance Card -->
        <div style="background: var(--primary-gradient); color: white; padding: 30px; border-radius: 16px; margin-bottom: 30px;">
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px;">
                <div>
                    <p style="opacity: 0.9; margin-bottom: 8px;">Total Leaves</p>
                    <h2 style="font-size: 36px; font-weight: 700; margin: 0;"><?php echo $balance['total_leaves']; ?></h2>
                </div>
                <div>
                    <p style="opacity: 0.9; margin-bottom: 8px;">Used Leaves</p>
                    <h2 style="font-size: 36px; font-weight: 700; margin: 0;"><?php echo $balance['used_leaves']; ?></h2>
                </div>
                <div>
                    <p style="opacity: 0.9; margin-bottom: 8px;">Available</p>
                    <h2 style="font-size: 36px; font-weight: 700; margin: 0;"><?php echo $available; ?></h2>
                </div>
            </div>
        </div>
        
        <!-- Leave Requests Table -->
        <div style="background: white; border-radius: 16px; border: 1px solid var(--border-color); overflow: hidden;">
            <div style="padding: 24px; border-bottom: 1px solid var(--border-color);">
                <h3 style="font-size: 18px; font-weight: 700; margin: 0;">Leave Applications</h3>
            </div>
            
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead style="background: var(--bg-light);">
                        <tr>
                            <th style="padding: 16px; text-align: left; font-weight: 600; font-size: 14px;">Leave Type</th>
                            <th style="padding: 16px; text-align: left; font-weight: 600; font-size: 14px;">From - To</th>
                            <th style="padding: 16px; text-align: left; font-weight: 600; font-size: 14px;">Days</th>
                            <th style="padding: 16px; text-align: left; font-weight: 600; font-size: 14px;">Reason</th>
                            <th style="padding: 16px; text-align: left; font-weight: 600; font-size: 14px;">Status</th>
                            <th style="padding: 16px; text-align: left; font-weight: 600; font-size: 14px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($leave_requests) > 0): ?>
                            <?php foreach ($leave_requests as $leave): ?>
                                <tr style="border-bottom: 1px solid var(--border-color);">
                                    <td style="padding: 16px; font-weight: 600;"><?php echo htmlspecialchars($leave['leave_type']); ?></td>
                                    <td style="padding: 16px;"><?php echo date('d M', strtotime($leave['from_date'])) . ' - ' . date('d M Y', strtotime($leave['to_date'])); ?></td>
                                    <td style="padding: 16px;"><?php echo $leave['total_days']; ?> days</td>
                                    <td style="padding: 16px; max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"><?php echo htmlspecialchars($leave['reason']); ?></td>
                                    <td style="padding: 16px;">
                                        <?php
                                        $status_colors = [
                                            'Pending' => ['bg' => '#fef3c7', 'text' => '#f59e0b'],
                                            'Approved' => ['bg' => '#dcfce7', 'text' => '#22c55e'],
                                            'Rejected' => ['bg' => '#fee2e2', 'text' => '#ef4444'],
                                        ];
                                        $color = $status_colors[$leave['status']] ?? ['bg' => '#f3f4f6', 'text' => '#6b7280'];
                                        ?>
                                        <span style="padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; background: <?php echo $color['bg']; ?>; color: <?php echo $color['text']; ?>;">
                                            <?php echo $leave['status']; ?>
                                        </span>
                                    </td>
                                    <td style="padding: 16px;">
                                        <button style="padding: 8px 16px; background: var(--primary-color); color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 13px;">
                                            View Details
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" style="padding: 40px; text-align: center; color: var(--text-gray);">
                                    <i class="fas fa-calendar-times" style="font-size: 48px; opacity: 0.3; margin-bottom: 16px; display: block;"></i>
                                    No leave requests found. Apply for your first leave!
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
