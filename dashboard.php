<?php
// teacher/dashboard.php
require '../config/db.php';
require '../includes/functions.php';

requireLogin(); // Teacher or Admin can view

// Stats
$total_students = $pdo->query("SELECT COUNT(*) FROM students")->fetchColumn();
$male_students = $pdo->query("SELECT COUNT(*) FROM students WHERE gender = 'Male'")->fetchColumn();
$female_students = $pdo->query("SELECT COUNT(*) FROM students WHERE gender = 'Female'")->fetchColumn();

// Grade Counts
$grades = $pdo->query("SELECT grade, COUNT(*) as count FROM students GROUP BY grade ORDER BY grade")->fetchAll();

$path_level = 1;
include '../includes/header.php';
?>

<div class="dashboard-container">
    <div style="margin-bottom: 20px;">
        <h2>Staff Dashboard</h2>
        <p>Welcome, <?php echo $_SESSION['full_name']; ?></p>
    </div>

     <!-- Stats Cards -->
     <div class="card-grid">
        <div class="card" style="border-left: 5px solid var(--primary-color);">
            <h3>Total Students</h3>
            <p style="font-size: 2rem; font-weight: bold;"><?php echo $total_students; ?></p>
        </div>
        <div class="card" style="border-left: 5px solid var(--secondary-color);">
            <h3>Boys</h3>
            <p style="font-size: 2rem; font-weight: bold;"><?php echo $male_students; ?></p>
        </div>
        <div class="card" style="border-left: 5px solid #e83e8c;">
            <h3>Girls</h3>
            <p style="font-size: 2rem; font-weight: bold;"><?php echo $female_students; ?></p>
        </div>
         <div class="card" style="background-color: var(--primary-color); color: white; display: flex; align-items: center; justify-content: center;">
            <a href="students.php" style="color: white; text-decoration: none; font-size: 1.2rem; font-weight: bold;">
                <i class="fas fa-search"></i> Search Directory
            </a>
        </div>
    </div>

    <!-- Grade Overview Chart (Simple HTML/CSS Bar Chart) -->
    <div class="card" style="margin-top: 30px;">
        <h3>Student Distribution by Grade</h3>
        <div style="margin-top: 20px; display: flex; align-items: flex-end; height: 200px; gap: 10px;">
            <?php 
                $max_count = 0;
                foreach($grades as $g) $max_count = max($max_count, $g['count']);
                $max_count = $max_count ?: 1; // avoid div by zero
            ?>
            <?php foreach ($grades as $g): ?>
                <div style="flex: 1; display: flex; flex-direction: column; align-items: center;">
                    <div style="width: 100%; text-align: center; font-weight: bold; margin-bottom: 5px;"><?php echo $g['count']; ?></div>
                    <div style="width: 100%; background-color: var(--primary-color); height: <?php echo ($g['count']/$max_count)*150; ?>px; border-radius: 4px 4px 0 0;"></div>
                    <div style="margin-top: 5px; font-weight: bold;"><?php echo $g['grade']; ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
