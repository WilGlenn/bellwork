<?php
namespace App\Repo;

use App\Db;

class StudentRepo {
    public static function upsert($classId, $name) {
        $pdo = Db::get();
        $stmt = $pdo->prepare('INSERT INTO students (class_id, name) VALUES (?, ?) ON DUPLICATE KEY UPDATE name=VALUES(name)');
        $stmt->execute([$classId, $name]);
        $stmt = $pdo->prepare('SELECT id FROM students WHERE class_id=? AND name=?');
        $stmt->execute([$classId, $name]);
        return $stmt->fetchColumn();
    }
    public static function allByClass($classId) {
        $stmt = Db::get()->prepare('SELECT * FROM students WHERE class_id=? ORDER BY sort_order, name');
        $stmt->execute([$classId]);
        return $stmt->fetchAll();
    }
    public static function get($id) {
        $stmt = Db::get()->prepare('SELECT * FROM students WHERE id=?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    public static function delete($id) {
        $pdo = Db::get();
        // Delete attendance for this student
        $pdo->prepare('DELETE FROM attendance WHERE student_id=?')->execute([$id]);
        // Delete student
        $pdo->prepare('DELETE FROM students WHERE id=?')->execute([$id]);
    }
    public static function update($id, $name) {
        $stmt = Db::get()->prepare('UPDATE students SET name=? WHERE id=?');
        $stmt->execute([$name, $id]);
    }
}
