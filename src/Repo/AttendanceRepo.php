<?php
namespace App\Repo;

use App\Db;

class AttendanceRepo {
    public static function upsert($classId, $studentId, $weekStart, $day, $checked) {
        $pdo = Db::get();
        $stmt = $pdo->prepare('INSERT INTO attendance (class_id, student_id, week_start, day, checked) VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE checked=VALUES(checked)');
        $stmt->execute([$classId, $studentId, $weekStart, $day, $checked]);
    }
    public static function getForClassWeek($classId, $weekStart) {
        $stmt = Db::get()->prepare('SELECT * FROM attendance WHERE class_id=? AND week_start=?');
        $stmt->execute([$classId, $weekStart]);
        return $stmt->fetchAll();
    }
    public static function resetWeek($classId, $weekStart) {
        $stmt = Db::get()->prepare('UPDATE attendance SET checked=0 WHERE class_id=? AND week_start=?');
        $stmt->execute([$classId, $weekStart]);
    }
}
