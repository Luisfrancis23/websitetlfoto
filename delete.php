<?php
require_once __DIR__ . '/db.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    header('Location: admin.php?error=invalid_id');
    exit;
}

$stmt = $pdo->prepare('SELECT image FROM activities WHERE id = :id LIMIT 1');
$stmt->execute([':id' => $id]);
$item = $stmt->fetch();

if ($item && !empty($item['image']) && !preg_match('#^https?://#i', $item['image'])) {
    $filePath = APP_ROOT . '/' . ltrim($item['image'], '/');
    if (is_file($filePath)) {
        unlink($filePath);
    }
}

$pdo->prepare('DELETE FROM activities WHERE id = :id')->execute([':id' => $id]);
header('Location: admin.php?success=deleted');
exit;
