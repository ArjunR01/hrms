<?php
$page_title = 'All Notifications';
require_once '../../config/db.php';
require_once '../../core/session.php';

$database = new Database();
$db = $database->getConnection();

include '../../includes/header.php';

// NOTE: no notifications table yet; show consistent UI + sample list
$notifications = [
    ['type'=>'warning','icon'=>'fa-clock','title'=>'Late Arrival Alert','message'=>'You were marked late today at 10:15 AM','time'=>'2 hours ago','unread'=>true],
    ['type'=>'success','icon'=>'fa-check','title'=>'Leave Approved','message'=>'Your leave request for Jan 25-27 has been approved','time'=>'5 hours ago','unread'=>true],
    ['type'=>'info','icon'=>'fa-dollar-sign','title'=>'Payroll Processed','message'=>'Your salary for January has been processed','time'=>'1 day ago','unread'=>true],
    ['type'=>'info','icon'=>'fa-graduation-cap','title'=>'Training Reminder','message'=>'Leadership Training scheduled for tomorrow','time'=>'2 days ago','unread'=>false],
    ['type'=>'success','icon'=>'fa-chart-line','title'=>'Performance Review','message'=>'Your Q4 performance review is ready','time'=>'3 days ago','unread'=>false],
];
?>

<?php include '../../includes/sidebar.php'; ?>
<div class="main-content">
    <?php include '../../includes/navbar.php'; ?>

    <div class="page-content">
        <div class="page-header" style="margin-bottom: 30px; display:flex; justify-content:space-between; align-items:center;">
            <div>
                <h1 style="font-size: 28px; font-weight: 700; margin-bottom: 8px;">Notifications</h1>
                <p style="color: var(--text-gray);">All updates and alerts related to your HRMS account</p>
            </div>
            <button style="padding:12px 18px;border-radius:10px;border:2px solid var(--border-color);background:white;font-weight:700;cursor:pointer;">
                <i class="fas fa-check-double"></i> Mark all as read
            </button>
        </div>

        <div style="background:white;border:1px solid var(--border-color);border-radius:16px;overflow:hidden;">
            <div style="padding:18px 22px;border-bottom:1px solid var(--border-color);">
                <h3 style="margin:0;font-size:18px;font-weight:800;">Notification List</h3>
            </div>
            <div>
                <?php foreach($notifications as $n): ?>
                    <div style="display:flex;gap:14px;padding:18px 22px;border-bottom:1px solid #f3f4f6;background:<?php echo $n['unread'] ? '#eff6ff' : 'white'; ?>;">
                        <div style="width:44px;height:44px;border-radius:50%;display:flex;align-items:center;justify-content:center;
                            <?php if($n['type']==='success') echo 'background:#dcfce7;color:#22c55e;';
                                  elseif($n['type']==='warning') echo 'background:#fef3c7;color:#f59e0b;';
                                  else echo 'background:#dbeafe;color:#3b82f6;'; ?>">
                            <i class="fas <?php echo $n['icon']; ?>"></i>
                        </div>
                        <div style="flex:1;">
                            <div style="display:flex;justify-content:space-between;gap:12px;align-items:flex-start;">
                                <div>
                                    <div style="font-weight:800;"><?php echo htmlspecialchars($n['title']); ?></div>
                                    <div style="color:var(--text-gray);font-size:13px;margin-top:4px;line-height:1.4;"><?php echo htmlspecialchars($n['message']); ?></div>
                                </div>
                                <div style="color:#9ca3af;font-size:12px;white-space:nowrap;"><?php echo htmlspecialchars($n['time']); ?></div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div style="margin-top:16px;background:#fff7ed;border:1px solid #fdba74;color:#9a3412;padding:14px;border-radius:12px;">
            <strong>Next step:</strong> we can add a `notifications` table and load real notifications for each employee.
        </div>
    </div>
</div>
<?php include '../../includes/footer.php'; ?>
