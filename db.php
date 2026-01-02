<?php
$host = 'localhost';
$dbname = 'map_bookmark_app';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
} catch (PDOException $e) {
    die("DB接続エラー: " . $e->getMessage());
}
