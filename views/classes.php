<?php
$title = 'Classes';
ob_start();
?>
<h1>Classes</h1>
<form method="get" class="row g-3 mb-3">
    <div class="col-auto">
        <select name="teacher" class="form-select" onchange="this.form.submit()">
            <option value="">Select Teacher</option>
            <?php foreach ($teachers as $t): ?>
                <option value="<?= $t['id'] ?>" <?= $selectedTeacher == $t['id'] ? 'selected' : '' ?>><?= htmlspecialchars($t['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</form>
<?php if ($selectedTeacher): ?>
<form method="post" class="row g-3 mb-3">
    <div class="col-auto">
        <input type="text" name="name" class="form-control" placeholder="Add class name" required>
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-primary">Add</button>
    </div>
</form>
<table class="table table-bordered">
    <thead><tr><th>Name</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach ($classes as $c): ?>
        <tr>
            <td>
                <?php if ($editId == $c['id']): ?>
                    <form method="post" class="d-inline">
                        <input type="hidden" name="edit_id" value="<?= $c['id'] ?>">
                        <input type="text" name="edit_name" value="<?= htmlspecialchars($c['name']) ?>" required>
                        <button type="submit" class="btn btn-sm btn-success">Save</button>
                    </form>
                <?php else: ?>
                    <?= htmlspecialchars($c['name']) ?>
                <?php endif; ?>
            </td>
            <td>
                <?php if ($editId != $c['id']): ?>
                    <a href="?teacher=<?= $selectedTeacher ?>&edit=<?= $c['id'] ?>" class="btn btn-sm btn-secondary">Edit</a>
                    <form method="post" class="d-inline delete-form">
                        <input type="hidden" name="delete_id" value="<?= $c['id'] ?>">
                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                    </form>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>
<a href="/setup" class="btn btn-link">Back to Setup</a>
<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
