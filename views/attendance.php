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
<table class="table table-bordered align-middle" id="attendance-table">
    <thead>
        <tr>
            <th>Student</th>
            <th>Mon</th>
            <th>Tue</th>
            <?php if (!empty($includeWednesday)): ?><th>Wed</th><?php endif; ?>
            <th>Thu</th>
            <th>Fri</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($students as $s): ?>
        <tr data-student="<?= $s['id'] ?>">
            <td><span class="drag-handle" style="cursor:grab;">&#9776;</span> <?= htmlspecialchars($s['name']) ?></td>
            <?php
                $days = ["Mon","Tue"];
                if (!empty($includeWednesday)) $days[] = "Wed";
                $days = array_merge($days, ["Thu","Fri"]);
            ?>
            <?php foreach ($days as $day): ?>
                <?php
                    $status = isset($attendance[$s['id']][$day]) ? $attendance[$s['id']][$day] : '';
                    $attClass = '';
                    $attText = '□';
                    if ($status === '1' || $status === 1) {
                        $attClass = 'att-present';
                        $attText = '✔';
                    } elseif ($status === 2) {
                        $attClass = 'att-absent';
                        $attText = 'A';
                    }
                ?>
                <td class="att-toggle <?= $attClass ?>" tabindex="0" role="button" aria-label="Attendance status" data-class-id="<?= $selectedClass ?>" data-student-id="<?= $s['id'] ?>" data-day="<?= $day ?>">
                    <?= $attText ?>
                </td>
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
