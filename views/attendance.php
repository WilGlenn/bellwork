<?php
// Variables expected: $teachers, $classes, $students, $selectedTeacher, $selectedClass, $attendance, $weekStart
$title = 'Attendance';
ob_start();
?>
<h1>Attendance</h1>
<form method="get" class="row g-3 mb-3">
    <div class="col-auto">
        <select name="teacher" class="form-select" onchange="this.form.submit()">
            <option value="">Select Teacher</option>
            <?php foreach ($teachers as $t): ?>
                <option value="<?= $t['id'] ?>" <?= $selectedTeacher == $t['id'] ? 'selected' : '' ?>><?= htmlspecialchars($t['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-auto">
        <select name="class" class="form-select" onchange="this.form.submit()">
            <option value="">Select Class</option>
            <?php foreach ($classes as $c): ?>
                <option value="<?= $c['id'] ?>" <?= $selectedClass == $c['id'] ? 'selected' : '' ?>><?= htmlspecialchars($c['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-auto ms-auto">
        <?php if ($selectedClass): ?>
        <button type="button" id="reset-week" class="btn btn-danger" data-class-id="<?= $selectedClass ?>">Reset Week</button>
        <?php endif; ?>
    </div>
</form>
<?php if ($students): ?>
<table class="table table-bordered align-middle">
    <thead>
        <tr>
            <th>Student</th>
            <th>Mon</th>
            <th>Tue</th>
            <th>Thu</th>
            <th>Fri</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($students as $s): ?>
        <tr data-student="<?= $s['id'] ?>">
            <td><?= htmlspecialchars($s['name']) ?></td>
            <?php foreach (["Mon","Tue","Thu","Fri"] as $day): ?>
                <td><input type="checkbox" class="form-check-input att-checkbox" data-class-id="<?= $selectedClass ?>" data-student-id="<?= $s['id'] ?>" data-day="<?= $day ?>" <?= !empty($attendance[$s['id']][$day]) ? 'checked' : '' ?>></td>
            <?php endforeach; ?>
            <td class="att-total">0</td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php elseif ($selectedClass): ?>
    <div class="alert alert-warning">No students in this class.</div>
<?php endif; ?>
<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
