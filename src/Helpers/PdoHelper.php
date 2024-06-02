<?php

namespace App\Helpers;

use PDO;

class PdoHelper
{
    protected static ?PDO $pdo = null;

    public static function getPdo(): ?PDO
    {
        if (!self::$pdo) {
            $host = Config::get('db.host');
            $db   = Config::get('db.database');
            $user = Config::get('db.user');
            $pass = Config::get('db.password');
            $charset = Config::get('db.charset');

            $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
            self::$pdo = new PDO($dsn, $user, $pass);
        }
        
        return self::$pdo;
    }
}
