<?php
$page_title = 'Settings';
require_once '../../config/db.php';
require_once '../../core/session.php';

$database = new Database();
$db = $database->getConnection();

include '../../includes/header.php';

$success = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $success = 'Settings saved successfully!';
}
?>

<?php include '../../includes/sidebar.php'; ?>
<div class="main-content">
    <?php include '../../includes/navbar.php'; ?>

    <div class="page-content">
        <div class="page-header" style="margin-bottom: 30px;">
            <h1 style="font-size: 28px; font-weight: 700; margin-bottom: 8px;">Settings</h1>
            <p style="color: var(--text-gray);">Manage preferences for your HRMS account</p>
        </div>

        <?php if ($success): ?>
            <div style="background:#dcfce7;border:1px solid #22c55e;color:#166534;padding:14px;border-radius:10px;margin-bottom:18px;">
                <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <div style="display:grid;grid-template-columns:1fr;gap:20px;max-width:900px;">
            <div style="background:white;border:1px solid var(--border-color);border-radius:16px;padding:24px;">
                <h3 style="margin:0 0 14px 0;font-size:18px;font-weight:800;">Account Settings</h3>
                <form method="POST">
                    <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:16px;">
                        <div>
                            <label style="display:block;font-weight:600;margin-bottom:8px;">Full Name</label>
                            <input value="<?php echo htmlspecialchars($full_name); ?>" disabled style="width:100%;padding:12px;border:2px solid var(--border-color);border-radius:10px;background:#f9fafb;" />
                        </div>
                        <div>
                            <label style="display:block;font-weight:600;margin-bottom:8px;">Email</label>
                            <input value="<?php echo htmlspecialchars($email); ?>" disabled style="width:100%;padding:12px;border:2px solid var(--border-color);border-radius:10px;background:#f9fafb;" />
                        </div>
                        <div>
                            <label style="display:block;font-weight:600;margin-bottom:8px;">Theme</label>
                            <select name="theme" style="width:100%;padding:12px;border:2px solid var(--border-color);border-radius:10px;">
                                <option value="default">Default (Purple)</option>
                            </select>
                        </div>
                        <div>
                            <label style="display:block;font-weight:600;margin-bottom:8px;">Notifications</label>
                            <select name="notifications" style="width:100%;padding:12px;border:2px solid var(--border-color);border-radius:10px;">
                                <option value="enabled">Enabled</option>
                                <option value="disabled">Disabled</option>
                            </select>
                        </div>
                    </div>

                    <div style="margin-top:18px;">
                        <button type="submit" style="padding:12px 28px;background:var(--primary-gradient);color:white;border:none;border-radius:10px;font-weight:800;cursor:pointer;">
                            <i class="fas fa-save"></i> Save Settings
                        </button>
                    </div>
                </form>
            </div>

            <div style="background:white;border:1px solid var(--border-color);border-radius:16px;padding:24px;">
                <h3 style="margin:0 0 14px 0;font-size:18px;font-weight:800;">Security</h3>
                <p style="color:var(--text-gray);margin:0 0 14px 0;">For security actions, use Logout when you finish using the HRMS.</p>
                <a href="../../auth/logout.php" style="display:inline-flex;align-items:center;gap:10px;padding:12px 18px;border-radius:10px;background:#fee2e2;color:#b91c1c;text-decoration:none;font-weight:800;border:1px solid #fecaca;">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </div>
</div>
<?php include '../../includes/footer.php'; ?>
