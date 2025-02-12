<?php

require_once __DIR__ . "/../vendor/autoload.php";

use App\Core\Database;

$db = Database::getInstance();
$db->exec("CREATE TABLE IF NOT EXISTS pages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL
)");

echo "Database migration completed.\n";
