<?php
namespace App\Controller;

use App\Db;
use App\Helper\Week;

class AttendanceController {
    public function handle($method, $uri) {
        // Attendance page: teacher/class dropdowns, students table, checkboxes, reset button
        $teachers = \App\Repo\TeacherRepo::all();
        $selectedTeacher = isset($_GET['teacher']) ? (int)$_GET['teacher'] : 0;
        $classes = $selectedTeacher ? \App\Repo\ClassRepo::allByTeacher($selectedTeacher) : [];
        $selectedClass = isset($_GET['class']) ? (int)$_GET['class'] : 0;
        $students = $selectedClass ? \App\Repo\StudentRepo::allByClass($selectedClass) : [];
        $weekStart = \App\Helper\Week::currentWeekStart();
        $attendance = [];
        $includeWednesday = false;
        if ($selectedTeacher) {
            $teacher = \App\Repo\TeacherRepo::get($selectedTeacher);
            $includeWednesday = !empty($teacher['include_wednesday']);
        }
        if ($selectedClass) {
            $rows = \App\Repo\AttendanceRepo::getForClassWeek($selectedClass, $weekStart);
            foreach ($rows as $row) {
                $attendance[$row['student_id']][$row['day']] = $row['checked'];
            }
        }
        include __DIR__ . '/../../views/attendance.php';
    }
    public function toggle() {
        // Handle AJAX toggle (POST JSON)
        $data = json_decode(file_get_contents('php://input'), true);
        $classId = isset($data['classId']) ? (int)$data['classId'] : 0;
        $studentId = isset($data['studentId']) ? (int)$data['studentId'] : 0;
        $day = isset($data['day']) ? $data['day'] : '';
        $checked = isset($data['checked']) ? (int)$data['checked'] : 0;
        $validDays = ['Mon','Tue','Thu','Fri'];
        // Dynamically allow Wednesday if teacher has flag
        $includeWednesday = false;
        if ($classId) {
            // Get teacher for this class
            $class = \App\Repo\ClassRepo::get($classId);
            if ($class && !empty($class['teacher_id'])) {
                $teacher = \App\Repo\TeacherRepo::get($class['teacher_id']);
                if ($teacher && !empty($teacher['include_wednesday'])) {
                    $validDays[] = 'Wed';
                }
            }
        }
        if (!$classId || !$studentId || !in_array($day, $validDays, true)) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Invalid input']);
            return;
        }
        $weekStart = \App\Helper\Week::currentWeekStart();
        \App\Repo\AttendanceRepo::upsert($classId, $studentId, $weekStart, $day, $checked);
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
    }
    public function reset() {
        // Handle AJAX reset (POST JSON)
        $data = json_decode(file_get_contents('php://input'), true);
        $classId = isset($data['classId']) ? (int)$data['classId'] : 0;
        if (!$classId) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Missing classId']);
            return;
        }
        $weekStart = \App\Helper\Week::currentWeekStart();
        \App\Repo\AttendanceRepo::resetWeek($classId, $weekStart);
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
    }
}
