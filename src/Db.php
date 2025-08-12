<?php
namespace App;

class Db {
    private static $pdo;

    public static function get(): \PDO {
        if (!self::$pdo) {
            $env = self::parseEnv();
            $dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s',
                $env['DB_HOST'], $env['DB_NAME'], $env['DB_CHARSET'] ?? 'utf8mb4');
            self::$pdo = new \PDO($dsn, $env['DB_USER'], $env['DB_PASS'], [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            ]);
        }
        return self::$pdo;
    }

    private static function parseEnv(): array {
        $lines = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $env = [];
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) continue;
            [$k, $v] = array_map('trim', explode('=', $line, 2));
            $env[$k] = $v;
        }
        return $env;
    }
}
