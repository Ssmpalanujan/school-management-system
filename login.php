<?php
// login.php
require 'config/db.php';
require 'includes/functions.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = "Please fill in all fields.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Login Success
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['full_name'] = $user['full_name'];

            logActivity($pdo, $user['id'], 'Login', 'User logged in');

            if ($user['role'] === 'admin') {
                redirect('admin/dashboard.php');
            } else {
                redirect('teacher/dashboard.php');
            }
        } else {
            $error = "Invalid username or password.";
        }
    }
}

$path_level = 0;
include 'includes/header.php';
?>

<div class="login-container">
    <div class="login-header">
        <h2>System Login</h2>
        <p>Enter your credentials to access the system.</p>
    </div>

    <?php if ($error): ?>
        <div style="color: var(--danger); margin-bottom: 15px; padding: 10px; background: #ffeaea; border-radius: 4px;">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" class="btn" style="width: 100%;">Login</button>
    </form>
    
    <div style="margin-top: 20px; font-size: 0.9rem;">
        <p>Forgot user/pass? Contact Administration.</p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
