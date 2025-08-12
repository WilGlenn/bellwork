<?php
namespace App\Repo;

use App\Db;

class ClassRepo {
    public static function upsert($teacherId, $name) {
        $pdo = Db::get();
        $stmt = $pdo->prepare('INSERT INTO classes (teacher_id, name) VALUES (?, ?) ON DUPLICATE KEY UPDATE name=VALUES(name)');
        $stmt->execute([$teacherId, $name]);
        $stmt = $pdo->prepare('SELECT id FROM classes WHERE teacher_id=? AND name=?');
        $stmt->execute([$teacherId, $name]);
        return $stmt->fetchColumn();
    }
    public static function allByTeacher($teacherId) {
        $stmt = Db::get()->prepare('SELECT * FROM classes WHERE teacher_id=? ORDER BY name');
        $stmt->execute([$teacherId]);
        return $stmt->fetchAll();
    }
    public static function get($id) {
        $stmt = Db::get()->prepare('SELECT * FROM classes WHERE id=?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    public static function delete($id) {
        $pdo = Db::get();
        // Delete attendance for students in this class
        $stmt = $pdo->prepare('SELECT id FROM students WHERE class_id=?');
        $stmt->execute([$id]);
        $studentIds = $stmt->fetchAll(\PDO::FETCH_COLUMN);
        foreach ($studentIds as $studentId) {
            $pdo->prepare('DELETE FROM attendance WHERE student_id=?')->execute([$studentId]);
        }
        // Delete students
        $pdo->prepare('DELETE FROM students WHERE class_id=?')->execute([$id]);
        // Delete class
        $pdo->prepare('DELETE FROM classes WHERE id=?')->execute([$id]);
    }
    public static function update($id, $name) {
        $stmt = Db::get()->prepare('UPDATE classes SET name=? WHERE id=?');
        $stmt->execute([$name, $id]);
    }
}
