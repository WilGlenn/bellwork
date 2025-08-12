<?php
$title = 'User Management';
ob_start();
?>
<h1>User Management</h1>
<form method="post" class="row g-3 mb-3" style="max-width:400px">
    <div class="col-auto">
        <input type="text" name="username" class="form-control" placeholder="Username" required>
    </div>
    <div class="col-auto">
        <input type="password" name="password" class="form-control" placeholder="Password" required>
    </div>
    <div class="col-auto">
        <select name="role" class="form-select">
            <option value="user">User</option>
            <option value="admin">Admin</option>
        </select>
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-primary">Add User</button>
    </div>
</form>
<table class="table table-bordered">
    <thead><tr><th>Username</th><th>Role</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach ($users as $u): ?>
        <tr>
            <td><?= htmlspecialchars($u['username']) ?></td>
            <td><?= htmlspecialchars($u['role']) ?></td>
            <td>
                <?php if ($u['id'] !== $_SESSION['user']['id']): ?>
                <form method="post" class="d-inline" onsubmit="return confirm('Delete this user?')">
                    <input type="hidden" name="delete_id" value="<?= $u['id'] ?>">
                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                </form>
                <?php else: ?>
                <span class="text-muted">(You)</span>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<a href="/setup" class="btn btn-link">Back to Setup</a>
<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
