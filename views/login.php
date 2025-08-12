<?php
$title = 'Sign In';
ob_start();
?>
<h1>Sign In</h1>
<form method="post" class="mt-4" style="max-width:400px">
    <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" name="username" id="username" class="form-control" required autofocus>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" name="password" id="password" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Sign In</button>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger mt-3"> <?= htmlspecialchars($error) ?> </div>
    <?php endif; ?>
</form>
<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
