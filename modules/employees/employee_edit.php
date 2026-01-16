<?php
$page_title = 'Edit Profile';
require_once '../../config/db.php';
require_once '../../core/session.php';

$database = new Database();
$db = $database->getConnection();

include '../../includes/header.php';

$emp_id = $_GET['id'] ?? $employee_id;

$stmt = $db->prepare("SELECT * FROM employees WHERE id=:id");
$stmt->execute([':id'=>$emp_id]);
$employee = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$employee) {
    header('Location: employee_view.php?id='.(int)$employee_id);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');

    $up = $db->prepare("UPDATE employees SET phone=:p, address=:a WHERE id=:id");
    $up->execute([':p'=>$phone, ':a'=>$address, ':id'=>$emp_id]);

    header('Location: employee_view.php?id='.(int)$emp_id);
    exit;
}
?>

<?php include '../../includes/sidebar.php'; ?>
<div class="main-content">
    <?php include '../../includes/navbar.php'; ?>

    <div class="page-content">
        <div class="page-header" style="margin-bottom:22px;">
            <h1 style="font-size:28px;font-weight:700;margin-bottom:8px;">Edit Profile</h1>
            <p style="color:var(--text-gray);">Update your contact details</p>
        </div>

        <div style="max-width:900px;">
            <div style="background:#fff;border:1px solid var(--border-color);border-radius:16px;padding:24px;">
                <form method="POST">
                    <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:16px;">
                        <div>
                            <label style="display:block;font-weight:700;margin-bottom:8px;">Full Name</label>
                            <input disabled value="<?php echo htmlspecialchars($employee['full_name']); ?>" style="width:100%;padding:12px;border:2px solid var(--border-color);border-radius:10px;background:#f9fafb;" />
                        </div>
                        <div>
                            <label style="display:block;font-weight:700;margin-bottom:8px;">Employee ID</label>
                            <input disabled value="<?php echo htmlspecialchars($employee['employee_id']); ?>" style="width:100%;padding:12px;border:2px solid var(--border-color);border-radius:10px;background:#f9fafb;" />
                        </div>
                        <div>
                            <label style="display:block;font-weight:700;margin-bottom:8px;">Phone</label>
                            <input name="phone" value="<?php echo htmlspecialchars($employee['phone'] ?? ''); ?>" style="width:100%;padding:12px;border:2px solid var(--border-color);border-radius:10px;" />
                        </div>
                        <div>
                            <label style="display:block;font-weight:700;margin-bottom:8px;">Email</label>
                            <input disabled value="<?php echo htmlspecialchars($email); ?>" style="width:100%;padding:12px;border:2px solid var(--border-color);border-radius:10px;background:#f9fafb;" />
                        </div>
                        <div style="grid-column:1/-1;">
                            <label style="display:block;font-weight:700;margin-bottom:8px;">Address</label>
                            <textarea name="address" rows="5" style="width:100%;padding:12px;border:2px solid var(--border-color);border-radius:10px;resize:vertical;" ><?php echo htmlspecialchars($employee['address'] ?? ''); ?></textarea>
                        </div>
                    </div>

                    <div style="margin-top:18px;display:flex;gap:12px;flex-wrap:wrap;">
                        <button type="submit" style="padding:12px 18px;background:var(--primary-gradient);color:#fff;border:none;border-radius:10px;font-weight:800;cursor:pointer;">
                            <i class="fas fa-save"></i> Save Changes
                        </button>
                        <a href="employee_view.php?id=<?php echo (int)$emp_id; ?>" style="padding:12px 18px;background:#fff;border:2px solid var(--border-color);border-radius:10px;text-decoration:none;color:var(--text-dark);font-weight:800;">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include '../../includes/footer.php'; ?>
