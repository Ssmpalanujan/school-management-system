<?php
// directory.php
require 'config/db.php';
require 'includes/functions.php';

// Search
$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';
$grade_filter = isset($_GET['grade']) ? sanitize($_GET['grade']) : '';

// Only show Active students to public
$sql = "SELECT admission_no, name_with_initials, grade, class, gender FROM students WHERE status = 'Active'";
$params = [];

if ($search) {
    $sql .= " AND (name_with_initials LIKE ? OR admission_no LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($grade_filter) {
    $sql .= " AND grade = ?";
    $params[] = $grade_filter;
}

$sql .= " ORDER BY grade ASC, class ASC, name_with_initials ASC LIMIT 50";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$students = $stmt->fetchAll();

$path_level = 0;
include 'includes/header.php';
?>

<div class="container">
    <h2 class="page-title">Public Student Directory</h2>
    <p>Search for active students by name or admission number. Limited details are shown for privacy.</p>
    
    <div class="card" style="margin-bottom: 20px;">
        <form method="GET" action="" style="display: flex; gap: 15px; align-items: end;">
            <div class="form-group" style="flex: 2; margin-bottom: 0;">
                <label>Search Name</label>
                <input type="text" name="search" value="<?php echo $search; ?>" placeholder="Name or Admission No...">
            </div>
            <div class="form-group" style="flex: 1; margin-bottom: 0;">
                <label>Grade</label>
                <select name="grade">
                    <option value="">All Grades</option>
                    <?php for($i=1; $i<=13; $i++) echo "<option value='$i' ".($grade_filter == $i ? 'selected':'').">Grade $i</option>"; ?>
                </select>
            </div>
            <button type="submit" class="btn">Search Directory</button>
        </form>
    </div>

    <div class="card table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Grade-Class</th>
                    <th>Name</th>
                    <th>Admission No</th>
                    <th>Gender</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $student): ?>
                <tr>
                    <td><span class="badge" style="background:#003366; color:white;">Gr <?php echo sanitize($student['grade']) . '-' . sanitize($student['class']); ?></span></td>
                    <td><?php echo sanitize($student['name_with_initials']); ?></td>
                    <td><?php echo sanitize($student['admission_no']); ?></td>
                    <td><?php echo sanitize($student['gender']); ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($students)): ?>
                    <tr><td colspan="4" style="text-align:center;">No students found or no search criteria entered.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
