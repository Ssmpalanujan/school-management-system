<?php
// admin/users.php
require '../config/db.php';
require '../includes/functions.php';

requireAdmin();

$error = '';
$success = '';

// Add User
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
    $username = sanitize($_POST['username']);
    $role = $_POST['role'];
    $full_name = sanitize($_POST['full_name']);
    $password = $_POST['password']; // Plain text, will hash
    
    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, password, role, full_name) VALUES (?, ?, ?, ?)");
        $stmt->execute([$username, $hashed_password, $role, $full_name]);
        $success = "User added successfully!";
         logActivity($pdo, $_SESSION['user_id'], 'Add User', "Added user $username");
    } catch (PDOException $e) {
        $error = "User creation failed. Username might exist.";
    }
}

// Delete User
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    if ($id != $_SESSION['user_id']) { // Check not deleting self
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
        logActivity($pdo, $_SESSION['user_id'], 'Delete User', "Deleted user ID $id");
        $success = "User deleted.";
    } else {
        $error = "Cannot delete your own account.";
    }
}

$users = $pdo->query("SELECT * FROM users ORDER BY role ASC")->fetchAll();

$path_level = 1;
include '../includes/header.php';
?>

<div class="container">
    <h2 class="page-title">Manage System Users</h2>
    
    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 30px;">
        
        <!-- Add User Form -->
        <div class="card">
            <h3>Add New User</h3>
            <?php if ($error): ?>
                <div style="color: red; margin: 10px 0;"><?php echo $error; ?></div>
            <?php endif; ?>
             <?php if ($success): ?>
                <div style="color: green; margin: 10px 0;"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <input type="hidden" name="add_user" value="1">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" required>
                </div>
                <div class="form-group">
                    <label>Pasword</label>
                    <input type="password" name="password" required>
                </div>
                <div class="form-group">
                    <label>Role</label>
                    <select name="role">
                        <option value="teacher">Teacher</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="full_name" required>
                </div>
                <button type="submit" class="btn">Create User</button>
            </form>
        </div>

        <!-- User List -->
        <div class="card table-responsive">
            <h3>Existing Users</h3>
            <table>
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $u): ?>
                    <tr>
                        <td><?php echo sanitize($u['username']); ?></td>
                        <td><?php echo sanitize($u['full_name']); ?></td>
                        <td><?php echo ucfirst($u['role']); ?></td>
                        <td>
                            <?php if($u['id'] != $_SESSION['user_id']): ?>
                                <a href="users.php?delete=<?php echo $u['id']; ?>" class="btn-delete" style="color: var(--danger);"><i class="fas fa-trash"></i></a>
                            <?php else: ?>
                                <span style="font-size: 0.8rem; color: #888;">(You)</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
