<?php
$title = 'CSV Import';
ob_start();
?>
<h1>Bulk Import CSV</h1>
<form method="post" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="csv" class="form-label">CSV File</label>
        <input type="file" name="csv" id="csv" class="form-control" required accept=".csv">
    </div>
    <button type="submit" class="btn btn-primary">Import</button>
</form>
<?php if (!empty($summary)): ?>
    <div class="alert alert-info mt-3">
        <?= nl2br(htmlspecialchars($summary)) ?>
    </div>
<?php endif; ?>
<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
