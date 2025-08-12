<?php
$title = 'Setup';
ob_start();
?>
<h1>Setup</h1>
<ul>
    <li><a href="/setup/teachers">Teachers</a></li>
    <li><a href="/setup/classes">Classes</a></li>
    <li><a href="/setup/students">Students</a></li>
    <li><a href="/setup/csv">Bulk Import CSV</a></li>
    <?php if (!empty($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
        <li><a href="/setup/users">User Management</a></li>
    <?php endif; ?>
</ul>
<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
