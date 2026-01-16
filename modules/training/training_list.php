<?php
$page_title = 'Training Programs';
require_once '../../config/db.php';
require_once '../../core/session.php';
$database = new Database();
$db = $database->getConnection();
include '../../includes/header.php';

$trainings = [
    ['title' => 'Leadership Skills', 'date' => '2025-02-15', 'duration' => '3 days', 'status' => 'Upcoming'],
    ['title' => 'Technical Workshop', 'date' => '2025-01-20', 'duration' => '2 days', 'status' => 'Enrolled'],
    ['title' => 'Communication Skills', 'date' => '2024-12-10', 'duration' => '1 day', 'status' => 'Completed'],
];
?>

<?php include '../../includes/sidebar.php'; ?>
<div class="main-content">
    <?php include '../../includes/navbar.php'; ?>
    <div class="page-content">
        <div class="page-header" style="margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 style="font-size: 28px; font-weight: 700; margin-bottom: 8px;">Training Programs</h1>
                <p style="color: var(--text-gray);">Explore and enroll in training courses</p>
            </div>
        </div>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 20px;">
            <?php foreach ($trainings as $training): ?>
                <div style="background: white; padding: 24px; border-radius: 16px; border: 1px solid var(--border-color);">
                    <div style="width: 60px; height: 60px; background: var(--primary-gradient); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 16px; font-size: 28px; color: white;">
                        🎓
                    </div>
                    <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 12px;"><?php echo $training['title']; ?></h3>
                    <div style="display: flex; gap: 16px; margin-bottom: 16px; font-size: 14px; color: var(--text-gray);">
                        <span><i class="fas fa-calendar"></i> <?php echo date('d M Y', strtotime($training['date'])); ?></span>
                        <span><i class="fas fa-clock"></i> <?php echo $training['duration']; ?></span>
                    </div>
                    <span style="padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; background: #dcfce7; color: #22c55e; display: inline-block; margin-bottom: 16px;">
                        <?php echo $training['status']; ?>
                    </span>
                    <button style="width: 100%; padding: 12px; background: var(--primary-color); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                        View Details
                    </button>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php include '../../includes/footer.php'; ?>
