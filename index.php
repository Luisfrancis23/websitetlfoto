<?php
require_once __DIR__ . '/db.php';

$items = $pdo->query('SELECT * FROM activities ORDER BY created_at DESC')->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Timor-Leste Public Gallery</title>
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

    <main class="container page">
        <section class="hero">
            <div>
                <p class="eyebrow">Public tourism and activity archive</p>
                <h1>Explore the beauty and culture of Timor-Leste</h1>
                <p>
                    Discover scenic destinations, local traditions, beaches, islands, and memorable activities shared by the community.
                </p>
            </div>
        </section>

        <section class="gallery-grid">
            <?php foreach ($items as $item): ?>
                <article class="card">
                    <?php
                    $image = $item['image'];
                    if (!empty($image) && preg_match('#^https?://#i', $image)) {
                        $src = $image;
                    } elseif (!empty($image)) {
                        $src = 'uploads/' . basename($image);
                    } else {
                        $src = 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1200&q=80';
                    }
                    ?>
                    <img src="<?= htmlspecialchars($src, ENT_QUOTES, 'UTF-8'); ?>" alt="<?= htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8'); ?>" />
                    <div class="card-body">
                        <span class="meta"><?= htmlspecialchars($item['location'], ENT_QUOTES, 'UTF-8'); ?></span>
                        <h2><?= htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8'); ?></h2>
                        <p><?= htmlspecialchars(substr($item['description'], 0, 120), ENT_QUOTES, 'UTF-8'); ?>...</p>
                        <div class="card-footer">
                            <span><?= htmlspecialchars(date('d M Y', strtotime($item['activity_date'])), ENT_QUOTES, 'UTF-8'); ?></span>
                            <a href="detail.php?id=<?= (int) $item['id']; ?>">View details</a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </section>
    </main>
</body>
</html>
