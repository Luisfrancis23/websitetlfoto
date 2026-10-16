<?php
require_once __DIR__ . '/db.php';

$error = $_GET['error'] ?? '';
$success = $_GET['success'] ?? '';
$editingId = isset($_GET['edit']) ? (int) $_GET['edit'] : 0;

$editingItem = null;
if ($editingId > 0) {
    $editingItem = $pdo->prepare('SELECT * FROM activities WHERE id = :id LIMIT 1');
    $editingItem->execute([':id' => $editingId]);
    $editingItem = $editingItem->fetch();
}

$items = $pdo->query('SELECT * FROM activities ORDER BY created_at DESC')->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Panel - Timor-Leste Gallery</title>
    <link rel="stylesheet" href="<?= asset_url('style.css'); ?>" />
</head>
<body>
    <header class="topbar">
        <div class="container nav">
            <div class="brand">Manage Activities</div>
            <nav>
                <a href="index.php">Public Gallery</a>
                <a href="admin.php">Admin</a>
            </nav>
        </div>
    </header>

    <main class="container page admin-page">
        <section class="panel">
            <h2><?= $editingItem ? 'Edit activity' : 'Add new activity'; ?></h2>

            <?php if ($error): ?>
                <div class="message error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="message success">
                    <?= htmlspecialchars(str_replace('_', ' ', ucfirst($success)), ENT_QUOTES, 'UTF-8'); ?> successfully.
                </div>
            <?php endif; ?>

            <form action="save.php" method="post" enctype="multipart/form-data" class="activity-form">
                <input type="hidden" name="id" value="<?= $editingItem ? (int)$editingItem['id'] : ''; ?>">

                <div class="form-row">
                    <label>Title</label>
                    <input type="text" name="title" value="<?= htmlspecialchars($editingItem['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required />
                </div>

                <div class="form-row">
                    <label>Location</label>
                    <input type="text" name="location" value="<?= htmlspecialchars($editingItem['location'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required />
                </div>

                <div class="form-row">
                    <label>Date</label>
                    <input type="date" name="activity_date" value="<?= htmlspecialchars($editingItem['activity_date'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required />
                </div>

                <div class="form-row">
                    <label>Description</label>
                    <textarea name="description" rows="5" required><?= htmlspecialchars($editingItem['description'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                </div>

                <div class="form-row">
                    <label>Photo</label>
                    <input type="file" name="image" accept="image/*" <?= $editingItem ? '' : 'required'; ?> />
                </div>

                <?php if (!empty($editingItem['image'])): ?>
                    <div class="current-image-box">
                        <p>Current image:</p>
                        <img src="<?= preg_match('#^https?://#i', $editingItem['image']) ? htmlspecialchars($editingItem['image'], ENT_QUOTES, 'UTF-8') : 'uploads/' . basename($editingItem['image']); ?>" alt="Current activity" />
                    </div>
                <?php endif; ?>

                <div class="form-actions">
                    <button type="submit" class="primary-btn"><?= $editingItem ? 'Update Activity' : 'Save Activity'; ?></button>
                    <?php if ($editingItem): ?>
                        <a href="admin.php" class="secondary-btn">Cancel</a>
                    <?php endif; ?>
                </div>
            </form>
        </section>

        <section class="panel">
            <h2>Activity list</h2>
            <div class="list-grid">
                <?php foreach ($items as $item): ?>
                    <div class="admin-item">
                        <img src="<?= preg_match('#^https?://#i', $item['image']) ? htmlspecialchars($item['image'], ENT_QUOTES, 'UTF-8') : 'uploads/' . basename($item['image']); ?>" alt="<?= htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8'); ?>" />
                        <div>
                            <h3><?= htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8'); ?></h3>
                            <p><?= htmlspecialchars($item['location'], ENT_QUOTES, 'UTF-8'); ?></p>
                            <small><?= htmlspecialchars(date('d M Y', strtotime($item['activity_date'])), ENT_QUOTES, 'UTF-8'); ?></small>
                            <div class="admin-actions">
                                <a href="admin.php?edit=<?= (int) $item['id']; ?>" class="secondary-btn">Edit</a>
                                <a href="delete.php?id=<?= (int) $item['id']; ?>" class="danger-btn" onclick="return confirm('Delete this activity?');">Delete</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>
</body>
</html>
