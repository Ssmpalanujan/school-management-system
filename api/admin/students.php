<?php
// admin/students.php
require '../config/db.php';
require '../includes/functions.php';

requireAdmin();

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM students WHERE id = ?");
    $stmt->execute([$id]);
    logActivity($pdo, $_SESSION['user_id'], 'Delete Student', "Deleted student ID $id");
    redirect('students.php');
}

// Search & Filter
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
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 class="page-title">Manage Students</h2>
        <a href="add_student.php" class="btn"><i class="fas fa-plus"></i> Add New Student</a>
    </div>

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

    <div class="card table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Adm No</th>
                    <th>Name</th>
                    <th>Grade-Class</th>
                    <th>Gender</th>
                    <th>Parent Contact</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $student): ?>
                <tr>
                    <td><?php echo sanitize($student['admission_no']); ?></td>
                    <td>
                        <div style="font-weight: bold;"><?php echo sanitize($student['name_with_initials']); ?></div>
                        <small style="color: #666;"><?php echo sanitize($student['full_name']); ?></small>
                    </td>
                    <td><?php echo sanitize($student['grade']) . '-' . sanitize($student['class']); ?></td>
                    <td><?php echo sanitize($student['gender']); ?></td>
                    <td><?php echo sanitize($student['parent_contact']); ?></td>
                    <td>
                        <span class="badge <?php echo strtolower($student['status']); ?>">
                            <?php echo sanitize($student['status']); ?>
                        </span>
                    </td>
                    <td>
                        <a href="edit_student.php?id=<?php echo $student['id']; ?>" class="btn-sm" title="Edit" style="color: var(--primary-color); margin-right: 10px;"><i class="fas fa-edit"></i></a>
                        <a href="students.php?delete=<?php echo $student['id']; ?>" class="btn-delete" title="Delete" style="color: var(--danger);"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($students)): ?>
                    <tr><td colspan="7" style="text-align:center;">No students found matching your criteria.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
