<?php
$title = 'Teachers';
ob_start();
?>
<h1>Teachers</h1>
<form method="post" class="row g-3 mb-3">
    <div class="col-auto">
        <input type="text" name="name" class="form-control" placeholder="Add teacher name" required>
    </div>
    <div class="col-auto">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="include_wednesday" id="include_wednesday">
            <label class="form-check-label" for="include_wednesday">Include Wednesdays</label>
        </div>
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-primary">Add</button>
    </div>
</form>
<table class="table table-bordered">
    <thead><tr><th>Name</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach ($teachers as $t): ?>
        <tr>
            <td>
                <?php if ($editId == $t['id']): ?>
                    <form method="post" class="d-inline">
                        <input type="hidden" name="edit_id" value="<?= $t['id'] ?>">
                        <input type="text" name="edit_name" value="<?= htmlspecialchars($t['name']) ?>" required>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="edit_include_wednesday" id="edit_include_wednesday_<?= $t['id'] ?>" <?= $t['include_wednesday'] ? 'checked' : '' ?>>
                            <label class="form-check-label" for="edit_include_wednesday_<?= $t['id'] ?>">Include Wednesdays</label>
                        </div>
                        <button type="submit" class="btn btn-sm btn-success">Save</button>
                    </form>
                <?php else: ?>
                    <?= htmlspecialchars($t['name']) ?>
                    <span class="badge bg-info text-dark ms-2">Wednesdays: <?= $t['include_wednesday'] ? 'Yes' : 'No' ?></span>
                <?php endif; ?>
            </td>
            <td>
                <?php if ($editId != $t['id']): ?>
                    <a href="?edit=<?= $t['id'] ?>" class="btn btn-sm btn-secondary">Edit</a>
                    <form method="post" class="d-inline delete-form">
                        <input type="hidden" name="delete_id" value="<?= $t['id'] ?>">
                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                    </form>
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
