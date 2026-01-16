<?php
/**
 * Attendance List Page - View attendance records
 */

// Set page title
$page_title = 'My Attendance';

// Include database and session
require_once '../../config/db.php';
require_once '../../core/session.php';

// Initialize database
$database = new Database();
$db = $database->getConnection();

// Include header (which includes session check)
include '../../includes/header.php';

// Get current month and year
$current_month = $_GET['month'] ?? date('m');
$current_year = $_GET['year'] ?? date('Y');

// Fetch attendance records for current month
try {
    $query = "SELECT * FROM attendance 
              WHERE employee_id = :emp_id 
              AND MONTH(attendance_date) = :month 
              AND YEAR(attendance_date) = :year 
              ORDER BY attendance_date DESC";
    
    $stmt = $db->prepare($query);
    $stmt->execute([
        ':emp_id' => $employee_id,
        ':month' => $current_month,
        ':year' => $current_year
    ]);
    
    $attendance_records = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Calculate statistics
    $total_present = 0;
    $total_absent = 0;
    $total_halfday = 0;
    $total_leave = 0;
    
    foreach ($attendance_records as $record) {
        switch ($record['status']) {
            case 'Present':
                $total_present++;
                break;
            case 'Absent':
                $total_absent++;
                break;
            case 'Half Day':
                $total_halfday++;
                break;
            case 'Leave':
                $total_leave++;
                break;
        }
    }
    
} catch(PDOException $e) {
    $error = "Error fetching attendance: " . $e->getMessage();
    $attendance_records = [];
}
?>

<!-- Include Sidebar -->
<?php include '../../includes/sidebar.php'; ?>

