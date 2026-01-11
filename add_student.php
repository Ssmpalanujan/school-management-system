<?php
// admin/add_student.php
require '../config/db.php';
require '../includes/functions.php';

requireAdmin();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect Data
    $admission_no = sanitize($_POST['admission_no']);
    $full_name = sanitize($_POST['full_name']);
    $name_with_initials = sanitize($_POST['name_with_initials']);
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $grade = $_POST['grade'];
    $class = $_POST['class'];
    $medium = $_POST['medium'];
    $address = sanitize($_POST['address']);
    $parent_name = sanitize($_POST['parent_name']);
    $parent_contact = sanitize($_POST['parent_contact']);
    $emergency_contact = sanitize($_POST['emergency_contact']);
    $admission_date = $_POST['admission_date'];
    
    // Photo Upload (Basic)
    $photo_path = null;
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $upload_dir = '../uploads/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        
        $file_ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png'];
        
        if (in_array($file_ext, $allowed)) {
            $new_filename = $admission_no . '_' . time() . '.' . $file_ext;
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $upload_dir . $new_filename)) {
                $photo_path = $new_filename;
            } else {
                $error = "Failed to upload photo.";
            }
        } else {
            $error = "Invalid file type. Only JPG, JPEG, PNG allowed.";
        }
    }

    if (!$error) {
        try {
            $sql = "INSERT INTO students (admission_no, full_name, name_with_initials, gender, dob, grade, class, medium, address, parent_name, parent_contact, emergency_contact, admission_date, photo_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$admission_no, $full_name, $name_with_initials, $gender, $dob, $grade, $class, $medium, $address, $parent_name, $parent_contact, $emergency_contact, $admission_date, $photo_path]);
            
            $success = "Student added successfully!";
            logActivity($pdo, $_SESSION['user_id'], 'Add Student', "Added student $admission_no");
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $error = "Admission Number already exists.";
            } else {
                $error = "Database Error: " . $e->getMessage();
            }
        }
    }
}

$path_level = 1;
include '../includes/header.php';
?>

<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h2 class="page-title">Register New Student</h2>
        <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>

    <?php if ($error): ?>
        <div style="background: #ffeaea; color: var(--danger); padding: 10px; margin-bottom: 20px; border-radius: 4px;"><?php echo $error; ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div style="background: #d4edda; color: var(--success); padding: 10px; margin-bottom: 20px; border-radius: 4px;"><?php echo $success; ?></div>
    <?php endif; ?>

    <form method="POST" action="" enctype="multipart/form-data" style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">
            
            <div class="form-left">
                <div class="row" style="display: flex; gap: 20px;">
                    <div class="form-group" style="flex: 1;">
                        <label>Admission No *</label>
                        <input type="text" name="admission_no" required>
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label>Admission Date *</label>
                        <input type="date" name="admission_date" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Full Name *</label>
                    <input type="text" name="full_name" required>
                </div>

                <div class="form-group">
                    <label>Name with Initials *</label>
                    <input type="text" name="name_with_initials" required>
                </div>

                <div class="row" style="display: flex; gap: 20px;">
                    <div class="form-group" style="flex: 1;">
                        <label>Gender *</label>
                        <select name="gender" required>
                            <option value="">Select</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label>Date of Birth *</label>
                        <input type="date" name="dob" required>
                    </div>
                </div>

                <div class="row" style="display: flex; gap: 20px;">
                    <div class="form-group" style="flex: 1;">
                        <label>Grade *</label>
                        <select name="grade" required>
                            <?php for($i=1; $i<=13; $i++) echo "<option value='$i'>Grade $i</option>"; ?>
                        </select>
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label>Class *</label>
                        <select name="class" required>
                            <?php foreach(['A','B','C','D','E','F'] as $c) echo "<option value='$c'>$c</option>"; ?>
                        </select>
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label>Medium *</label>
                        <select name="medium" required>
                            <option value="Tamil">Tamil</option>
                            <option value="Sinhala">Sinhala</option>
                            <option value="English">English</option>
                        </select>
                    </div>
                </div>

                 <div class="form-group">
                    <label>Address</label>
                    <textarea name="address" rows="3"></textarea>
                </div>
            </div>

            <div class="form-right">
                <div class="photo-upload" style="text-align: center; margin-bottom: 20px;">
                    <label>Student Photo</label>
                    <div style="width: 150px; height: 150px; background: #eee; border: 1px dashed #aaa; margin: 10px auto; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                        <img id="image-preview" src="#" alt="Preview" style="display: none; width: 100%; height: auto;">
                        <span id="placeholder-text" style="color: #888;">No Image</span>
                    </div>
                    <input type="file" id="photo" name="photo" accept="image/*">
                </div>

                <div class="form-group">
                    <label>Parent / Guardian Name *</label>
                    <input type="text" name="parent_name" required>
                </div>

                <div class="form-group">
                    <label>Parent Contact *</label>
                    <input type="text" name="parent_contact" required>
                </div>

                 <div class="form-group">
                    <label>Emergency Contact</label>
                    <input type="text" name="emergency_contact">
                </div>
            </div>
        </div>

        <div style="margin-top: 20px; text-align: right;">
            <button type="reset" class="btn btn-secondary">Reset</button>
            <button type="submit" class="btn">Save Student Record</button>
        </div>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
