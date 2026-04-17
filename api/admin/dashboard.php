<?php
// admin/dashboard.php
require '../config/db.php';
require '../includes/functions.php';

requireAdmin();

// Calculate Stats
$total_students = $pdo->query("SELECT COUNT(*) FROM students")->fetchColumn();
$male_students = $pdo->query("SELECT COUNT(*) FROM students WHERE gender = 'Male'")->fetchColumn();
$female_students = $pdo->query("SELECT COUNT(*) FROM students WHERE gender = 'Female'")->fetchColumn();
$total_users = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();

// Get Recent Students
$recent_students = $pdo->query("SELECT * FROM students ORDER BY created_at DESC LIMIT 5")->fetchAll();

$path_level = 1;
include '../includes/header.php';
?>

<div class="dashboard-container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2>Admin Dashboard</h2>
        <div class="dashboard-actions">
            <a href="add_student.php" class="btn"><i class="fas fa-plus"></i> Add Student</a>
            <a href="users.php" class="btn btn-secondary"><i class="fas fa-users-cog"></i> Manage Users</a>
        </div>
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
        <div class="card" style="border-left: 5px solid var(--success);">
            <h3>System Users</h3>
            <p style="font-size: 2rem; font-weight: bold;"><?php echo $total_users; ?></p>
        </div>
    </div>

    <!-- Recent Students Table -->
    <div style="margin-top: 40px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
            <h3>Recently Added Students</h3>
            <a href="students.php" style="color: var(--primary-color); font-weight: bold;">View All</a>
        </div>
        
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Admission No</th>
                        <th>Name</th>
                        <th>Grade</th>
                        <th>Gender</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent_students as $student): ?>
                    <tr>
                        <td><?php echo sanitize($student['admission_no']); ?></td>
                        <td><?php echo sanitize($student['name_with_initials']); ?></td>
                        <td><?php echo sanitize($student['grade']) . '-' . sanitize($student['class']); ?></td>
                        <td><?php echo sanitize($student['gender']); ?></td>
                        <td>
                            <span class="badge <?php echo strtolower($student['status']); ?>">
                                <?php echo sanitize($student['status']); ?>
                            </span>
                        </td>
                        <td>
                            <a href="edit_student.php?id=<?php echo $student['id']; ?>" class="btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($recent_students)): ?>
                        <tr><td colspan="6" style="text-align:center;">No students found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
