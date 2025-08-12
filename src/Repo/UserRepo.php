<?php
namespace App\Repo;

use App\Db;

class UserRepo {
    public static function findByUsername($username) {
        $stmt = Db::get()->prepare('SELECT * FROM users WHERE username=?');
        $stmt->execute([$username]);
        return $stmt->fetch();
    }
    public static function all() {
        return Db::get()->query('SELECT * FROM users ORDER BY username')->fetchAll();
    }
    public static function add($username, $hash, $role) {
        $stmt = Db::get()->prepare('INSERT INTO users (username, password_hash, role) VALUES (?, ?, ?)');
        $stmt->execute([$username, $hash, $role]);
    }
    public static function delete($id) {
        $stmt = Db::get()->prepare('DELETE FROM users WHERE id=?');
        $stmt->execute([$id]);
    }
}
