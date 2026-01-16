<?php
$page_title = 'Submit Expense Claim';
require_once '../../config/db.php';
require_once '../../core/session.php';
$database = new Database();
$db = $database->getConnection();
include '../../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $success = "Expense claim submitted successfully!";
}
?>

<?php include '../../includes/sidebar.php'; ?>
<div class="main-content">
    <?php include '../../includes/navbar.php'; ?>
    <div class="page-content">
        <div class="page-header" style="margin-bottom: 30px;">
            <h1 style="font-size: 28px; font-weight: 700; margin-bottom: 8px;">Submit Expense Claim</h1>
            <p style="color: var(--text-gray);">Claim your business expenses</p>
        </div>
        
        <?php if (isset($success)): ?>
            <div style="background: #dcfce7; border: 1px solid #22c55e; color: #22c55e; padding: 16px; border-radius: 10px; margin-bottom: 20px;">
                <i class="fas fa-check-circle"></i> <?php echo $success; ?>
            </div>
        <?php endif; ?>
        
        <div style="max-width: 800px;">
            <div style="background: white; padding: 30px; border-radius: 16px; border: 1px solid var(--border-color);">
                <form method="POST" enctype="multipart/form-data">
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                        <div>
                            <label style="display: block; font-weight: 600; margin-bottom: 8px;">Expense Type *</label>
                            <select required style="width: 100%; padding: 12px; border: 2px solid var(--border-color); border-radius: 10px;">
                                <option value="">Select Type</option>
                                <option>Travel</option>
                                <option>Food</option>
                                <option>Accommodation</option>
                                <option>Others</option>
                            </select>
                        </div>
                        <div>
                            <label style="display: block; font-weight: 600; margin-bottom: 8px;">Amount *</label>
                            <input type="number" required placeholder="Enter amount" style="width: 100%; padding: 12px; border: 2px solid var(--border-color); border-radius: 10px;">
                        </div>
                        <div>
                            <label style="display: block; font-weight: 600; margin-bottom: 8px;">Date *</label>
                            <input type="date" required style="width: 100%; padding: 12px; border: 2px solid var(--border-color); border-radius: 10px;">
                        </div>
                        <div>
                            <label style="display: block; font-weight: 600; margin-bottom: 8px;">Upload Bill *</label>
                            <input type="file" required style="width: 100%; padding: 12px; border: 2px solid var(--border-color); border-radius: 10px;">
                        </div>
                        <div style="grid-column: 1 / -1;">
                            <label style="display: block; font-weight: 600; margin-bottom: 8px;">Description *</label>
                            <textarea required rows="4" placeholder="Enter expense description..." style="width: 100%; padding: 12px; border: 2px solid var(--border-color); border-radius: 10px; resize: vertical;"></textarea>
                        </div>
                    </div>
                    <div style="margin-top: 30px;">
                        <button type="submit" style="padding: 12px 30px; background: var(--primary-gradient); color: white; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">
                            <i class="fas fa-paper-plane"></i> Submit Claim
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include '../../includes/footer.php'; ?>
