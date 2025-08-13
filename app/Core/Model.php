<?php
namespace App\Core;

use PDO;

abstract class Model
{
    protected PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public static function db(): PDO
    {
        return Database::connection();
    }
}