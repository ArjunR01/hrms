<?php
require_once __DIR__ . '/config/db.php';

$db = new Database();
$conn = $db->getConnection();

echo "DB CONNECTED SUCCESSFULLY";
