<?php
$page_title = 'Salary Structure';
require_once '../../config/db.php';
require_once '../../core/session.php';

$database = new Database();
$db = $database->getConnection();

include '../../includes/header.php';

// Placeholder numbers (until payroll tables added)
$basic = 25000;
$hra = 10000;
$allowances = 8000;
$gross = $basic + $hra + $allowances;
$pf = 1800;
$tax = 3200;
$deductions = $pf + $tax;
$net = $gross - $deductions;
?>

<?php include '../../includes/sidebar.php'; ?>
<div class="main-content">
    <?php include '../../includes/navbar.php'; ?>

    <div class="page-content">
        <div class="page-header" style="margin-bottom:22px;">
            <h1 style="font-size:28px;font-weight:700;margin-bottom:8px;">Salary Structure</h1>
            <p style="color:var(--text-gray);">A breakdown of your salary components</p>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
            <div style="background:#fff;border:1px solid var(--border-color);border-radius:16px;padding:22px;">
                <h3 style="margin:0 0 14px 0;font-size:18px;font-weight:800;">Earnings</h3>
                <div style="display:grid;gap:10px;">
                    <div style="display:flex;justify-content:space-between;"><span>Basic</span><strong>₹ <?php echo number_format($basic); ?></strong></div>
                    <div style="display:flex;justify-content:space-between;"><span>HRA</span><strong>₹ <?php echo number_format($hra); ?></strong></div>
                    <div style="display:flex;justify-content:space-between;"><span>Allowances</span><strong>₹ <?php echo number_format($allowances); ?></strong></div>
                    <div style="height:1px;background:#eef2f7;margin:8px 0;"></div>
                    <div style="display:flex;justify-content:space-between;font-size:16px;"><span style="font-weight:800;">Gross</span><strong style="color:var(--success);">₹ <?php echo number_format($gross); ?></strong></div>
                </div>
            </div>

            <div style="background:#fff;border:1px solid var(--border-color);border-radius:16px;padding:22px;">
                <h3 style="margin:0 0 14px 0;font-size:18px;font-weight:800;">Deductions</h3>
                <div style="display:grid;gap:10px;">
                    <div style="display:flex;justify-content:space-between;"><span>PF</span><strong>₹ <?php echo number_format($pf); ?></strong></div>
                    <div style="display:flex;justify-content:space-between;"><span>Tax</span><strong>₹ <?php echo number_format($tax); ?></strong></div>
                    <div style="height:1px;background:#eef2f7;margin:8px 0;"></div>
                    <div style="display:flex;justify-content:space-between;font-size:16px;"><span style="font-weight:800;">Total Deductions</span><strong style="color:var(--error);">₹ <?php echo number_format($deductions); ?></strong></div>
                </div>
            </div>

            <div style="grid-column:1/-1;background:var(--primary-gradient);color:#fff;border-radius:16px;padding:22px;display:flex;justify-content:space-between;align-items:center;gap:14px;flex-wrap:wrap;">
                <div>
                    <div style="opacity:.9;font-size:14px;margin-bottom:6px;">Estimated Net Pay</div>
                    <div style="font-size:40px;font-weight:900;line-height:1;">₹ <?php echo number_format($net); ?></div>
                </div>
                <a href="/modules/payroll/payslip_download.php" style="padding:12px 16px;background:rgba(255,255,255,.18);border:1px solid rgba(255,255,255,.25);border-radius:12px;color:#fff;text-decoration:none;font-weight:800;">
                    <i class="fas fa-file-invoice"></i> View Payslips
                </a>
            </div>
        </div>
    </div>
</div>
<?php include '../../includes/footer.php'; ?>
