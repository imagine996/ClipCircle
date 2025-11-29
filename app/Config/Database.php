<?php
namespace App\Config;

class Database {
    public static function getConnection(): \PDO {
        static $pdo = null;
        if ($pdo === null) {
            $dsn = "mysql:host=127.0.0.1;dbname=dd;charset=utf8mb4";
            $pdo = new \PDO($dsn, 'dd', 'eYySiFGxMiAi6nPN', [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
            ]);
            // 保持连接时也宽容模式
            $pdo->exec("SET SESSION sql_mode = ''");
        }
        return $pdo;
    }
}