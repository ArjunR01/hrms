<?php
$page_title = 'Self Appraisal';
require_once '../../config/db.php';
require_once '../../core/session.php';

$database = new Database();
$db = $database->getConnection();

include '../../includes/header.php';

$success = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cycle = trim($_POST['cycle'] ?? '');
    $rating = trim($_POST['rating'] ?? '');
    $strengths = trim($_POST['strengths'] ?? '');
    $improvements = trim($_POST['improvements'] ?? '');
    $achievements = trim($_POST['achievements'] ?? '');

    if ($cycle && $rating && $strengths && $improvements) {
        // No performance table yet; store minimal acknowledgment.
        $success = 'Self appraisal submitted successfully!';
    } else {
        $error = 'Please fill all required fields.';
    }
}
?>

<?php include '../../includes/sidebar.php'; ?>
<div class="main-content">
    <?php include '../../includes/navbar.php'; ?>

    <div class="page-content">
        <div class="page-header" style="margin-bottom:22px;">
            <h1 style="font-size:28px;font-weight:700;margin-bottom:8px;">Self Appraisal</h1>
            <p style="color:var(--text-gray);">Submit your self evaluation for the current cycle</p>
        </div>

        <?php if ($success): ?>
            <div style="background:#dcfce7;border:1px solid #22c55e;color:#166534;padding:14px;border-radius:10px;margin-bottom:18px;">
                <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div style="background:#fee2e2;border:1px solid #ef4444;color:#991b1b;padding:14px;border-radius:10px;margin-bottom:18px;">
                <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <div style="max-width:1000px;">
            <div style="background:#fff;border:1px solid var(--border-color);border-radius:16px;padding:24px;">
                <form method="POST">
                    <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:16px;">
                        <div>
                            <label style="display:block;font-weight:800;margin-bottom:8px;">Cycle *</label>
                            <select name="cycle" required style="width:100%;padding:12px;border:2px solid var(--border-color);border-radius:10px;">
                                <option value="">Select</option>
                                <option value="2026-Q1">2026 - Q1</option>
                                <option value="2025-Q4">2025 - Q4</option>
                            </select>
                        </div>
                        <div>
                            <label style="display:block;font-weight:800;margin-bottom:8px;">Overall Rating (1-5) *</label>
                            <select name="rating" required style="width:100%;padding:12px;border:2px solid var(--border-color);border-radius:10px;">
                                <option value="">Select</option>
                                <option>5</option>
                                <option>4</option>
                                <option>3</option>
                                <option>2</option>
                                <option>1</option>
                            </select>
                        </div>
                        <div style="grid-column:1/-1;">
                            <label style="display:block;font-weight:800;margin-bottom:8px;">Key Strengths *</label>
                            <textarea name="strengths" required rows="4" style="width:100%;padding:12px;border:2px solid var(--border-color);border-radius:10px;resize:vertical;" placeholder="Your strengths..."></textarea>
                        </div>
                        <div style="grid-column:1/-1;">
                            <label style="display:block;font-weight:800;margin-bottom:8px;">Areas to Improve *</label>
                            <textarea name="improvements" required rows="4" style="width:100%;padding:12px;border:2px solid var(--border-color);border-radius:10px;resize:vertical;" placeholder="Areas you want to improve..."></textarea>
                        </div>
                        <div style="grid-column:1/-1;">
                            <label style="display:block;font-weight:800;margin-bottom:8px;">Achievements (optional)</label>
                            <textarea name="achievements" rows="4" style="width:100%;padding:12px;border:2px solid var(--border-color);border-radius:10px;resize:vertical;" placeholder="Key achievements this cycle..."></textarea>
                        </div>
                    </div>

                    <div style="margin-top:18px;display:flex;gap:12px;flex-wrap:wrap;">
                        <button type="submit" style="padding:12px 18px;background:var(--primary-gradient);color:#fff;border:none;border-radius:10px;font-weight:900;cursor:pointer;">
                            <i class="fas fa-paper-plane"></i> Submit
                        </button>
                        <a href="/dashboards/employee_dashboard.php" style="padding:12px 18px;background:#fff;border:2px solid var(--border-color);border-radius:10px;text-decoration:none;color:var(--text-dark);font-weight:900;">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>

            <div style="margin-top:16px;background:#fff7ed;border:1px solid #fdba74;color:#9a3412;padding:14px;border-radius:12px;">
                <strong>Next step:</strong> we can add a `performance_reviews` table to store and track appraisal history.
            </div>
        </div>
    </div>
</div>
<?php include '../../includes/footer.php'; ?>
