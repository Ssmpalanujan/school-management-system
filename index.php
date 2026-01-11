<?php
$path_level = 0;
include 'includes/header.php';
?>

<div class="hero" style="text-align: center; padding: 50px 0;">
    <h2>Welcome to the Student Directory System</h2>
    <p style="font-size: 1.2rem; margin-top: 10px; color: #555;">Efficiently managing student records for a brighter future.</p>
    
    <div style="margin-top: 30px;">
        <a href="directory.php" class="btn">Search Directory</a>
        <?php if (!isset($_SESSION['user_id'])): ?>
            <a href="login.php" class="btn btn-secondary">Staff Login</a>
        <?php endif; ?>
    </div>
</div>

<div class="stats-section" style="margin-top: 50px;">
    <h3 class="page-title">School Insights</h3>
    <div class="card-grid">
        <div class="card" style="text-align: center;">
            <i class="fas fa-user-graduate fa-3x" style="color: var(--primary-color);"></i>
            <h3 style="margin: 10px 0;">Quality Education</h3>
            <p>Providing excellence in education for over 50 years.</p>
        </div>
        <div class="card" style="text-align: center;">
            <i class="fas fa-chalkboard-teacher fa-3x" style="color: var(--primary-color);"></i>
            <h3 style="margin: 10px 0;">Qualified Staff</h3>
            <p>Dedicated teachers committed to student success.</p>
        </div>
        <div class="card" style="text-align: center;">
            <i class="fas fa-futbol fa-3x" style="color: var(--primary-color);"></i>
            <h3 style="margin: 10px 0;">Extra Curricular</h3>
            <p>Focusing on sports and holistic development.</p>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
