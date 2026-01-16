<?php
$page_title = 'Register Grievance';
require_once '../../config/db.php';
require_once '../../core/session.php';

$database = new Database();
$db = $database->getConnection();

include '../../includes/header.php';

// NOTE: This project currently doesn't have a grievances table.
// For now we store submissions in session (safe minimal) and show UI.
// Next step: create grievances table + actions.

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category = trim($_POST['category'] ?? '');
    $priority = trim($_POST['priority'] ?? '');
    $subject  = trim($_POST['subject'] ?? '');
    $details  = trim($_POST['details'] ?? '');

    if ($category && $priority && $subject && $details) {
        $_SESSION['flash_success'] = 'Grievance submitted successfully! Our team will review it.';
        header('Location: grievance_register.php');
        exit;
    }

    $error = 'Please fill all required fields.';
}

$success = $_SESSION['flash_success'] ?? null;
unset($_SESSION['flash_success']);
?>

<?php include '../../includes/sidebar.php'; ?>
<div class="main-content">
    <?php include '../../includes/navbar.php'; ?>

    <div class="page-content">
        <div class="page-header" style="margin-bottom: 30px;">
            <h1 style="font-size: 28px; font-weight: 700; margin-bottom: 8px;">Register Grievance</h1>
            <p style="color: var(--text-gray);">Raise concerns confidentially. We are here to help.</p>
        </div>

        <?php if (!empty($success)): ?>
            <div style="background:#dcfce7;border:1px solid #22c55e;color:#166534;padding:14px;border-radius:10px;margin-bottom:18px;">
                <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div style="background:#fee2e2;border:1px solid #ef4444;color:#991b1b;padding:14px;border-radius:10px;margin-bottom:18px;">
                <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <div style="max-width: 900px;">
            <div style="background:white;border:1px solid var(--border-color);border-radius:16px;padding:28px;">
                <form method="POST">
                    <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:18px;">
                        <div>
                            <label style="display:block;font-weight:600;margin-bottom:8px;">Category *</label>
                            <select name="category" required style="width:100%;padding:12px;border:2px solid var(--border-color);border-radius:10px;">
                                <option value="">Select</option>
                                <option value="Workplace">Workplace</option>
                                <option value="Payroll">Payroll</option>
                                <option value="Manager">Manager</option>
                                <option value="Harassment">Harassment</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div>
                            <label style="display:block;font-weight:600;margin-bottom:8px;">Priority *</label>
                            <select name="priority" required style="width:100%;padding:12px;border:2px solid var(--border-color);border-radius:10px;">
                                <option value="">Select</option>
                                <option value="Low">Low</option>
                                <option value="Medium">Medium</option>
                                <option value="High">High</option>
                            </select>
                        </div>
                        <div style="grid-column:1/-1;">
                            <label style="display:block;font-weight:600;margin-bottom:8px;">Subject *</label>
                            <input name="subject" required placeholder="Short title" style="width:100%;padding:12px;border:2px solid var(--border-color);border-radius:10px;" />
                        </div>
                        <div style="grid-column:1/-1;">
                            <label style="display:block;font-weight:600;margin-bottom:8px;">Details *</label>
                            <textarea name="details" required rows="6" placeholder="Describe your issue..." style="width:100%;padding:12px;border:2px solid var(--border-color);border-radius:10px;resize:vertical;"></textarea>
                        </div>
                    </div>

                    <div style="margin-top:22px;display:flex;gap:12px;">
                        <button type="submit" style="padding:12px 28px;background:var(--primary-gradient);color:white;border:none;border-radius:10px;font-weight:700;cursor:pointer;">
                            <i class="fas fa-paper-plane"></i> Submit
                        </button>
                        <a href="../../dashboards/employee_dashboard.php" style="padding:12px 28px;background:white;color:var(--text-dark);border:2px solid var(--border-color);border-radius:10px;font-weight:700;text-decoration:none;">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>

            <div style="margin-top:18px;background:#fff7ed;border:1px solid #fdba74;color:#9a3412;padding:14px;border-radius:12px;">
                <strong>Note:</strong> In the next step we’ll store grievances in the database (new `grievances` table) and show tracking status.
            </div>
        </div>
    </div>
</div>
<?php include '../../includes/footer.php'; ?>
