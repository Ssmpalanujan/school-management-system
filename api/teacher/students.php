<?php
// teacher/students.php
require '../config/db.php';
require '../includes/functions.php';

requireLogin();

// Search logic similar to admin but read-only
$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';
$grade_filter = isset($_GET['grade']) ? sanitize($_GET['grade']) : '';

$sql = "SELECT * FROM students WHERE (full_name LIKE ? OR admission_no LIKE ?)";
$params = ["%$search%", "%$search%"];

if ($grade_filter) {
    $sql .= " AND grade = ?";
    $params[] = $grade_filter;
}

$sql .= " ORDER BY grade ASC, class ASC, name_with_initials ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$students = $stmt->fetchAll();

$path_level = 1;
include '../includes/header.php';
?>

<div class="container">
    <h2 class="page-title">Student Directory</h2>
    
    <div class="card" style="margin-bottom: 20px;">
        <form method="GET" action="" style="display: flex; gap: 15px; align-items: end;">
            <div class="form-group" style="flex: 2; margin-bottom: 0;">
                <label>Search (Name/Admission No)</label>
                <input type="text" name="search" value="<?php echo $search; ?>" placeholder="Search...">
            </div>
            <div class="form-group" style="flex: 1; margin-bottom: 0;">
                <label>Filter by Grade</label>
                <select name="grade">
                    <option value="">All Grades</option>
                    <?php for($i=1; $i<=13; $i++) echo "<option value='$i' ".($grade_filter == $i ? 'selected':'').">Grade $i</option>"; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-secondary">Filter</button>
        </form>
    </div>

    <!-- Grid View for Teachers -->
    <div class="card-grid">
        <?php foreach ($students as $student): ?>
        <div class="card" style="display: flex; gap: 15px;">
             <div style="flex-shrink: 0; width: 80px; height: 80px; background: #eee; overflow: hidden; border-radius: 50%;">
                <?php if($student['photo_path']): ?>
                    <img src="../uploads/<?php echo $student['photo_path']; ?>" style="width: 100%; height: 100%; object-fit: cover;">
                <?php else: ?>
                    <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #aaa;">
                        <i class="fas fa-user fa-2x"></i>
                    </div>
                <?php endif; ?>
            </div>
            <div>
                <h4 style="margin-bottom: 5px;"><?php echo sanitize($student['name_with_initials']); ?></h4>
                <p style="font-size: 0.9rem; color: #555;"><?php echo sanitize($student['admission_no']); ?></p>
                <div style="margin-top: 5px;">
                    <span class="badge" style="background: #eee; color: #333;">Gr <?php echo sanitize($student['grade']) . '-' . sanitize($student['class']); ?></span>
                    <span class="badge" style="background: #eee; color: #333;"><?php echo sanitize($student['gender']); ?></span>
                </div>
                <div style="margin-top: 10px; font-size: 0.8rem;">
                    <strong>Parent:</strong> <?php echo sanitize($student['parent_contact']); ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    
    <?php if (empty($students)): ?>
        <p style="text-align: center; margin-top: 30px;">No students found.</p>
    <?php endif; ?>

</div>

<?php include '../includes/footer.php'; ?>
