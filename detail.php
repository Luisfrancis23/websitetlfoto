<?php
require_once __DIR__ . '/db.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$item = null;

if ($id > 0) {
    $stmt = $pdo->prepare('SELECT * FROM activities WHERE id = :id LIMIT 1');
    $stmt->execute([':id' => $id]);
    $item = $stmt->fetch();
}

if (!$item) {
    header('Location: index.php');
    exit;
}

$image = $item['image'];
if (!empty($image) && preg_match('#^https?://#i', $image)) {
    $src = $image;
} elseif (!empty($image)) {
    $src = 'uploads/' . basename($image);
} else {
    $src = 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1200&q=80';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8'); ?> - Timor-Leste</title>
    <link rel="stylesheet" href="<?= asset_url('style.css'); ?>" />
</head>
<body>
    <header class="topbar">
        <div class="container nav">
            <div class="brand">Timor-Leste Activity Gallery</div>
            <nav>
                <a href="index.php">Public Gallery</a>
                <a href="admin.php">Admin</a>
            </nav>
        </div>
    </header>

    <main class="container page detail-page">
        <div class="detail-banner">
            <img src="<?= htmlspecialchars($src, ENT_QUOTES, 'UTF-8'); ?>" alt="<?= htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8'); ?>" />
        </div>

        <section class="detail-content">
            <p class="eyebrow"><?= htmlspecialchars($item['location'], ENT_QUOTES, 'UTF-8'); ?></p>
            <h1><?= htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8'); ?></h1>
            <p class="activity-date">Date: <?= htmlspecialchars(date('d M Y', strtotime($item['activity_date'])), ENT_QUOTES, 'UTF-8'); ?></p>
            <p><?= nl2br(htmlspecialchars($item['description'], ENT_QUOTES, 'UTF-8')); ?></p>
            <a href="index.php" class="primary-btn">Back to gallery</a>
        </section>
    </main>
</body>
</html>
