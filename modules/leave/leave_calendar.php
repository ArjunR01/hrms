<?php
$page_title = 'Leave Calendar';
require_once '../../config/db.php';
require_once '../../core/session.php';

$database = new Database();
$db = $database->getConnection();

include '../../includes/header.php';

$month = (int)($_GET['month'] ?? date('m'));
$year  = (int)($_GET['year'] ?? date('Y'));

$firstDay = new DateTime(sprintf('%04d-%02d-01', $year, $month));
$daysInMonth = (int)$firstDay->format('t');
$startWeekday = (int)$firstDay->format('N'); // 1..7

$stmt = $db->prepare("SELECT from_date,to_date,leave_type,status FROM leave_requests WHERE employee_id=:emp AND status IN ('Approved','Pending') AND (MONTH(from_date)=:m OR MONTH(to_date)=:m) AND (YEAR(from_date)=:y OR YEAR(to_date)=:y)");
$stmt->execute([':emp'=>$employee_id, ':m'=>$month, ':y'=>$year]);
$leaves = $stmt->fetchAll(PDO::FETCH_ASSOC);

$leaveMap = [];
foreach ($leaves as $l) {
    $from = new DateTime($l['from_date']);
    $to = new DateTime($l['to_date']);
    for ($d = clone $from; $d <= $to; $d->modify('+1 day')) {
        if ((int)$d->format('m') === $month && (int)$d->format('Y') === $year) {
            $leaveMap[$d->format('Y-m-d')] = $l;
        }
    }
}
?>

<?php include '../../includes/sidebar.php'; ?>
<div class="main-content">
    <?php include '../../includes/navbar.php'; ?>

    <div class="page-content">
        <div class="page-header" style="margin-bottom:22px;display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;">
            <div>
                <h1 style="font-size:28px;font-weight:700;margin-bottom:8px;">Leave Calendar</h1>
                <p style="color:var(--text-gray);">Your leave requests shown on a monthly calendar</p>
            </div>
            <div style="display:flex;gap:10px;">
                <a href="leave_my_requests.php" style="padding:10px 14px;background:#fff;border:2px solid var(--border-color);border-radius:10px;text-decoration:none;font-weight:800;color:var(--text-dark);">
                    <i class="fas fa-list"></i> My Requests
                </a>
                <a href="leave_apply.php" style="padding:10px 14px;background:var(--primary-gradient);color:#fff;border-radius:10px;text-decoration:none;font-weight:800;">
                    <i class="fas fa-plus"></i> Apply Leave
                </a>
            </div>
        </div>

        <div style="background:#fff;border:1px solid var(--border-color);border-radius:16px;overflow:hidden;">
            <div style="padding:18px 22px;border-bottom:1px solid var(--border-color);display:flex;justify-content:space-between;align-items:center;">
                <div style="font-weight:800;font-size:18px;">
                    <?php echo htmlspecialchars($firstDay->format('F Y')); ?>
                </div>
                <div style="display:flex;gap:8px;">
                    <?php $prev = (clone $firstDay)->modify('-1 month'); $next = (clone $firstDay)->modify('+1 month'); ?>
                    <a href="?month=<?php echo (int)$prev->format('m'); ?>&year=<?php echo (int)$prev->format('Y'); ?>" style="padding:8px 12px;border:2px solid var(--border-color);border-radius:10px;text-decoration:none;color:var(--text-dark);font-weight:800;">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                    <a href="?month=<?php echo (int)$next->format('m'); ?>&year=<?php echo (int)$next->format('Y'); ?>" style="padding:8px 12px;border:2px solid var(--border-color);border-radius:10px;text-decoration:none;color:var(--text-dark);font-weight:800;">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
            </div>

            <div style="display:grid;grid-template-columns:repeat(7,1fr);background:var(--bg-light);">
                <?php foreach(['Mon','Tue','Wed','Thu','Fri','Sat','Sun'] as $wd): ?>
                    <div style="padding:14px 12px;font-weight:800;color:var(--text-gray);font-size:13px;border-right:1px solid #eef2f7;"><?php echo $wd; ?></div>
                <?php endforeach; ?>
            </div>

            <div style="display:grid;grid-template-columns:repeat(7,1fr);">
                <?php
                $cells = 0;
                for ($i=1; $i<$startWeekday; $i++) {
                    echo '<div style="min-height:110px;border-top:1px solid #eef2f7;border-right:1px solid #eef2f7;background:#fff;"></div>';
                    $cells++;
                }

                for ($day=1; $day<=$daysInMonth; $day++) {
                    $dateStr = sprintf('%04d-%02d-%02d', $year, $month, $day);
                    $isToday = ($dateStr === date('Y-m-d'));
                    $hasLeave = isset($leaveMap[$dateStr]);
                    $bg = $hasLeave ? ($leaveMap[$dateStr]['status'] === 'Approved' ? '#dcfce7' : '#fef3c7') : '#fff';

                    echo '<div style="min-height:110px;padding:12px;border-top:1px solid #eef2f7;border-right:1px solid #eef2f7;background:' . $bg . ';">';
                    echo '<div style="display:flex;justify-content:space-between;align-items:center;">';
                    echo '<div style="font-weight:800;' . ($isToday ? 'color:#667eea;' : '') . '">' . $day . '</div>';
                    if ($hasLeave) {
                        $color = $leaveMap[$dateStr]['status'] === 'Approved' ? '#16a34a' : '#b45309';
                        echo '<span style="font-size:11px;font-weight:800;color:' . $color . ';">' . htmlspecialchars($leaveMap[$dateStr]['status']) . '</span>';
                    }
                    echo '</div>';
                    if ($hasLeave) {
                        echo '<div style="margin-top:10px;font-size:12px;font-weight:700;color:#111827;">' . htmlspecialchars($leaveMap[$dateStr]['leave_type']) . '</div>';
                    }
                    echo '</div>';
                    $cells++;
                }

                while ($cells % 7 !== 0) {
                    echo '<div style="min-height:110px;border-top:1px solid #eef2f7;border-right:1px solid #eef2f7;background:#fff;"></div>';
                    $cells++;
                }
                ?>
            </div>
        </div>

        <div style="margin-top:16px;display:flex;gap:12px;flex-wrap:wrap;">
            <div style="display:flex;align-items:center;gap:8px;color:var(--text-gray);font-size:13px;">
                <span style="width:14px;height:14px;background:#dcfce7;border-radius:4px;display:inline-block;"></span> Approved
            </div>
            <div style="display:flex;align-items:center;gap:8px;color:var(--text-gray);font-size:13px;">
                <span style="width:14px;height:14px;background:#fef3c7;border-radius:4px;display:inline-block;"></span> Pending
            </div>
        </div>
    </div>
</div>
<?php include '../../includes/footer.php'; ?>
