<?php
$page_title = 'Leave Balance';
require_once '../../config/db.php';
require_once '../../core/session.php';

$database = new Database();
$db = $database->getConnection();

include '../../includes/header.php';

$stmt = $db->prepare("SELECT COALESCE(SUM(total_days),0) AS used FROM leave_requests WHERE employee_id = :emp AND status='Approved'");
$stmt->execute([':emp' => $employee_id]);
$used = (float)($stmt->fetch(PDO::FETCH_ASSOC)['used'] ?? 0);
$total = 24;
$available = max(0, $total - $used);

$types = [
    ['name'=>'Casual Leave','total'=>8],
    ['name'=>'Sick Leave','total'=>8],
    ['name'=>'Earned Leave','total'=>8],
];
?>

<?php include '../../includes/sidebar.php'; ?>
<div class="main-content">
    <?php include '../../includes/navbar.php'; ?>

    <div class="page-content">
        <div class="page-header" style="margin-bottom:30px;">
            <h1 style="font-size:28px;font-weight:700;margin-bottom:8px;">Leave Balance</h1>
            <p style="color:var(--text-gray);">Overview of your available leave credits</p>
        </div>

        <div style="background:var(--primary-gradient);color:#fff;border-radius:16px;padding:28px;margin-bottom:24px;">
            <div style="display:flex;justify-content:space-between;gap:20px;flex-wrap:wrap;">
                <div>
                    <div style="opacity:.9;font-size:14px;margin-bottom:8px;">Total Annual Leaves</div>
                    <div style="font-size:42px;font-weight:800;line-height:1;"><?php echo (int)$total; ?></div>
                </div>
                <div>
                    <div style="opacity:.9;font-size:14px;margin-bottom:8px;">Used</div>
                    <div style="font-size:42px;font-weight:800;line-height:1;"><?php echo (int)$used; ?></div>
                </div>
                <div>
                    <div style="opacity:.9;font-size:14px;margin-bottom:8px;">Available</div>
                    <div style="font-size:42px;font-weight:800;line-height:1;"><?php echo (int)$available; ?></div>
                </div>
            </div>
        </div>

        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:20px;">
            <?php foreach($types as $t):
                $typeUsedStmt = $db->prepare("SELECT COALESCE(SUM(total_days),0) AS used FROM leave_requests WHERE employee_id=:emp AND status='Approved' AND leave_type=:type");
                $typeUsedStmt->execute([':emp'=>$employee_id, ':type'=>$t['name']]);
                $tUsed = (float)($typeUsedStmt->fetch(PDO::FETCH_ASSOC)['used'] ?? 0);
                $tAvail = max(0, $t['total'] - $tUsed);
                $pct = $t['total'] > 0 ? round(($tAvail/$t['total'])*100) : 0;
            ?>
            <div style="background:#fff;border:1px solid var(--border-color);border-radius:16px;padding:22px;">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:12px;">
                    <div>
                        <div style="color:var(--text-gray);font-size:13px;margin-bottom:6px;"><?php echo htmlspecialchars($t['name']); ?></div>
                        <div style="font-size:28px;font-weight:800;"><?php echo (int)$tAvail; ?> <span style="font-size:14px;color:var(--text-gray);font-weight:600;">/ <?php echo (int)$t['total']; ?></span></div>
                        <div style="color:var(--text-gray);font-size:12px;margin-top:6px;"><?php echo $pct; ?>% available</div>
                    </div>
                    <div style="width:44px;height:44px;border-radius:12px;background:#ede9fe;color:#6d28d9;display:flex;align-items:center;justify-content:center;">
                        <i class="fas fa-leaf"></i>
                    </div>
                </div>
                <div style="margin-top:14px;height:10px;background:#f3f4f6;border-radius:99px;overflow:hidden;">
                    <div style="height:100%;width:<?php echo $pct; ?>%;background:var(--primary-gradient);"></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div style="margin-top:20px;display:flex;gap:12px;flex-wrap:wrap;">
            <a href="leave_apply.php" style="padding:12px 18px;background:var(--primary-gradient);color:#fff;border-radius:10px;text-decoration:none;font-weight:800;">
                <i class="fas fa-plus"></i> Apply Leave
            </a>
            <a href="leave_my_requests.php" style="padding:12px 18px;background:#fff;border:2px solid var(--border-color);color:var(--text-dark);border-radius:10px;text-decoration:none;font-weight:800;">
                <i class="fas fa-list"></i> My Requests
            </a>
            <a href="leave_calendar.php" style="padding:12px 18px;background:#fff;border:2px solid var(--border-color);color:var(--text-dark);border-radius:10px;text-decoration:none;font-weight:800;">
                <i class="fas fa-calendar"></i> Calendar
            </a>
        </div>
    </div>
</div>
<?php include '../../includes/footer.php'; ?>
