<?php
/**
 * Download Payslips Page
 */

$page_title = 'My Payslips';

require_once '../../config/db.php';
require_once '../../core/session.php';

$database = new Database();
$db = $database->getConnection();

include '../../includes/header.php';

// Sample payslips data (in production, fetch from payroll table)
$payslips = [
    ['month' => 'January 2025', 'salary' => 50000, 'deductions' => 5000, 'net' => 45000, 'status' => 'Paid'],
    ['month' => 'December 2024', 'salary' => 50000, 'deductions' => 5000, 'net' => 45000, 'status' => 'Paid'],
    ['month' => 'November 2024', 'salary' => 50000, 'deductions' => 5000, 'net' => 45000, 'status' => 'Paid'],
    ['month' => 'October 2024', 'salary' => 50000, 'deductions' => 5000, 'net' => 45000, 'status' => 'Paid'],
];
?>

<?php include '../../includes/sidebar.php'; ?>

<div class="main-content">
    <?php include '../../includes/navbar.php'; ?>
    
    <div class="page-content">
        <div class="page-header" style="margin-bottom: 30px;">
            <h1 style="font-size: 28px; font-weight: 700; margin-bottom: 8px;">My Payslips</h1>
            <p style="color: var(--text-gray);">View and download your salary slips</p>
        </div>
        
        <!-- Current Month Salary Card -->
        <div style="background: var(--primary-gradient); color: white; padding: 30px; border-radius: 16px; margin-bottom: 30px;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <p style="opacity: 0.9; margin-bottom: 8px; font-size: 14px;">Current Month Salary</p>
                    <h2 style="font-size: 42px; font-weight: 700; margin: 0;">₹ 45,000</h2>
                    <p style="opacity: 0.9; margin-top: 8px;">January 2025 • Net Pay</p>
                </div>
                <div style="text-align: right;">
                    <div style="background: rgba(255,255,255,0.2); padding: 16px 24px; border-radius: 12px; margin-bottom: 12px;">
                        <p style="font-size: 14px; margin-bottom: 4px;">Gross Salary</p>
                        <p style="font-size: 24px; font-weight: 700; margin: 0;">₹ 50,000</p>
                    </div>
                    <div style="background: rgba(255,255,255,0.2); padding: 16px 24px; border-radius: 12px;">
                        <p style="font-size: 14px; margin-bottom: 4px;">Deductions</p>
                        <p style="font-size: 24px; font-weight: 700; margin: 0;">₹ 5,000</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Payslips List -->
        <div style="background: white; border-radius: 16px; border: 1px solid var(--border-color); overflow: hidden;">
            <div style="padding: 24px; border-bottom: 1px solid var(--border-color);">
                <h3 style="font-size: 18px; font-weight: 700; margin: 0;">Payslip History</h3>
            </div>
            
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead style="background: var(--bg-light);">
                        <tr>
                            <th style="padding: 16px; text-align: left; font-weight: 600; font-size: 14px;">Month</th>
                            <th style="padding: 16px; text-align: left; font-weight: 600; font-size: 14px;">Gross Salary</th>
                            <th style="padding: 16px; text-align: left; font-weight: 600; font-size: 14px;">Deductions</th>
                            <th style="padding: 16px; text-align: left; font-weight: 600; font-size: 14px;">Net Pay</th>
                            <th style="padding: 16px; text-align: left; font-weight: 600; font-size: 14px;">Status</th>
                            <th style="padding: 16px; text-align: left; font-weight: 600; font-size: 14px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($payslips as $slip): ?>
                            <tr style="border-bottom: 1px solid var(--border-color);">
                                <td style="padding: 16px; font-weight: 600;"><?php echo $slip['month']; ?></td>
                                <td style="padding: 16px;">₹ <?php echo number_format($slip['salary']); ?></td>
                                <td style="padding: 16px; color: var(--error);">₹ <?php echo number_format($slip['deductions']); ?></td>
                                <td style="padding: 16px; font-weight: 700; color: var(--success);">₹ <?php echo number_format($slip['net']); ?></td>
                                <td style="padding: 16px;">
                                    <span style="padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; background: #dcfce7; color: #22c55e;">
                                        <?php echo $slip['status']; ?>
                                    </span>
                                </td>
                                <td style="padding: 16px;">
                                    <button style="padding: 8px 16px; background: var(--primary-color); color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 13px; margin-right: 8px;">
                                        <i class="fas fa-download"></i> Download
                                    </button>
                                    <button style="padding: 8px 16px; background: white; color: var(--text-dark); border: 2px solid var(--border-color); border-radius: 8px; cursor: pointer; font-size: 13px;">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
