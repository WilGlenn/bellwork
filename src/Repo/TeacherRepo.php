<?php
namespace App\Repo;

use App\Db;

class TeacherRepo {
    public static function upsert($name) {
        $pdo = Db::get();
        $stmt = $pdo->prepare('INSERT INTO teachers (name) VALUES (?) ON DUPLICATE KEY UPDATE name=VALUES(name)');
        $stmt->execute([$name]);
        return $pdo->query('SELECT id FROM teachers WHERE name=' . $pdo->quote($name))->fetchColumn();
    }
    public static function all() {
        return Db::get()->query('SELECT * FROM teachers ORDER BY name')->fetchAll();
    }
    public static function get($id) {
        $stmt = Db::get()->prepare('SELECT * FROM teachers WHERE id=?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    public static function delete($id) {
        $pdo = Db::get();
        // Get all classes for this teacher
        $stmt = $pdo->prepare('SELECT id FROM classes WHERE teacher_id=?');
        $stmt->execute([$id]);
        $classIds = $stmt->fetchAll(\PDO::FETCH_COLUMN);
        foreach ($classIds as $classId) {
            // Delete attendance for students in this class
            $stmt2 = $pdo->prepare('SELECT id FROM students WHERE class_id=?');
            $stmt2->execute([$classId]);
            $studentIds = $stmt2->fetchAll(\PDO::FETCH_COLUMN);
            foreach ($studentIds as $studentId) {
                $pdo->prepare('DELETE FROM attendance WHERE student_id=?')->execute([$studentId]);
            }
            // Delete students
            $pdo->prepare('DELETE FROM students WHERE class_id=?')->execute([$classId]);
            // Delete class
            $pdo->prepare('DELETE FROM classes WHERE id=?')->execute([$classId]);
        }
        // Delete teacher
        $pdo->prepare('DELETE FROM teachers WHERE id=?')->execute([$id]);
    }
    public static function update($id, $name) {
        $stmt = Db::get()->prepare('UPDATE teachers SET name=? WHERE id=?');
        $stmt->execute([$name, $id]);
    }
}
