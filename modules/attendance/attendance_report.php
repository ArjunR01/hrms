<?php
$page_title = 'Attendance Report';
require_once '../../config/db.php';
require_once '../../core/session.php';

$database = new Database();
$db = $database->getConnection();

include '../../includes/header.php';

$month = $_GET['month'] ?? date('m');
$year  = $_GET['year'] ?? date('Y');

$stmt = $db->prepare("SELECT * FROM attendance WHERE employee_id=:emp AND MONTH(attendance_date)=:m AND YEAR(attendance_date)=:y ORDER BY attendance_date ASC");
$stmt->execute([':emp' => $employee_id, ':m' => $month, ':y' => $year]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Export CSV
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="attendance_' . $year . '_' . $month . '.csv"');
    $out = fopen('php://output', 'w');
    fputcsv($out, ['Date', 'Check In', 'Check Out', 'Status', 'Remarks']);
    foreach ($rows as $r) {
        fputcsv($out, [$r['attendance_date'], $r['check_in'], $r['check_out'], $r['status'], $r['remarks']]);
    }
    fclose($out);
    exit;
}
?>

<?php include '../../includes/sidebar.php'; ?>
<div class="main-content">
    <?php include '../../includes/navbar.php'; ?>

    <div class="page-content">
        <div class="page-header" style="margin-bottom:22px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;">
            <div>
                <h1 style="font-size:28px;font-weight:700;margin-bottom:8px;">Attendance Report</h1>
                <p style="color:var(--text-gray);">Generate and export your monthly attendance</p>
            </div>
            <a href="?month=<?php echo urlencode($month); ?>&year=<?php echo urlencode($year); ?>&export=csv" style="padding:12px 16px;border-radius:10px;background:var(--primary-gradient);color:#fff;text-decoration:none;font-weight:800;">
                <i class="fas fa-download"></i> Export CSV
            </a>
        </div>

        <div style="background:#fff;border:1px solid var(--border-color);border-radius:16px;overflow:hidden;">
            <div style="padding:18px 22px;border-bottom:1px solid var(--border-color);display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;">
                <div style="font-weight:800;">Month: <?php echo htmlspecialchars(date('F Y', mktime(0,0,0,(int)$month,1,(int)$year))); ?></div>
                <form method="GET" style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
                    <select name="month" style="padding:10px;border:2px solid var(--border-color);border-radius:10px;">
                        <?php for($m=1;$m<=12;$m++): ?>
                            <option value="<?php echo $m; ?>" <?php echo ((int)$month === $m) ? 'selected' : ''; ?>><?php echo date('F', mktime(0,0,0,$m,1)); ?></option>
                        <?php endfor; ?>
                    </select>
                    <select name="year" style="padding:10px;border:2px solid var(--border-color);border-radius:10px;">
                        <?php $yNow=(int)date('Y'); for($y=$yNow-2;$y<=$yNow+1;$y++): ?>
                            <option value="<?php echo $y; ?>" <?php echo ((int)$year === $y) ? 'selected' : ''; ?>><?php echo $y; ?></option>
                        <?php endfor; ?>
                    </select>
                    <button type="submit" style="padding:10px 14px;border-radius:10px;border:none;background:var(--primary-color);color:#fff;font-weight:800;cursor:pointer;">Filter</button>
                </form>
            </div>

            <div style="overflow-x:auto;">
                <table style="width:100%;border-collapse:collapse;">
                    <thead style="background:var(--bg-light);">
                        <tr>
                            <th style="padding:14px 16px;text-align:left;font-weight:800;font-size:13px;">Date</th>
                            <th style="padding:14px 16px;text-align:left;font-weight:800;font-size:13px;">Check In</th>
                            <th style="padding:14px 16px;text-align:left;font-weight:800;font-size:13px;">Check Out</th>
                            <th style="padding:14px 16px;text-align:left;font-weight:800;font-size:13px;">Status</th>
                            <th style="padding:14px 16px;text-align:left;font-weight:800;font-size:13px;">Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!$rows): ?>
                            <tr><td colspan="5" style="padding:28px;text-align:center;color:var(--text-gray);">No data for selected month.</td></tr>
                        <?php else: foreach($rows as $r): ?>
                            <tr style="border-bottom:1px solid #eef2f7;">
                                <td style="padding:14px 16px;"><?php echo date('d M Y', strtotime($r['attendance_date'])); ?></td>
                                <td style="padding:14px 16px;"><?php echo $r['check_in'] ? date('h:i A', strtotime($r['check_in'])) : '-'; ?></td>
                                <td style="padding:14px 16px;"><?php echo $r['check_out'] ? date('h:i A', strtotime($r['check_out'])) : '-'; ?></td>
                                <td style="padding:14px 16px;font-weight:800;"><?php echo htmlspecialchars($r['status']); ?></td>
                                <td style="padding:14px 16px;color:var(--text-gray);"><?php echo htmlspecialchars($r['remarks'] ?? '-'); ?></td>
                            </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include '../../includes/footer.php'; ?>
