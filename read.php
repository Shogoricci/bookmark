<?php
require_once 'db.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';

if ($search !== '') {
    // タイトルまたはコメントから検索
    $stmt = $pdo->prepare("SELECT * FROM locations WHERE title LIKE ? OR comment LIKE ? ORDER BY id DESC");
    $stmt->execute(["%$search%", "%$search%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM locations ORDER BY id DESC");
}

$locations = $stmt->fetchAll(PDO::FETCH_ASSOC);