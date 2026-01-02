<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $lat = $_POST['lat'];
    $lng = $_POST['lng'];
    $comment = $_POST['comment'];

    if (!empty($title) && !empty($lat) && !empty($lng)) {
        $stmt = $pdo->prepare("INSERT INTO locations (title, lat, lng, comment) VALUES (?, ?, ?, ?)");
        $stmt->execute([$title, $lat, $lng, $comment]);
    }
}

if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM locations WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
}

header("Location: index.php");
exit;