<!-- Main Content -->
<div class="main-content">
    <!-- Include Navbar -->
    <?php include '../../includes/navbar.php'; ?>
    
    <!-- Page Content -->
    <div class="page-content">
        
        <!-- Page Header -->
        <div class="page-header" style="margin-bottom: 30px;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h1 style="font-size: 28px; font-weight: 700; color: var(--text-dark); margin-bottom: 8px;">My Attendance</h1>
                    <p style="color: var(--text-gray); font-size: 14px;">View and track your attendance records</p>
                </div>
                <div style="display: flex; gap: 12px;">
                    <a href="attendance_mark.php" class="btn-primary" style="display: inline-flex; align-items: center; gap: 8px; padding: 12px 20px; background: var(--primary-gradient); color: white; text-decoration: none; border-radius: 10px; font-weight: 600;">
                        <i class="fas fa-clock"></i>
                        Mark Attendance
                    </a>
                    <a href="attendance_report.php" class="btn-secondary" style="display: inline-flex; align-items: center; gap: 8px; padding: 12px 20px; background: white; color: var(--text-dark); text-decoration: none; border-radius: 10px; border: 2px solid var(--border-color); font-weight: 600;">
                        <i class="fas fa-download"></i>
                        Download Report
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Statistics Cards -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
            <!-- Present Days -->
            <div style="background: white; padding: 24px; border-radius: 16px; border: 1px solid var(--border-color);">
                <div style="display: flex; justify-content: space-between; align-items: start;">
                    <div>
                        <p style="color: var(--text-gray); font-size: 14px; margin-bottom: 8px;">Present Days</p>
                        <h2 style="font-size: 32px; font-weight: 700; color: var(--success); margin: 0;"><?php echo $total_present; ?></h2>
                        <p style="color: var(--text-gray); font-size: 12px; margin-top: 4px;">This month</p>
                    </div>
                    <div style="width: 50px; height: 50px; background: #dcfce7; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                        ✓
                    </div>
                </div>
            </div>
            
            <!-- Absent Days -->
            <div style="background: white; padding: 24px; border-radius: 16px; border: 1px solid var(--border-color);">
                <div style="display: flex; justify-content: space-between; align-items: start;">
                    <div>
                        <p style="color: var(--text-gray); font-size: 14px; margin-bottom: 8px;">Absent Days</p>
                        <h2 style="font-size: 32px; font-weight: 700; color: var(--error); margin: 0;"><?php echo $total_absent; ?></h2>
                        <p style="color: var(--text-gray); font-size: 12px; margin-top: 4px;">This month</p>
                    </div>
                    <div style="width: 50px; height: 50px; background: #fee2e2; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                        ✗
                    </div>
                </div>
            </div>
            
            <!-- Half Days -->
            <div style="background: white; padding: 24px; border-radius: 16px; border: 1px solid var(--border-color);">
                <div style="display: flex; justify-content: space-between; align-items: start;">
                    <div>
                        <p style="color: var(--text-gray); font-size: 14px; margin-bottom: 8px;">Half Days</p>
                        <h2 style="font-size: 32px; font-weight: 700; color: var(--warning); margin: 0;"><?php echo $total_halfday; ?></h2>
                        <p style="color: var(--text-gray); font-size: 12px; margin-top: 4px;">This month</p>
                    </div>
                    <div style="width: 50px; height: 50px; background: #fef3c7; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                        ◑
                    </div>
                </div>
            </div>
            
            <!-- On Leave -->
            <div style="background: white; padding: 24px; border-radius: 16px; border: 1px solid var(--border-color);">
                <div style="display: flex; justify-content: space-between; align-items: start;">
                    <div>
                        <p style="color: var(--text-gray); font-size: 14px; margin-bottom: 8px;">On Leave</p>
                        <h2 style="font-size: 32px; font-weight: 700; color: var(--info); margin: 0;"><?php echo $total_leave; ?></h2>
                        <p style="color: var(--text-gray); font-size: 12px; margin-top: 4px;">This month</p>
                    </div>
                    <div style="width: 50px; height: 50px; background: #dbeafe; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                        📅
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Attendance Table -->
        <div style="background: white; border-radius: 16px; border: 1px solid var(--border-color); overflow: hidden;">
            <div style="padding: 24px; border-bottom: 1px solid var(--border-color);">
                <h3 style="font-size: 18px; font-weight: 700; margin: 0;">Attendance Records</h3>
                <p style="color: var(--text-gray); font-size: 14px; margin-top: 4px;">
                    <?php echo date('F Y', mktime(0, 0, 0, $current_month, 1, $current_year)); ?>
                </p>
            </div>
            
            <div style="overflow-x: auto;">
                <table class="table" style="width: 100%; border-collapse: collapse;">
                    <thead style="background: var(--bg-light);">
                        <tr>
                            <th style="padding: 16px; text-align: left; font-weight: 600; color: var(--text-dark); font-size: 14px;">Date</th>
                            <th style="padding: 16px; text-align: left; font-weight: 600; color: var(--text-dark); font-size: 14px;">Day</th>
                            <th style="padding: 16px; text-align: left; font-weight: 600; color: var(--text-dark); font-size: 14px;">Check In</th>
                            <th style="padding: 16px; text-align: left; font-weight: 600; color: var(--text-dark); font-size: 14px;">Check Out</th>
                            <th style="padding: 16px; text-align: left; font-weight: 600; color: var(--text-dark); font-size: 14px;">Status</th>
                            <th style="padding: 16px; text-align: left; font-weight: 600; color: var(--text-dark); font-size: 14px;">Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($attendance_records) > 0): ?>
                            <?php foreach ($attendance_records as $record): ?>
                                <tr style="border-bottom: 1px solid var(--border-color);">
                                    <td style="padding: 16px; font-size: 14px;">
                                        <?php echo date('d M Y', strtotime($record['attendance_date'])); ?>
                                    </td>
                                    <td style="padding: 16px; font-size: 14px;">
                                        <?php echo date('l', strtotime($record['attendance_date'])); ?>
                                    </td>
                                    <td style="padding: 16px; font-size: 14px;">
                                        <?php echo $record['check_in'] ? date('h:i A', strtotime($record['check_in'])) : '-'; ?>
                                    </td>
                                    <td style="padding: 16px; font-size: 14px;">
                                        <?php echo $record['check_out'] ? date('h:i A', strtotime($record['check_out'])) : '-'; ?>
                                    </td>
                                    <td style="padding: 16px;">
                                        <?php
                                        $status_colors = [
                                            'Present' => ['bg' => '#dcfce7', 'text' => '#22c55e'],
                                            'Absent' => ['bg' => '#fee2e2', 'text' => '#ef4444'],
                                            'Half Day' => ['bg' => '#fef3c7', 'text' => '#f59e0b'],
                                            'Leave' => ['bg' => '#dbeafe', 'text' => '#3b82f6'],
                                        ];
                                        $color = $status_colors[$record['status']] ?? ['bg' => '#f3f4f6', 'text' => '#6b7280'];
                                        ?>
                                        <span style="padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; background: <?php echo $color['bg']; ?>; color: <?php echo $color['text']; ?>;">
                                            <?php echo $record['status']; ?>
                                        </span>
                                    </td>
                                    <td style="padding: 16px; font-size: 14px; color: var(--text-gray);">
                                        <?php echo $record['remarks'] ?? '-'; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" style="padding: 40px; text-align: center; color: var(--text-gray);">
                                    <i class="fas fa-calendar-times" style="font-size: 48px; opacity: 0.3; margin-bottom: 16px; display: block;"></i>
                                    No attendance records found for this month.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
