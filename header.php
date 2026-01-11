<?php
// includes/header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Adjust path for assets based on location
$path_level = isset($path_level) ? $path_level : 0;
$prefix = str_repeat('../', $path_level);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vipulananda Central College - Student Directory</title>
    <link rel="stylesheet" href="<?php echo $prefix; ?>assets/css/style.css">
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header>
        <div class="container header-content">
            <div class="logo-area">
                <!-- Placeholder for logo -->
                <i class="fas fa-university fa-3x"></i> 
                <div class="school-name">
                    <h1>Vipulananda Central College</h1>
                    <p>Karaitivu, Sri Lanka</p>
                </div>
            </div>
            <nav>
                <ul>
                    <li><a href="<?php echo $prefix; ?>index.php">Home</a></li>
                    <li><a href="<?php echo $prefix; ?>directory.php">Directory</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <?php if ($_SESSION['role'] === 'admin'): ?>
                            <li><a href="<?php echo $prefix; ?>admin/dashboard.php">Dashboard</a></li>
                        <?php else: ?>
                            <li><a href="<?php echo $prefix; ?>teacher/dashboard.php">Dashboard</a></li>
                        <?php endif; ?>
                        <li><a href="<?php echo $prefix; ?>logout.php">Logout</a></li>
                    <?php else: ?>
                        <li><a href="<?php echo $prefix; ?>login.php">Login</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
    <main class="container">
