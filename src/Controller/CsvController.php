<?php
namespace App\Controller;

class CsvController {
    public function handle($method, $uri) {
        $summary = '';
        if ($method === 'POST' && isset($_FILES['csv']) && $_FILES['csv']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['csv']['tmp_name'];
            $f = fopen($file, 'r');
            $header = fgetcsv($f);
            $expected = ['teacher_name','class_name','student_name'];
            $map = array_map('strtolower', $header);
            if (array_map('strtolower', $header) !== $expected) {
                $summary = 'Invalid CSV header. Expected: teacher_name,class_name,student_name';
            } else {
                $count = 0;
                $skipped = 0;
                while (($row = fgetcsv($f)) !== false) {
                    if (count($row) < 3) { $skipped++; continue; }
                    $teacher = \App\Util\NameUtil::normalize($row[0] ?? '');
                    $class = \App\Util\NameUtil::normalize($row[1] ?? '');
                    $student = \App\Util\NameUtil::normalize($row[2] ?? '');
                    if (!$teacher || !$class || !$student) { $skipped++; continue; }
                    $teacherId = \App\Repo\TeacherRepo::upsert($teacher);
                    $classId = \App\Repo\ClassRepo::upsert($teacherId, $class);
                    \App\Repo\StudentRepo::upsert($classId, $student);
                    $count++;
                }
                fclose($f);
                $_SESSION['flash'] = "Imported $count rows. Skipped $skipped rows.";
                header('Location: /setup');
                exit;
            }
        }
        include __DIR__ . '/../../views/csv.php';
    }
}
