<?php
$page_title = 'Search';
require_once '../../config/db.php';
require_once '../../core/session.php';

$database = new Database();
$db = $database->getConnection();

include '../../includes/header.php';

$q = trim($_GET['q'] ?? '');
$results = [
    'employees' => [],
    'attendance' => [],
    'leave' => [],
];

if ($q !== '') {
    // Search employees by name/email/employee_id
    $stmt = $db->prepare("SELECT id, employee_id, full_name, email, department, designation FROM employees WHERE full_name LIKE :q OR employee_id LIKE :q OR email LIKE :q LIMIT 20");
    $stmt->execute([':q' => "%$q%"]);
    $results['employees'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Search attendance remarks
    $stmt = $db->prepare("SELECT attendance_date, status, remarks FROM attendance WHERE employee_id = :emp AND (remarks LIKE :q OR status LIKE :q) ORDER BY attendance_date DESC LIMIT 20");
    $stmt->execute([':emp' => $employee_id, ':q' => "%$q%"]);
    $results['attendance'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Search leave reason/type
    $stmt = $db->prepare("SELECT leave_type, from_date, to_date, status, reason FROM leave_requests WHERE employee_id = :emp AND (leave_type LIKE :q OR reason LIKE :q OR status LIKE :q) ORDER BY created_at DESC LIMIT 20");
    $stmt->execute([':emp' => $employee_id, ':q' => "%$q%"]);
    $results['leave'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<?php include '../../includes/sidebar.php'; ?>
<div class="main-content">
  <?php include '../../includes/navbar.php'; ?>

  <div class="page-content">
    <div class="page-header" style="margin-bottom:22px;">
      <h1 style="font-size:28px;font-weight:700;margin-bottom:8px;">Search</h1>
      <p style="color:var(--text-gray);">Search across employees, attendance, and leave</p>
    </div>

    <div style="background:#fff;border:1px solid var(--border-color);border-radius:16px;padding:18px; margin-bottom:20px;">
      <form method="GET" style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
        <input name="q" value="<?php echo htmlspecialchars($q); ?>" placeholder="Type something..." style="flex:1;min-width:240px;padding:12px;border:2px solid var(--border-color);border-radius:10px;" />
        <button type="submit" style="padding:12px 18px;border:none;border-radius:10px;background:var(--primary-gradient);color:#fff;font-weight:800;cursor:pointer;">
          <i class="fas fa-search"></i> Search
        </button>
      </form>
    </div>

    <?php if ($q === ''): ?>
      <div style="color:var(--text-gray);">Enter a keyword to search.</div>
    <?php else: ?>
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(320px,1fr));gap:18px;">

        <div style="background:#fff;border:1px solid var(--border-color);border-radius:16px;overflow:hidden;">
          <div style="padding:14px 18px;border-bottom:1px solid var(--border-color);font-weight:900;">Employees</div>
          <div style="padding:12px 18px;">
            <?php if (!$results['employees']): ?>
              <div style="color:var(--text-gray);">No employee matches.</div>
            <?php else: foreach($results['employees'] as $e): ?>
              <div style="padding:10px 0;border-bottom:1px solid #f3f4f6;">
                <div style="font-weight:800;"><?php echo htmlspecialchars($e['full_name']); ?></div>
                <div style="color:var(--text-gray);font-size:13px;"><?php echo htmlspecialchars($e['employee_id']); ?> • <?php echo htmlspecialchars($e['department']); ?></div>
                <a href="/modules/employees/employee_view.php?id=<?php echo (int)$e['id']; ?>" style="color:var(--primary-color);font-weight:800;text-decoration:none;font-size:13px;">View Profile</a>
              </div>
            <?php endforeach; endif; ?>
          </div>
        </div>

        <div style="background:#fff;border:1px solid var(--border-color);border-radius:16px;overflow:hidden;">
          <div style="padding:14px 18px;border-bottom:1px solid var(--border-color);font-weight:900;">Attendance</div>
          <div style="padding:12px 18px;">
            <?php if (!$results['attendance']): ?>
              <div style="color:var(--text-gray);">No attendance matches.</div>
            <?php else: foreach($results['attendance'] as $a): ?>
              <div style="padding:10px 0;border-bottom:1px solid #f3f4f6;">
                <div style="font-weight:800;"><?php echo date('d M Y', strtotime($a['attendance_date'])); ?> • <?php echo htmlspecialchars($a['status']); ?></div>
                <div style="color:var(--text-gray);font-size:13px;"><?php echo htmlspecialchars($a['remarks'] ?? '-'); ?></div>
              </div>
            <?php endforeach; endif; ?>
          </div>
        </div>

        <div style="background:#fff;border:1px solid var(--border-color);border-radius:16px;overflow:hidden;">
          <div style="padding:14px 18px;border-bottom:1px solid var(--border-color);font-weight:900;">Leave</div>
          <div style="padding:12px 18px;">
            <?php if (!$results['leave']): ?>
              <div style="color:var(--text-gray);">No leave matches.</div>
            <?php else: foreach($results['leave'] as $l): ?>
              <div style="padding:10px 0;border-bottom:1px solid #f3f4f6;">
                <div style="font-weight:800;"><?php echo htmlspecialchars($l['leave_type']); ?> • <?php echo htmlspecialchars($l['status']); ?></div>
                <div style="color:var(--text-gray);font-size:13px;"><?php echo date('d M', strtotime($l['from_date'])); ?> - <?php echo date('d M Y', strtotime($l['to_date'])); ?></div>
                <div style="color:var(--text-gray);font-size:13px;"><?php echo htmlspecialchars($l['reason']); ?></div>
              </div>
            <?php endforeach; endif; ?>
          </div>
        </div>

      </div>
    <?php endif; ?>
  </div>
</div>
<?php include '../../includes/footer.php'; ?>
